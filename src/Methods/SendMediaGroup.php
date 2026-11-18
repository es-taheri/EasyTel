<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\ReplyParameters;

/**
 * Use this method to send a group of photos, live photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an Array of <a href="https://core.telegram.org/bots/api#message">Message</a> objects that were sent is returned.
 *
 * @method SendMediaGroup business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message will be sent
 * @method SendMediaGroup message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendMediaGroup direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat
 * @method SendMediaGroup disable_notification(bool $value) Sends messages <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendMediaGroup protect_content(bool $value) Protects the contents of the sent messages from forwarding and saving
 * @method SendMediaGroup allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
 * @method SendMediaGroup message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendMediaGroup reply_parameters(ReplyParameters $value) Description of the message to reply to
 *
 * @link https://core.telegram.org/bots/api#sendmediagroup
 */
class SendMediaGroup
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection on behalf of which the message will be sent */
    private string $business_connection_id;
    /** @var int|string Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var int Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat */
    private int $direct_messages_topic_id;
    /** @var string A JSON-serialized Array describing messages to be sent, must include 2-10 items */
    private string $media;
    /** @var bool Sends messages <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the sent messages from forwarding and saving */
    private bool $protect_content;
    /** @var bool Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance. */
    private bool $allow_paid_broadcast;
    /** @var string Unique identifier of the message effect to be added to the message; for private chats only */
    private string $message_effect_id;
    /** @var ReplyParameters Description of the message to reply to */
    private ReplyParameters $reply_parameters;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>
     * @param string $media A JSON-serialized Array describing messages to be sent, must include 2-10 items
     */
    public function __construct(Request $request, int|string $chat_id, string $media)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->media = $media;
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
