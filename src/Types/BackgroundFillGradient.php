<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The background is a gradient fill.
 * @method self type(string $value) Type of the background fill, always “gradient”
 * @method self top_color(int $value) Top color of the gradient in the RGB24 format
 * @method self bottom_color(int $value) Bottom color of the gradient in the RGB24 format
 * @method self rotation_angle(int $value) Clockwise rotation angle of the background fill in degrees; 0-359
 */
class BackgroundFillGradient
{
    public string $type;
    public int $top_color;
    public int $bottom_color;
    public int $rotation_angle;

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
     * The background is a gradient fill.
     * @param string|null $type Type of the background fill, always “gradient”
     * @param int|null $top_color Top color of the gradient in the RGB24 format
     * @param int|null $bottom_color Bottom color of the gradient in the RGB24 format
     * @param int|null $rotation_angle Clockwise rotation angle of the background fill in degrees; 0-359
     */
    public static function make(string $type = null, int $top_color = null, int $bottom_color = null, int $rotation_angle = null): self
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