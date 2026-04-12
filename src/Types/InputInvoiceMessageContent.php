<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of an invoice message to be sent as the result of an inline query.
 * @method self title(string $value) Product name, 1-32 characters
 * @method self description(string $value) Product description, 1-255 characters
 * @method self payload(string $value) Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use it for your internal processes.
 * @method self provider_token(string $value) <em>Optional</em>. Payment provider token, obtained via <a href="https://t.me/botfather">@BotFather</a>. Pass an empty string for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self currency(string $value) Three-letter ISO 4217 currency code, see <a href="/bots/payments#supported-currencies">more on currencies</a>. Pass “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self prices(array $value) Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self max_tip_amount(int $value) <em>Optional</em>. The maximum accepted amount for tips in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a maximum tip of <code>US$ 1.45</code> pass <code>max_tip_amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self suggested_tip_amounts(array $value) <em>Optional</em>. A JSON-serialized array of suggested amounts of tip in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed <em>max_tip_amount</em>.
 * @method self provider_data(string $value) <em>Optional</em>. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
 * @method self photo_url(string $value) <em>Optional</em>. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
 * @method self photo_size(int $value) <em>Optional</em>. Photo size in bytes
 * @method self photo_width(int $value) <em>Optional</em>. Photo width
 * @method self photo_height(int $value) <em>Optional</em>. Photo height
 * @method self need_name(bool $value) <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s full name to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self need_phone_number(bool $value) <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s phone number to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self need_email(bool $value) <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s email address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self need_shipping_address(bool $value) <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s shipping address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self send_phone_number_to_provider(bool $value) <em>Optional</em>. Pass <em>True</em> if the user&#39;s phone number should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self send_email_to_provider(bool $value) <em>Optional</em>. Pass <em>True</em> if the user&#39;s email address should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method self is_flexible(bool $value) <em>Optional</em>. Pass <em>True</em> if the final price depends on the shipping method. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 */
class InputInvoiceMessageContent
{
    public string $title;
    public string $description;
    public string $payload;
    public string $provider_token;
    public string $currency;
    public array $prices;
    public int $max_tip_amount;
    public array $suggested_tip_amounts;
    public string $provider_data;
    public string $photo_url;
    public int $photo_size;
    public int $photo_width;
    public int $photo_height;
    public bool $need_name;
    public bool $need_phone_number;
    public bool $need_email;
    public bool $need_shipping_address;
    public bool $send_phone_number_to_provider;
    public bool $send_email_to_provider;
    public bool $is_flexible;

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
     * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of an invoice message to be sent as the result of an inline query.
     * @param string|null $title Product name, 1-32 characters
     * @param string|null $description Product description, 1-255 characters
     * @param string|null $payload Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use it for your internal processes.
     * @param string|null $provider_token <em>Optional</em>. Payment provider token, obtained via <a href="https://t.me/botfather">@BotFather</a>. Pass an empty string for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param string|null $currency Three-letter ISO 4217 currency code, see <a href="/bots/payments#supported-currencies">more on currencies</a>. Pass “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param array|null $prices Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param int|null $max_tip_amount <em>Optional</em>. The maximum accepted amount for tips in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a maximum tip of <code>US$ 1.45</code> pass <code>max_tip_amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param array|null $suggested_tip_amounts <em>Optional</em>. A JSON-serialized array of suggested amounts of tip in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed <em>max_tip_amount</em>.
     * @param string|null $provider_data <em>Optional</em>. A JSON-serialized object for data about the invoice, which will be shared with the payment provider. A detailed description of the required fields should be provided by the payment provider.
     * @param string|null $photo_url <em>Optional</em>. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service.
     * @param int|null $photo_size <em>Optional</em>. Photo size in bytes
     * @param int|null $photo_width <em>Optional</em>. Photo width
     * @param int|null $photo_height <em>Optional</em>. Photo height
     * @param bool|null $need_name <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s full name to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param bool|null $need_phone_number <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s phone number to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param bool|null $need_email <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s email address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param bool|null $need_shipping_address <em>Optional</em>. Pass <em>True</em> if you require the user&#39;s shipping address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param bool|null $send_phone_number_to_provider <em>Optional</em>. Pass <em>True</em> if the user&#39;s phone number should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param bool|null $send_email_to_provider <em>Optional</em>. Pass <em>True</em> if the user&#39;s email address should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param bool|null $is_flexible <em>Optional</em>. Pass <em>True</em> if the final price depends on the shipping method. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     */
    public static function make(string $title = null, string $description = null, string $payload = null, string $provider_token = null, string $currency = null, array $prices = null, int $max_tip_amount = null, array $suggested_tip_amounts = null, string $provider_data = null, string $photo_url = null, int $photo_size = null, int $photo_width = null, int $photo_height = null, bool $need_name = null, bool $need_phone_number = null, bool $need_email = null, bool $need_shipping_address = null, bool $send_phone_number_to_provider = null, bool $send_email_to_provider = null, bool $is_flexible = null): self
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