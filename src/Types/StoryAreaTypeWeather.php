<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a story area containing weather information. Currently, a story can have up to 3 weather areas.
 * @method self type(string $value) Type of the area, always “weather”
 * @method self temperature(float $value) Temperature, in degree Celsius
 * @method self emoji(string $value) Emoji representing the weather
 * @method self background_color(int $value) A color of the area background in the ARGB format
 */
class StoryAreaTypeWeather
{
    /** @var string */
    public string $type;
    /** @var float */
    public float $temperature;
    /** @var string */
    public string $emoji;
    /** @var int */
    public int $background_color;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a story area containing weather information. Currently, a story can have up to 3 weather areas.
     * @param string|null $type Type of the area, always “weather”
     * @param float|null $temperature Temperature, in degree Celsius
     * @param string|null $emoji Emoji representing the weather
     * @param int|null $background_color A color of the area background in the ARGB format
     */
    public static function make(string $type = null, float $temperature = null, string $emoji = null, int $background_color = null): self
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