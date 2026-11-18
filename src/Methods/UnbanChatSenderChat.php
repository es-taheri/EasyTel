<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
 *
 * @link https://core.telegram.org/bots/api#unbanchatsenderchat
 */
class UnbanChatSenderChat
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Unique identifier of the target sender chat */
    private int $sender_chat_id;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format <code>@username</code>
     * @param int $sender_chat_id Unique identifier of the target sender chat
     */
    public function __construct(Request $request, int|string $chat_id, int $sender_chat_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->sender_chat_id = $sender_chat_id;
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
