<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\SuggestedPostParameters;

/**
 * Use this method to forward messages of any kind. Service messages and messages with protected content can't be forwarded. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
 *
 * @method ForwardMessage message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method ForwardMessage direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the message will be forwarded; required if the message is forwarded to a direct messages chat
 * @method ForwardMessage video_start_timestamp(int $value) New start timestamp for the forwarded video in the message
 * @method ForwardMessage disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method ForwardMessage protect_content(bool $value) Protects the contents of the forwarded message from forwarding and saving
 * @method ForwardMessage message_effect_id(string $value) Unique identifier of the message effect to be added to the message; only available when forwarding to private chats
 * @method ForwardMessage suggested_post_parameters(SuggestedPostParameters $value) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only
 *
 * @link https://core.telegram.org/bots/api#forwardmessage
 */
class ForwardMessage
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var int Identifier of the direct messages topic to which the message will be forwarded; required if the message is forwarded to a direct messages chat */
    private int $direct_messages_topic_id;
    /** @var int|string Unique identifier for the chat where the original message was sent (or username of the target bot, supergroup, or channel in the format <code>@username</code>) */
    private int|string $from_chat_id;
    /** @var int New start timestamp for the forwarded video in the message */
    private int $video_start_timestamp;
    /** @var bool Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the forwarded message from forwarding and saving */
    private bool $protect_content;
    /** @var string Unique identifier of the message effect to be added to the message; only available when forwarding to private chats */
    private string $message_effect_id;
    /** @var SuggestedPostParameters A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only */
    private SuggestedPostParameters $suggested_post_parameters;
    /** @var int Message identifier in the chat specified in <em>from_chat_id</em> */
    private int $message_id;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>
     * @param int|string $from_chat_id Unique identifier for the chat where the original message was sent (or username of the target bot, supergroup, or channel in the format <code>@username</code>)
     * @param int $message_id Message identifier in the chat specified in <em>from_chat_id</em>
     */
    public function __construct(Request $request, int|string $chat_id, int|string $from_chat_id, int $message_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->from_chat_id = $from_chat_id;
        $this->message_id = $message_id;
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
