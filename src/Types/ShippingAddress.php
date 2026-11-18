<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a shipping address.
 * @method self country_code(string $value) Two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country code
 * @method self state(string $value) State, if applicable
 * @method self city(string $value) City
 * @method self street_line1(string $value) First line for the address
 * @method self street_line2(string $value) Second line for the address
 * @method self post_code(string $value) Address post code
 */
class ShippingAddress
{
    /** @var string */
    public string $country_code;
    /** @var string */
    public string $state;
    /** @var string */
    public string $city;
    /** @var string */
    public string $street_line1;
    /** @var string */
    public string $street_line2;
    /** @var string */
    public string $post_code;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a shipping address.
     * @param string|null $country_code Two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country code
     * @param string|null $state State, if applicable
     * @param string|null $city City
     * @param string|null $street_line1 First line for the address
     * @param string|null $street_line2 Second line for the address
     * @param string|null $post_code Address post code
     */
    public static function make(string $country_code = null, string $state = null, string $city = null, string $street_line1 = null, string $street_line2 = null, string $post_code = null): self
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