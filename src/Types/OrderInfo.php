<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents information about an order.
 * @method self name(string $value) <em>Optional</em>. User name
 * @method self phone_number(string $value) <em>Optional</em>. User&#39;s phone number
 * @method self email(string $value) <em>Optional</em>. User email
 * @method self shipping_address(ShippingAddress $value) <em>Optional</em>. User shipping address
 */
class OrderInfo
{
    public string $name;
    public string $phone_number;
    public string $email;
    public ShippingAddress $shipping_address;

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
        if (isset($update['shipping_address'])) $this->shipping_address = new ShippingAddress($update['shipping_address']);
    }

    /**
     * This object represents information about an order.
     * @param string|null $name <em>Optional</em>. User name
     * @param string|null $phone_number <em>Optional</em>. User&#39;s phone number
     * @param string|null $email <em>Optional</em>. User email
     * @param ShippingAddress|null $shipping_address <em>Optional</em>. User shipping address
     */
    public static function make(string $name = null, string $phone_number = null, string $email = null, ShippingAddress $shipping_address = null): self
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