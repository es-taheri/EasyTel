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
    public string $type;
    public float $latitude;
    public float $longitude;
    public LocationAddress $address;

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
        if (isset($update['address'])) $this->address = new LocationAddress($update['address']);
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