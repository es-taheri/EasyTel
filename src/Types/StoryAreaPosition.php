<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the position of a clickable area within a story.
 * @method self x_percentage(float $value) The abscissa of the area&#39;s center, as a percentage of the media width
 * @method self y_percentage(float $value) The ordinate of the area&#39;s center, as a percentage of the media height
 * @method self width_percentage(float $value) The width of the area&#39;s rectangle, as a percentage of the media width
 * @method self height_percentage(float $value) The height of the area&#39;s rectangle, as a percentage of the media height
 * @method self rotation_angle(float $value) The clockwise rotation angle of the rectangle, in degrees; 0-360
 * @method self corner_radius_percentage(float $value) The radius of the rectangle corner rounding, as a percentage of the media width
 */
class StoryAreaPosition
{
    public float $x_percentage;
    public float $y_percentage;
    public float $width_percentage;
    public float $height_percentage;
    public float $rotation_angle;
    public float $corner_radius_percentage;

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
     * Describes the position of a clickable area within a story.
     * @param float|null $x_percentage The abscissa of the area&#39;s center, as a percentage of the media width
     * @param float|null $y_percentage The ordinate of the area&#39;s center, as a percentage of the media height
     * @param float|null $width_percentage The width of the area&#39;s rectangle, as a percentage of the media width
     * @param float|null $height_percentage The height of the area&#39;s rectangle, as a percentage of the media height
     * @param float|null $rotation_angle The clockwise rotation angle of the rectangle, in degrees; 0-360
     * @param float|null $corner_radius_percentage The radius of the rectangle corner rounding, as a percentage of the media width
     */
    public static function make(float $x_percentage = null, float $y_percentage = null, float $width_percentage = null, float $height_percentage = null, float $rotation_angle = null, float $corner_radius_percentage = null): self
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