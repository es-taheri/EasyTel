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
    public string $type;
    public float $temperature;
    public string $emoji;
    public int $background_color;

    public function __construct(array $update = [])
    {
        $objects = array_keys($update);
        $r = new \ReflectionClass(static::class);
        foreach ($objects as $object):
            if ($r->hasProperty($object)):
                $prop = $r->getProperty($object);
                $type = $prop->getType();
                if (in_array(strtolower(trim($type)), ['string', 'true', 'false', 'bool', 'int', 'float', 'array', 'mixed']) || str_contains($type, '|'))
                    $this->{$object} = $update[$object];
            endif;
        endforeach;
        
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
        $updates = array_filter($args, fn($v) => isset($v));
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
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (isset($this->{$name})):
                $value = $property->getValue($this);
                $property_type = $property->getType();
                if ($property_type == 'object')
                    $output[$name] = (fn() => ($this->_output($type)))->bindTo($value, $value)();
                else
                    $output[$name] = $value;
            endif;
        endforeach;
        return Statics::output($output, $type);
    }
}