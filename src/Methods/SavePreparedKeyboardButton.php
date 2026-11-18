<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\KeyboardButton;

/**
 * Stores a keyboard button that can be used by a user within a Mini App. Returns a <a href="https://core.telegram.org/bots/api#preparedkeyboardbutton">PreparedKeyboardButton</a> object.
 *
 * @link https://core.telegram.org/bots/api#savepreparedkeyboardbutton
 */
class SavePreparedKeyboardButton
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the target user that can use the button */
    private int $user_id;
    /** @var KeyboardButton A JSON-serialized object describing the button to be saved. The button must be of the type <em>request_users</em>, <em>request_chat</em>, or <em>request_managed_bot</em>. */
    private KeyboardButton $button;

    /**
     * @param Request $request
     * @param int $user_id Unique identifier of the target user that can use the button
     * @param KeyboardButton $button A JSON-serialized object describing the button to be saved. The button must be of the type <em>request_users</em>, <em>request_chat</em>, or <em>request_managed_bot</em>.
     */
    public function __construct(Request $request, int $user_id, KeyboardButton $button)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->button = $button;
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
