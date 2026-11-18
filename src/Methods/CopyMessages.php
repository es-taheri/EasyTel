<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to copy messages of any kind. If some of the specified messages can't be found or copied, they are skipped. Service messages, paid media messages, giveaway messages, giveaway winners messages, invoice messages, and widget messages can't be copied. A quiz <a href="https://core.telegram.org/bots/api#poll">poll</a> can be copied only if the value of the field <em>correct_option_ids</em> is known to the bot. The method is analogous to the method <a href="https://core.telegram.org/bots/api#forwardmessages">forwardMessages</a>, but the copied messages don't have a link to the original message. Album grouping is kept for copied messages. On success, an Array of <a href="https://core.telegram.org/bots/api#messageid">MessageId</a> of the sent messages is returned.
 *
 * @method CopyMessages message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method CopyMessages direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat
 * @method CopyMessages disable_notification(bool $value) Sends the messages <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method CopyMessages protect_content(bool $value) Protects the contents of the sent messages from forwarding and saving
 * @method CopyMessages remove_caption(bool $value) Pass <em>True</em> to copy the messages without their captions
 *
 * @link https://core.telegram.org/bots/api#copymessages
 */
class CopyMessages
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var int Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat */
    private int $direct_messages_topic_id;
    /** @var int|string Unique identifier for the chat where the original messages were sent (or username of the target bot, supergroup, or channel in the format <code>@username</code>) */
    private int|string $from_chat_id;
    /** @var string A JSON-serialized list of 1-100 identifiers of messages in the chat <em>from_chat_id</em> to copy. The identifiers must be specified in a strictly increasing order. */
    private string $message_ids;
    /** @var bool Sends the messages <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the sent messages from forwarding and saving */
    private bool $protect_content;
    /** @var bool Pass <em>True</em> to copy the messages without their captions */
    private bool $remove_caption;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>
     * @param int|string $from_chat_id Unique identifier for the chat where the original messages were sent (or username of the target bot, supergroup, or channel in the format <code>@username</code>)
     * @param string $message_ids A JSON-serialized list of 1-100 identifiers of messages in the chat <em>from_chat_id</em> to copy. The identifiers must be specified in a strictly increasing order.
     */
    public function __construct(Request $request, int|string $chat_id, int|string $from_chat_id, string $message_ids)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->from_chat_id = $from_chat_id;
        $this->message_ids = $message_ids;
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
