<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method CreateInvoiceLink business_connection_id(string $value) Unique identifier of the business connection on behalf of which the link will be created. For payments in <a href="https://t.me/BotNews/90">Telegram Stars</a> only.
 * @method CreateInvoiceLink provider_token(string $value) Payment provider token, obtained via <a href="https://t.me/botfather">@BotFather</a>. Pass an empty string for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink subscription_period(int $value) The number of seconds the subscription will be active for before the next payment. The currency must be set to “XTR” (Telegram Stars) if the parameter is used. Currently, it must always be 2592000 (30 days) if specified. Any number of subscriptions can be active for a given bot at the same time, including multiple concurrent subscriptions from the same user. Subscription price must no exceed 10000 Telegram Stars.
 * @method CreateInvoiceLink max_tip_amount(int $value) The maximum accepted amount for tips in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a maximum tip of <code>US$ 1.45</code> pass <code>max_tip_amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink suggested_tip_amounts(string  $value) A JSON-serialized array of suggested amounts of tips in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed <em>max_tip_amount</em>.
 * @method CreateInvoiceLink provider_data(string $value) JSON-serialized data about the invoice, which will be shared with the payment provider. A detailed description of required fields should be provided by the payment provider.
 * @method CreateInvoiceLink photo_url(string $value) URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
 * @method CreateInvoiceLink photo_size(int $value) Photo size in bytes
 * @method CreateInvoiceLink photo_width(int $value) Photo width
 * @method CreateInvoiceLink photo_height(int $value) Photo height
 * @method CreateInvoiceLink need_name(bool $value) Pass <em>True</em> if you require the user&#39;s full name to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink need_phone_number(bool $value) Pass <em>True</em> if you require the user&#39;s phone number to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink need_email(bool $value) Pass <em>True</em> if you require the user&#39;s email address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink need_shipping_address(bool $value) Pass <em>True</em> if you require the user&#39;s shipping address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink send_phone_number_to_provider(bool $value) Pass <em>True</em> if the user&#39;s phone number should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink send_email_to_provider(bool $value) Pass <em>True</em> if the user&#39;s email address should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method CreateInvoiceLink is_flexible(bool $value) Pass <em>True</em> if the final price depends on the shipping method. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 */
class CreateInvoiceLink
{
    private Request $_request;
    private bool $_sent = false;
    private string $title;
    private string $description;
    private string $payload;
    private string $currency;
    private string  $prices;
    private string $business_connection_id;
    private string $provider_token;
    private int $subscription_period;
    private int $max_tip_amount;
    private string  $suggested_tip_amounts;
    private string $provider_data;
    private string $photo_url;
    private int $photo_size;
    private int $photo_width;
    private int $photo_height;
    private bool $need_name;
    private bool $need_phone_number;
    private bool $need_email;
    private bool $need_shipping_address;
    private bool $send_phone_number_to_provider;
    private bool $send_email_to_provider;
    private bool $is_flexible;

    public function __construct(Request $request, string $title, string $description, string $payload, string $currency, string  $prices)
    {
        $this->_request = $request;
        $this->title = $title;
        $this->description = $description;
        $this->payload = $payload;
        $this->currency = $currency;
        $this->prices = $prices;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];
        foreach ($this as $key => $value):
            if (isset($this->{$key}) && !in_array($key, ['_request', '_result'])):
                if (gettype($value) == 'object')
                    $parameters[$key] = (fn() => ($this->_output()))->bindTo($value, $value)();
                else
                    $parameters[$key] = $value;
            endif;
        endforeach;
        $r = new \ReflectionClass($this);
        $this->_sent = true;
        return $this->_request->send(lcfirst($r->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if (!$this->_sent) $this->_result();
    }
}