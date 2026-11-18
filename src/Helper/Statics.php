<?php

namespace EasyTel\Helper;

use EasyTel\Telegram;
use JSON\json;

class Statics
{
    /** Built-in type names that must never be treated as a class. */
    private const BUILTIN_TYPES = [
        'int', 'float', 'string', 'bool', 'true', 'false', 'array', 'object', 'mixed',
        'null', 'iterable', 'callable', 'void', 'never', 'self', 'static', 'parent'
    ];

    /** @var array<string, array{0: int, 1: ?string}> cached docblock array element info */
    private static array $listCache = [];

    /**
     * Normalize data into the requested output format.
     */
    public static function output(mixed $data, int $type = Telegram::OUTPUT_JSON): mixed
    {
        if (is_bool($data) || is_int($data) || is_float($data) || is_null($data)) {
            return $data;
        }

        $converted = match ($type) {
            Telegram::OUTPUT_JSON => json::to_json($data),
            Telegram::OUTPUT_OBJECT => json::to_object($data),
            default => json::to_array($data),
        };

        // A body that is not valid JSON (gateway HTML error page, empty body, ...)
        // is returned untouched instead of being silently turned into false.
        if ($converted === false && is_string($data)) {
            return $data;
        }

        return $converted;
    }

    /**
     * Hydrate a generated Type object from a decoded Telegram payload.
     *
     * - scalar/`array` properties receive the raw value,
     * - single-object properties are built from their declared class,
     * - `array` properties declared as `@var Type[]` (or `@var Type[][]`) are
     *   mapped to arrays of Type objects,
     * - union properties are filled only with a class-compatible instance.
     */
    public static function hydrate(object $instance, array $update): void
    {
        if ($update === []) {
            return;
        }

        $reflection = new \ReflectionClass($instance);

        if (self::hydrateUnionBase($instance, $reflection, $update)) {
            return;
        }

        foreach ($update as $name => $value) {
            if (!is_string($name) || !$reflection->hasProperty($name)) {
                continue;
            }

            $property = $reflection->getProperty($name);
            if (!$property->isPublic()) {
                continue;
            }

            $type = $property->getType();
            if ($type === null) {
                $instance->{$name} = $value;
                continue;
            }

            if ($type instanceof \ReflectionNamedType) {
                if ($type->isBuiltin()) {
                    $instance->{$name} = ($type->getName() === 'array' && is_array($value))
                        ? self::hydrateList($property, $value)
                        : $value;
                } else {
                    $object = self::makeObject($type->getName(), $value);
                    if ($object !== null) {
                        $instance->{$name} = $object;
                    }
                }
                continue;
            }

            if ($type instanceof \ReflectionUnionType) {
                self::assignUnion($instance, $name, $type, $value);
            }
        }
    }

    /**
     * Assign a value to a union-typed property.
     *
     * Some documented unions also accept plain values next to the object
     * variants (RichText may be a String, an Array of RichText or a variant).
     */
    private static function assignUnion(object $instance, string $name, \ReflectionUnionType $type, mixed $value): void
    {
        $hasString = false;
        $hasArray = false;
        $classes = [];

        foreach ($type->getTypes() as $member) {
            if (!$member instanceof \ReflectionNamedType) {
                continue;
            }
            if ($member->isBuiltin()) {
                if ($member->getName() === 'string') {
                    $hasString = true;
                } elseif ($member->getName() === 'array') {
                    $hasArray = true;
                }
                continue;
            }
            $classes[] = $member->getName();
        }

        if ($hasString && is_string($value)) {
            $instance->{$name} = $value;
            return;
        }

        if (is_object($value)) {
            foreach ($classes as $class) {
                if ($value instanceof $class) {
                    $instance->{$name} = $value;
                    return;
                }
            }
            return;
        }

        if ($hasArray && is_array($value) && array_is_list($value)) {
            $items = [];
            foreach ($value as $key => $item) {
                $items[$key] = ($classes !== [] && is_array($item) && !array_is_list($item))
                    ? self::hydrateItem($classes[0], $item)
                    : $item;
            }
            $instance->{$name} = $items;
            return;
        }

        if ($classes === []) {
            $instance->{$name} = $value;
            return;
        }

        if (!is_array($value)) {
            return;
        }

        $object = self::makeUnionObject($type, $value);
        if ($object !== null) {
            $instance->{$name} = $object;
        }
    }

    /**
     * Union base types (e.g. BackgroundFill, ReactionType, InputMedia) declare one
     * property per concrete variant, named after the lower-cased variant class.
     * Every variant is built from the same payload, exactly like the generated
     * constructors used to do.
     */
    private static function hydrateUnionBase(object $instance, \ReflectionClass $reflection, array $update): bool
    {
        // Only classes generated as union bases carry this marker. Field names
        // alone are not reliable (e.g. CommunityChatAdded has a `community` and
        // a `user` field, which both look like variant names).
        if (!$reflection->hasConstant('_UNION_BASE')) {
            return false;
        }

        $variants = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $type = $property->getType();
            if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                return false;
            }

