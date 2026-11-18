<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The background is a freeform gradient that rotates after every message in the chat.
 * @method self type(string $value) Type of the background fill, always “freeform_gradient”
 * @method self colors(int[] $value) A list of the 3 or 4 base colors that are used to generate the freeform gradient in the RGB24 format
 */
class BackgroundFillFreeformGradient
{
    /** @var string */
    public string $type;
    /** @var int[] */
    public array $colors;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The background is a freeform gradient that rotates after every message in the chat.
     * @param string|null $type Type of the background fill, always “freeform_gradient”
     * @param int[]|null $colors A list of the 3 or 4 base colors that are used to generate the freeform gradient in the RGB24 format
     */
    public static function make(string $type = null, array $colors = null): self
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