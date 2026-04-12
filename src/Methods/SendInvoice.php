<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\SuggestedPostParameters;
use EasyTel\Types\ReplyParameters;
use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method SendInvoice message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendInvoice direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
 * @method SendInvoice provider_token(string $value) Payment provider token, obtained via <a href="https://t.me/botfather">@BotFather</a>. Pass an empty string for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice max_tip_amount(int $value) The maximum accepted amount for tips in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). For example, for a maximum tip of <code>US$ 1.45</code> pass <code>max_tip_amount = 145</code>. See the <em>exp</em> parameter in <a href="/bots/payments/currencies.json">currencies.json</a>, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies). Defaults to 0. Not supported for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice suggested_tip_amounts(string  $value) A JSON-serialized array of suggested amounts of tips in the <em>smallest units</em> of the currency (integer, <strong>not</strong> float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be positive, passed in a strictly increased order and must not exceed <em>max_tip_amount</em>.
 * @method SendInvoice start_parameter(string $value) Unique deep-linking parameter. If left empty, <strong>forwarded copies</strong> of the sent message will have a <em>Pay</em> button, allowing multiple users to pay directly from the forwarded message, using the same invoice. If non-empty, forwarded copies of the sent message will have a <em>URL</em> button with a deep link to the bot (instead of a <em>Pay</em> button), with the value used as the start parameter
 * @method SendInvoice provider_data(string $value) JSON-serialized data about the invoice, which will be shared with the payment provider. A detailed description of required fields should be provided by the payment provider.
 * @method SendInvoice photo_url(string $value) URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a service. People like it better when they see what they are paying for.
 * @method SendInvoice photo_size(int $value) Photo size in bytes
 * @method SendInvoice photo_width(int $value) Photo width
 * @method SendInvoice photo_height(int $value) Photo height
 * @method SendInvoice need_name(bool $value) Pass <em>True</em> if you require the user&#39;s full name to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice need_phone_number(bool $value) Pass <em>True</em> if you require the user&#39;s phone number to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice need_email(bool $value) Pass <em>True</em> if you require the user&#39;s email address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice need_shipping_address(bool $value) Pass <em>True</em> if you require the user&#39;s shipping address to complete the order. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice send_phone_number_to_provider(bool $value) Pass <em>True</em> if the user&#39;s phone number should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice send_email_to_provider(bool $value) Pass <em>True</em> if the user&#39;s email address should be sent to the provider. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice is_flexible(bool $value) Pass <em>True</em> if the final price depends on the shipping method. Ignored for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
 * @method SendInvoice disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendInvoice protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendInvoice allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot&#39;s balance
 * @method SendInvoice message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendInvoice suggested_post_parameters(SuggestedPostParameters $value) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
 * @method SendInvoice reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendInvoice reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>. If empty, one &#39;Pay <code>total price</code>&#39; button will be shown. If not empty, the first button must be a Pay button.
 */
class SendInvoice
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int|string $chat_id;
    private string $title;
    private string $description;
    private string $payload;
    private string $currency;
    private string  $prices;
    private int $message_thread_id;
    private int $direct_messages_topic_id;
    private string $provider_token;
    private int $max_tip_amount;
    private string  $suggested_tip_amounts;
    private string $start_parameter;
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
    private bool $disable_notification;
    private bool $protect_content;
    private bool $allow_paid_broadcast;
    private string $message_effect_id;
    private SuggestedPostParameters $suggested_post_parameters;
    private ReplyParameters $reply_parameters;
    private InlineKeyboardMarkup $reply_markup;

    public function __construct(Request $request, int|string $chat_id, string $title, string $description, string $payload, string $currency, string  $prices)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->title = $title;
        $this->description = $description;
        $this->payload = $payload;
        $this->currency = $currency;
        $this->prices = $prices;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        $this->_returned = true;
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
        if (!$this->_returned && !$this->_sent) $this->_result();
    }
}