            $variants[] = [$property->getName(), $type->getName()];
        }

        if ($variants === []) {
            return false;
        }

        // Prefer the variant selected by the payload's discriminator; fall back
        // to filling every variant when the payload carries no usable type.
        $match = self::discriminate($reflection->getName(), $update);
        $matched = false;
        foreach ($variants as [, $class]) {
            if ($class === $match) {
                $matched = true;
                break;
            }
        }

        foreach ($variants as [$name, $class]) {
            if ($matched && $class !== $match) {
                continue;
            }

            if (array_key_exists($name, $update)) {
                $object = self::makeObject($class, $update[$name]);
                if ($object !== null) {
                    $instance->{$name} = $object;
                }
                continue;
            }

            $instance->{$name} = new $class($update);
        }

        return true;
    }


    private static function makeObject(string $class, mixed $value): ?object
    {
        if (is_object($value)) {
            return $value instanceof $class ? $value : null;
        }
        if (!is_array($value)) {
            return null;
        }

        // Generated type classes do not inherit from each other, so a property
        // declared as `MessageOrigin` (or any other union base class) can only
        // hold an instance of exactly that class. The base constructor hydrates
        // every variant from the payload; the discriminated subclass is only
        // used for untyped array elements, see hydrateItem().
        return new $class($value);
    }

    private static function makeUnionObject(\ReflectionUnionType $type, mixed $value): ?object
    {
        $classes = [];
        foreach ($type->getTypes() as $member) {
            if ($member instanceof \ReflectionNamedType && !$member->isBuiltin()) {
                $classes[] = $member->getName();
            }
        }

        if ($classes === []) {
            return null;
        }

        if (count($classes) === 1) {
            return self::makeObject($classes[0], $value);
        }

        if (!is_array($value)) {
            return null;
        }

        if (isset($value['type']) && is_string($value['type'])) {
            $suffix = strtolower(self::studly($value['type']));
            foreach ($classes as $class) {
                if (str_ends_with(strtolower(self::shortName($class)), $suffix)) {
                    return new $class($value);
                }
            }
        }

        // No usable discriminator: pick the variant that declares the most
        // fields present in the payload.
        $best = $classes[0];
        $bestScore = -1;
        foreach ($classes as $class) {
            $score = count(array_intersect(array_keys($value), self::propertyNames($class)));
            if ($score > $bestScore) {
                $best = $class;
                $bestScore = $score;
            }
        }

        return new $best($value);
    }

    /** @return string[] */
    private static function propertyNames(string $class): array
    {
        static $cache = [];

        if (!isset($cache[$class])) {
            $cache[$class] = array_map(
                fn(\ReflectionProperty $property) => $property->getName(),
                (new \ReflectionClass($class))->getProperties(\ReflectionProperty::IS_PUBLIC)
            );
        }

        return $cache[$class];
    }

    private static function shortName(string $class): string
    {
        $position = strrpos($class, '\\');
        return $position === false ? $class : substr($class, $position + 1);
    }

    /**
     * Pick the concrete subclass implied by a payload's `type` field, if one exists.
     */
    private static function discriminate(string $base, array $value): ?string
    {
        $type = $value['type'] ?? null;
        if (!is_string($type) || $type === '') {
            // ChatMember union variants are discriminated by their `status` field.
            $type = isset($value['status']) && is_string($value['status']) ? $value['status'] : null;
        }
        if ($type === null || $type === '') {
            return null;
        }

        $suffix = self::studly($type);
        $position = strrpos($base, '\\');
        $namespace = $position === false ? '' : substr($base, 0, $position + 1);

        foreach ([$base . $suffix, $namespace . $suffix] as $candidate) {
            if ($candidate !== $base && class_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private static function studly(string $value): string
    {
        $value = str_replace(['-', ' '], '_', $value);
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
    }

    /**
     * Map a list payload to objects using the property's `@var Type[]` docblock.
     */
    private static function hydrateList(\ReflectionProperty $property, array $value): array
    {
        $key = $property->getDeclaringClass()->getName() . '::' . $property->getName();

        if (!array_key_exists($key, self::$listCache)) {
            $doc = $property->getDocComment() ?: '';
            $depth = 0;
            $element = null;

            if (preg_match('/@var\s+(\S+)/', $doc, $matches)) {
                $docType = $matches[1];

                // Standard PHPDoc: MessageEntity[], InlineKeyboardButton[][]
                if (preg_match('/^([A-Za-z_][\w\\\\]*)((?:\[\])+)$/', $docType, $parts)) {
                    $depth = intdiv(strlen($parts[2]), 2);
                    $element = self::resolveType($parts[1]);
                }
                // Legacy prefix form: []MessageEntity
                elseif (preg_match('/^((?:\[\])+)([A-Za-z_][\w\\\\]*)$/', $docType, $parts)) {
                    $depth = intdiv(strlen($parts[1]), 2);
                    $element = self::resolveType($parts[2]);
                }
            }

            self::$listCache[$key] = [$depth, $element];
        }

        [$depth, $element] = self::$listCache[$key];

        if ($element === null) {
            return $value;
        }

        if ($depth < 2) {
            return array_map(fn($item) => self::hydrateItem($element, $item), $value);
        }

        return array_map(
            fn($row) => is_array($row)
                ? array_map(fn($item) => self::hydrateItem($element, $item), $row)
                : $row,
            $value
        );
    }

    private static function hydrateItem(string $class, mixed $item): mixed
    {
        if (is_object($item)) {
            return $item;
        }
        if (!is_array($item)) {
            return $item;
        }

        $concrete = self::discriminate($class, $item) ?? $class;
        return new $concrete($item);
    }

    private static function resolveType(string $type): ?string
    {
        if (in_array(strtolower($type), self::BUILTIN_TYPES, true)) {
            return null;
        }

        if (str_contains($type, '\\')) {
            return class_exists($type) ? $type : null;
        }

        $class = 'EasyTel\\Types\\' . $type;
        return class_exists($class) ? $class : null;
    }
}
