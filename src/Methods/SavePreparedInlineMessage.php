<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineQueryResult;

/**
 * Stores a message that can be sent by a user of a Mini App. Returns a <a href="https://core.telegram.org/bots/api#preparedinlinemessage">PreparedInlineMessage</a> object.
 *
 * @method SavePreparedInlineMessage allow_user_chats(bool $value) Pass <em>True</em> if the message can be sent to private chats with users
 * @method SavePreparedInlineMessage allow_bot_chats(bool $value) Pass <em>True</em> if the message can be sent to private chats with bots
 * @method SavePreparedInlineMessage allow_group_chats(bool $value) Pass <em>True</em> if the message can be sent to group and supergroup chats
 * @method SavePreparedInlineMessage allow_channel_chats(bool $value) Pass <em>True</em> if the message can be sent to channel chats
 *
 * @link https://core.telegram.org/bots/api#savepreparedinlinemessage
 */
class SavePreparedInlineMessage
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the target user that can use the prepared message */
    private int $user_id;
    /** @var InlineQueryResult A JSON-serialized object describing the message to be sent */
    private InlineQueryResult $result;
    /** @var bool Pass <em>True</em> if the message can be sent to private chats with users */
    private bool $allow_user_chats;
    /** @var bool Pass <em>True</em> if the message can be sent to private chats with bots */
    private bool $allow_bot_chats;
    /** @var bool Pass <em>True</em> if the message can be sent to group and supergroup chats */
    private bool $allow_group_chats;
    /** @var bool Pass <em>True</em> if the message can be sent to channel chats */
    private bool $allow_channel_chats;

    /**
     * @param Request $request
     * @param int $user_id Unique identifier of the target user that can use the prepared message
     * @param InlineQueryResult $result A JSON-serialized object describing the message to be sent
     */
    public function __construct(Request $request, int $user_id, InlineQueryResult $result)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->result = $result;
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
