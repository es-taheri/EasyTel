<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\ForceReply;
use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardRemove;
use EasyTel\Types\ReplyParameters;

/**
 * Use this method to send a message with an <a href="https://core.telegram.org/bots/widgets">HTML widget</a>. Currently, in the production environment, widgets can be sent only to private chats, and sent widgets automatically have protected content and therefore can't be forwarded. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
 *
 * @method SendWidget message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendWidget width(int $value) Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble.
 * @method SendWidget height(int $value) Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600.
 * @method SendWidget public_data(string $value) Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data.
 * @method SendWidget chat_data(string $value) Data of the widget, which is specific to the message, 0-1024 bytes
 * @method SendWidget caption(string $value) Widget caption, 0-1024 characters after entities parsing
 * @method SendWidget parse_mode(string $value) Mode for parsing entities in the widget caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendWidget caption_entities(string $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method SendWidget show_caption_above_media(bool $value) Pass <em>True</em> if the caption must be shown above the widget
 * @method SendWidget allow_queries(bool $value) Pass <em>True</em> to allow the widget to send queries to the bot
 * @method SendWidget is_shared(bool $value) Pass <em>True</em> to create a shared widget, which can be sent again using the method <a href="https://core.telegram.org/bots/api#sendsharedwidget">sendSharedWidget</a>
 * @method SendWidget disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendWidget protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendWidget allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
 * @method SendWidget message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendWidget reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendWidget reply_markup(InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $value) Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>, <a href="https://core.telegram.org/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
 *
 * @link https://core.telegram.org/bots/api#sendwidget
 */
class SendWidget
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var string HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level. */
    private string $html;
    /** @var int Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble. */
    private int $width;
    /** @var int Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600. */
    private int $height;
    /** @var string Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data. */
    private string $public_data;
    /** @var string Data of the widget, which is specific to the message, 0-1024 bytes */
    private string $chat_data;
    /** @var string Widget caption, 0-1024 characters after entities parsing */
    private string $caption;
    /** @var string Mode for parsing entities in the widget caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em> */
    private string $caption_entities;
    /** @var bool Pass <em>True</em> if the caption must be shown above the widget */
    private bool $show_caption_above_media;
    /** @var bool Pass <em>True</em> to allow the widget to send queries to the bot */
    private bool $allow_queries;
    /** @var bool Pass <em>True</em> to create a shared widget, which can be sent again using the method <a href="https://core.telegram.org/bots/api#sendsharedwidget">sendSharedWidget</a> */
    private bool $is_shared;
    /** @var bool Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the sent message from forwarding and saving */
    private bool $protect_content;
    /** @var bool Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance. */
    private bool $allow_paid_broadcast;
    /** @var string Unique identifier of the message effect to be added to the message; for private chats only */
    private string $message_effect_id;
    /** @var ReplyParameters Description of the message to reply to */
    private ReplyParameters $reply_parameters;
    /** @var InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>, <a href="https://core.telegram.org/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user. */
    private InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $reply_markup;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code>
     * @param string $html HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level.
     */
    public function __construct(Request $request, int|string $chat_id, string $html)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->html = $html;
    }

    public function __call(string $name, array $arguments)
    {
        if (!property_exists($this, $name)) {
            $this->_sent = true; // never send a half built request from __destruct()
            throw new \BadMethodCallException(
                sprintf('Unknown parameter "%s" for %s.', $name, static::class)
            );
        }

        if ($arguments === []) {
            $this->_sent = true;
            throw new \InvalidArgumentException(
                sprintf('Parameter "%s" of %s requires a value.', $name, static::class)
            );
        }

        try {
            $this->{$name} = array_shift($arguments);
        } catch (\Throwable $e) {
            $this->_sent = true; // the value did not fit: the request is invalid
            throw $e;
        }

        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];

        foreach ($this as $key => $value) {
            if (str_starts_with($key, '_') || !isset($this->{$key})) {
                continue;
            }

            if (is_object($value)) {
                $parameters[$key] = method_exists($value, '_output')
                    ? (fn() => $this->_output())->bindTo($value, $value)()
                    : $value;
            } else {
                $parameters[$key] = $value;
            }
        }

        $reflection = new \ReflectionClass($this);
        $this->_sent = true;

        return $this->_request->send(lcfirst($reflection->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if ($this->_sent)
            return;

        // Fire-and-forget call from the destructor must never raise inside
        // shutdown; the explicit _result() call is the error-reporting path.
        try {
            $this->_result();
        } catch (\Throwable) {
            // Ignored on purpose.
        }
    }
}
