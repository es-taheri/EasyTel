<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The background is automatically filled based on the selected colors.
 * @method self type(string $value) Type of the background, always “fill”
 * @method self fill(BackgroundFill $value) The background fill
 * @method self dark_theme_dimming(int $value) Dimming of the background in dark themes, as a percentage; 0-100
 */
class BackgroundTypeFill
{
    /** @var string */
    public string $type;
    /** @var BackgroundFill */
    public BackgroundFill $fill;
    /** @var int */
    public int $dark_theme_dimming;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The background is automatically filled based on the selected colors.
     * @param string|null $type Type of the background, always “fill”
     * @param BackgroundFill|null $fill The background fill
     * @param int|null $dark_theme_dimming Dimming of the background in dark themes, as a percentage; 0-100
     */
    public static function make(string $type = null, BackgroundFill $fill = null, int $dark_theme_dimming = null): self
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