<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains basic information about a successful payment. Note that if the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram&#39;s control.
 * @method self currency(string $value) Three-letter ISO 4217 <a href="/bots/payments#supported-currencies">currency</a> code, or “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>
 * @method self total_amount(int $value) Total price in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a price of <code>US$ 1.45</code> pass <code>amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 * @method self invoice_payload(string $value) Bot-specified invoice payload
 * @method self subscription_expiration_date(int $value) <em>Optional</em>. Expiration date of the subscription, in Unix time; for recurring payments only
 * @method self is_recurring(True $value) <em>Optional</em>. <em>True</em>, if the payment is a recurring payment for a subscription
 * @method self is_first_recurring(True $value) <em>Optional</em>. <em>True</em>, if the payment is the first payment for a subscription
 * @method self shipping_option_id(string $value) <em>Optional</em>. Identifier of the shipping option chosen by the user
 * @method self order_info(OrderInfo $value) <em>Optional</em>. Order information provided by the user
 * @method self telegram_payment_charge_id(string $value) Telegram payment identifier
 * @method self provider_payment_charge_id(string $value) Provider payment identifier
 */
class SuccessfulPayment
{
    public string $currency;
    public int $total_amount;
    public string $invoice_payload;
    public int $subscription_expiration_date;
    public True $is_recurring;
    public True $is_first_recurring;
    public string $shipping_option_id;
    public OrderInfo $order_info;
    public string $telegram_payment_charge_id;
    public string $provider_payment_charge_id;

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
        if (isset($update['order_info'])) $this->order_info = new OrderInfo($update['order_info']);
    }

    /**
     * This object contains basic information about a successful payment. Note that if the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram&#39;s control.
     * @param string|null $currency Three-letter ISO 4217 <a href="/bots/payments#supported-currencies">currency</a> code, or “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>
     * @param int|null $total_amount Total price in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a price of <code>US$ 1.45</code> pass <code>amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
     * @param string|null $invoice_payload Bot-specified invoice payload
     * @param int|null $subscription_expiration_date <em>Optional</em>. Expiration date of the subscription, in Unix time; for recurring payments only
     * @param True|null $is_recurring <em>Optional</em>. <em>True</em>, if the payment is a recurring payment for a subscription
     * @param True|null $is_first_recurring <em>Optional</em>. <em>True</em>, if the payment is the first payment for a subscription
     * @param string|null $shipping_option_id <em>Optional</em>. Identifier of the shipping option chosen by the user
     * @param OrderInfo|null $order_info <em>Optional</em>. Order information provided by the user
     * @param string|null $telegram_payment_charge_id Telegram payment identifier
     * @param string|null $provider_payment_charge_id Provider payment identifier
     */
    public static function make(string $currency = null, int $total_amount = null, string $invoice_payload = null, int $subscription_expiration_date = null, True $is_recurring = null, True $is_first_recurring = null, string $shipping_option_id = null, OrderInfo $order_info = null, string $telegram_payment_charge_id = null, string $provider_payment_charge_id = null): self
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