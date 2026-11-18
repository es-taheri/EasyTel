<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the position of a rectangle area on a story media or a TON wallet card.
 * @method self x_percentage(float $value) The abscissa of the area's center, as a percentage of the media or the card width
 * @method self y_percentage(float $value) The ordinate of the area's center, as a percentage of the media or the card height
 * @method self width_percentage(float $value) The width of the area's rectangle, as a percentage of the media or the card width
 * @method self height_percentage(float $value) The height of the area's rectangle, as a percentage of the media or the card height
 * @method self rotation_angle(float $value) The clockwise rotation angle of the rectangle, in degrees; 0-360
 * @method self corner_radius_percentage(float $value) The radius of the rectangle corner rounding, as a percentage of the media or the card width
 */
class AreaPosition
{
    /** @var float */
    public float $x_percentage;
    /** @var float */
    public float $y_percentage;
    /** @var float */
    public float $width_percentage;
    /** @var float */
    public float $height_percentage;
    /** @var float */
    public float $rotation_angle;
    /** @var float */
    public float $corner_radius_percentage;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes the position of a rectangle area on a story media or a TON wallet card.
     * @param float|null $x_percentage The abscissa of the area's center, as a percentage of the media or the card width
     * @param float|null $y_percentage The ordinate of the area's center, as a percentage of the media or the card height
     * @param float|null $width_percentage The width of the area's rectangle, as a percentage of the media or the card width
     * @param float|null $height_percentage The height of the area's rectangle, as a percentage of the media or the card height
     * @param float|null $rotation_angle The clockwise rotation angle of the rectangle, in degrees; 0-360
     * @param float|null $corner_radius_percentage The radius of the rectangle corner rounding, as a percentage of the media or the card width
     */
    public static function make(float $x_percentage = null, float $y_percentage = null, float $width_percentage = null, float $height_percentage = null, float $rotation_angle = null, float $corner_radius_percentage = null): self
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