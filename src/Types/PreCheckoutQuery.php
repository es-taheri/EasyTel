<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about an incoming pre-checkout query.
 * @method self id(string $value) Unique query identifier
 * @method self from(User $value) User who sent the query
 * @method self currency(string $value) Three-letter ISO 4217 <a href="/bots/payments#supported-currencies">currency</a> code, or “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>
 * @method self total_amount(int $value) Total price in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a price of <code>US$ 1.45</code> pass <code>amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 * @method self invoice_payload(string $value) Bot-specified invoice payload
 * @method self shipping_option_id(string $value) <em>Optional</em>. Identifier of the shipping option chosen by the user
 * @method self order_info(OrderInfo $value) <em>Optional</em>. Order information provided by the user
 */
class PreCheckoutQuery
{
    /** @var string */
    public string $id;
    /** @var User */
    public User $from;
    /** @var string */
    public string $currency;
    /** @var int */
    public int $total_amount;
    /** @var string */
    public string $invoice_payload;
    /** @var string */
    public string $shipping_option_id;
    /** @var OrderInfo */
    public OrderInfo $order_info;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about an incoming pre-checkout query.
     * @param string|null $id Unique query identifier
     * @param User|null $from User who sent the query
     * @param string|null $currency Three-letter ISO 4217 <a href="/bots/payments#supported-currencies">currency</a> code, or “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>
     * @param int|null $total_amount Total price in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a price of <code>US$ 1.45</code> pass <code>amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     * @param string|null $invoice_payload Bot-specified invoice payload
     * @param string|null $shipping_option_id <em>Optional</em>. Identifier of the shipping option chosen by the user
     * @param OrderInfo|null $order_info <em>Optional</em>. Order information provided by the user
     */
    public static function make(string $id = null, User $from = null, string $currency = null, int $total_amount = null, string $invoice_payload = null, string $shipping_option_id = null, OrderInfo $order_info = null): self
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