<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents information about an order.
 * @method self name(string $value) <em>Optional</em>. User name
 * @method self phone_number(string $value) <em>Optional</em>. User's phone number
 * @method self email(string $value) <em>Optional</em>. User email
 * @method self shipping_address(ShippingAddress $value) <em>Optional</em>. User shipping address
 */
class OrderInfo
{
    /** @var string */
    public string $name;
    /** @var string */
    public string $phone_number;
    /** @var string */
    public string $email;
    /** @var ShippingAddress */
    public ShippingAddress $shipping_address;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents information about an order.
     * @param string|null $name <em>Optional</em>. User name
     * @param string|null $phone_number <em>Optional</em>. User's phone number
     * @param string|null $email <em>Optional</em>. User email
     * @param ShippingAddress|null $shipping_address <em>Optional</em>. User shipping address
     */
    public static function make(string $name = null, string $phone_number = null, string $email = null, ShippingAddress $shipping_address = null): self
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