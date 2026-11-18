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
    /** @var string */
    public string $country_code;
    /** @var string */
    public string $state;
    /** @var string */
    public string $city;
    /** @var string */
    public string $street;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
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