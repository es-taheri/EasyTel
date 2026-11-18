<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;

/**
 * Use this method to edit only the reply markup of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, <em>True</em> is returned.
 *
 * @method EditEphemeralMessageReplyMarkup reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>
 *
 * @link https://core.telegram.org/bots/api#editephemeralmessagereplymarkup
 */
class EditEphemeralMessageReplyMarkup
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Identifier of the user who received the message */
    private int $receiver_user_id;
    /** @var int Identifier of the ephemeral message to edit */
    private int $ephemeral_message_id;
    /** @var InlineKeyboardMarkup A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a> */
    private InlineKeyboardMarkup $reply_markup;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code>
     * @param int $receiver_user_id Identifier of the user who received the message
     * @param int $ephemeral_message_id Identifier of the ephemeral message to edit
     */
    public function __construct(Request $request, int|string $chat_id, int $receiver_user_id, int $ephemeral_message_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->receiver_user_id = $receiver_user_id;
        $this->ephemeral_message_id = $ephemeral_message_id;
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
