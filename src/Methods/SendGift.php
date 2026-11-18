<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Sends a gift to the given user or channel chat. The gift can't be converted to Telegram Stars by the receiver. Returns <em>True</em> on success.
 *
 * @method SendGift user_id(int $value) Required if <em>chat_id</em> is not specified. Unique identifier of the target user who will receive the gift.
 * @method SendGift chat_id(int|string $value) Required if <em>user_id</em> is not specified. Unique identifier for the chat that will receive the gift or username of the channel in the format <code>@username</code>.
 * @method SendGift pay_for_upgrade(bool $value) Pass <em>True</em> to pay for the gift upgrade from the bot's balance, thereby making the upgrade free for the receiver
 * @method SendGift text(string $value) Text that will be shown along with the gift; 0-128 characters
 * @method SendGift text_parse_mode(string $value) Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 * @method SendGift text_entities(string $value) A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of <em>text_parse_mode</em>. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 *
 * @link https://core.telegram.org/bots/api#sendgift
 */
class SendGift
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Required if <em>chat_id</em> is not specified. Unique identifier of the target user who will receive the gift. */
    private int $user_id;
    /** @var int|string Required if <em>user_id</em> is not specified. Unique identifier for the chat that will receive the gift or username of the channel in the format <code>@username</code>. */
    private int|string $chat_id;
    /** @var string Identifier of the gift; limited gifts can't be sent to channel chats */
    private string $gift_id;
    /** @var bool Pass <em>True</em> to pay for the gift upgrade from the bot's balance, thereby making the upgrade free for the receiver */
    private bool $pay_for_upgrade;
    /** @var string Text that will be shown along with the gift; 0-128 characters */
    private string $text;
    /** @var string Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored. */
    private string $text_parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of <em>text_parse_mode</em>. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored. */
    private string $text_entities;

    /**
     * @param Request $request
     * @param string $gift_id Identifier of the gift; limited gifts can't be sent to channel chats
     */
    public function __construct(Request $request, string $gift_id)
    {
        $this->_request = $request;
        $this->gift_id = $gift_id;
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
