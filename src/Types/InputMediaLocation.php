<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a location to be sent.
 * @method self type(string $value) Type of the media, must be <em>location</em>
 * @method self latitude(float $value) Latitude of the location
 * @method self longitude(float $value) Longitude of the location
 * @method self horizontal_accuracy(float $value) <em>Optional</em>. The radius of uncertainty for the location, measured in meters; 0-1500
 */
class InputMediaLocation
{
    /** @var string */
    public string $type;
    /** @var float */
    public float $latitude;
    /** @var float */
    public float $longitude;
    /** @var float */
    public float $horizontal_accuracy;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a location to be sent.
     * @param string|null $type Type of the media, must be <em>location</em>
     * @param float|null $latitude Latitude of the location
     * @param float|null $longitude Longitude of the location
     * @param float|null $horizontal_accuracy <em>Optional</em>. The radius of uncertainty for the location, measured in meters; 0-1500
     */
    public static function make(string $type = null, float $latitude = null, float $longitude = null, float $horizontal_accuracy = null): self
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