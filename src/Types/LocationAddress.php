<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the physical address of a location.
 * @method self country_code(string $value) The two-letter ISO 3166-1 alpha-2 country code of the country where the location is located
 * @method self state(string $value) <em>Optional</em>. State of the location
 * @method self city(string $value) <em>Optional</em>. City of the location
 * @method self street(string $value) <em>Optional</em>. Street address of the location
 */
class LocationAddress
{
    public string $country_code;
    public string $state;
    public string $city;
    public string $street;

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
     * Describes the physical address of a location.
     * @param string|null $country_code The two-letter ISO 3166-1 alpha-2 country code of the country where the location is located
     * @param string|null $state <em>Optional</em>. State of the location
     * @param string|null $city <em>Optional</em>. City of the location
     * @param string|null $street <em>Optional</em>. Street address of the location
     */
    public static function make(string $country_code = null, string $state = null, string $city = null, string $street = null): self
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