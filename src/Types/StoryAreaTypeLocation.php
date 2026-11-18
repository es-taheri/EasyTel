<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a story area pointing to a location. Currently, a story can have up to 10 location areas.
 * @method self type(string $value) Type of the area, always “location”
 * @method self latitude(float $value) Location latitude in degrees
 * @method self longitude(float $value) Location longitude in degrees
 * @method self address(LocationAddress $value) <em>Optional</em>. Address of the location
 */
class StoryAreaTypeLocation
{
    /** @var string */
    public string $type;
    /** @var float */
    public float $latitude;
    /** @var float */
    public float $longitude;
    /** @var LocationAddress */
    public LocationAddress $address;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a story area pointing to a location. Currently, a story can have up to 10 location areas.
     * @param string|null $type Type of the area, always “location”
     * @param float|null $latitude Location latitude in degrees
     * @param float|null $longitude Location longitude in degrees
     * @param LocationAddress|null $address <em>Optional</em>. Address of the location
     */
    public static function make(string $type = null, float $latitude = null, float $longitude = null, LocationAddress $address = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => $v !== null);
        return new self($updates);
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    protected function _output(int $type = Telegram::OUTPUT_JSON): object|array|string
    {
        $output = [];
        $r = new \ReflectionClass(static::class);
        $union = $r->hasConstant('_UNION_BASE');

        $normalize = function ($value) use (&$normalize) {
            if (is_object($value) && method_exists($value, '_output'))
                return (fn() => $this->_output(Telegram::OUTPUT_ARRAY))->bindTo($value, $value)();
            if (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item)
                    $items[$key] = $normalize($item);
                return $items;
            }
            return $value;
        };

        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (!isset($this->{$name}))
                continue;

            $raw = $property->getValue($this);
            $value = $normalize($raw);

            // A union base variant property represents the payload itself, so
            // its fields replace the variant key.
            if ($union && is_object($raw) && is_array($value)
                && $name === strtolower((new \ReflectionClass($raw))->getShortName())):
                $output += $value;
                continue;
            endif;

            $output[$name] = $value;
        endforeach;
        return Statics::output($output, $type);
    }
}