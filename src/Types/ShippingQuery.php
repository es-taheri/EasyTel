<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about an incoming shipping query.
 * @method self id(string $value) Unique query identifier
 * @method self from(User $value) User who sent the query
 * @method self invoice_payload(string $value) Bot-specified invoice payload
 * @method self shipping_address(ShippingAddress $value) User specified shipping address
 */
class ShippingQuery
{
    /** @var string */
    public string $id;
    /** @var User */
    public User $from;
    /** @var string */
    public string $invoice_payload;
    /** @var ShippingAddress */
    public ShippingAddress $shipping_address;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about an incoming shipping query.
     * @param string|null $id Unique query identifier
     * @param User|null $from User who sent the query
     * @param string|null $invoice_payload Bot-specified invoice payload
     * @param ShippingAddress|null $shipping_address User specified shipping address
     */
    public static function make(string $id = null, User $from = null, string $invoice_payload = null, ShippingAddress $shipping_address = null): self
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