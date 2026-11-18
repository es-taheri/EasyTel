<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a point on the map.
 * @method self latitude(float $value) Latitude as defined by the sender
 * @method self longitude(float $value) Longitude as defined by the sender
 * @method self horizontal_accuracy(float $value) <em>Optional</em>. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method self live_period(int $value) <em>Optional</em>. Time relative to the message sending date, during which the location can be updated; in seconds. For active live locations only.
 * @method self heading(int $value) <em>Optional</em>. The direction in which user is moving, in degrees; 1-360. For active live locations only.
 * @method self proximity_alert_radius(int $value) <em>Optional</em>. The maximum distance for proximity alerts about approaching another chat member, in meters. For sent live locations only.
 */
class Location
{
    /** @var float */
    public float $latitude;
    /** @var float */
    public float $longitude;
    /** @var float */
    public float $horizontal_accuracy;
    /** @var int */
    public int $live_period;
    /** @var int */
    public int $heading;
    /** @var int */
    public int $proximity_alert_radius;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a point on the map.
     * @param float|null $latitude Latitude as defined by the sender
     * @param float|null $longitude Longitude as defined by the sender
     * @param float|null $horizontal_accuracy <em>Optional</em>. The radius of uncertainty for the location, measured in meters; 0-1500
     * @param int|null $live_period <em>Optional</em>. Time relative to the message sending date, during which the location can be updated; in seconds. For active live locations only.
     * @param int|null $heading <em>Optional</em>. The direction in which user is moving, in degrees; 1-360. For active live locations only.
     * @param int|null $proximity_alert_radius <em>Optional</em>. The maximum distance for proximity alerts about approaching another chat member, in meters. For sent live locations only.
     */
    public static function make(float $latitude = null, float $longitude = null, float $horizontal_accuracy = null, int $live_period = null, int $heading = null, int $proximity_alert_radius = null): self
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