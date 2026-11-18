<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Returns an error, if the new score is not greater than the user's current score in the chat and <em>force</em> is <em>False</em>.
 *
 * @method SetGameScore force(bool $value) Pass <em>True</em> if the high score is allowed to decrease. This can be useful when fixing mistakes or banning cheaters.
 * @method SetGameScore disable_edit_message(bool $value) Pass <em>True</em> if the game message should not be automatically edited to include the current scoreboard
 * @method SetGameScore chat_id(int $value) Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat.
 * @method SetGameScore message_id(int $value) Required if <em>inline_message_id</em> is not specified. Identifier of the sent message.
 * @method SetGameScore inline_message_id(string $value) Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message.
 *
 * @link https://core.telegram.org/bots/api#setgamescore
 */
class SetGameScore
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int User identifier */
    private int $user_id;
    /** @var int New score, must be non-negative */
    private int $score;
    /** @var bool Pass <em>True</em> if the high score is allowed to decrease. This can be useful when fixing mistakes or banning cheaters. */
    private bool $force;
    /** @var bool Pass <em>True</em> if the game message should not be automatically edited to include the current scoreboard */
    private bool $disable_edit_message;
    /** @var int Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat. */
    private int $chat_id;
    /** @var int Required if <em>inline_message_id</em> is not specified. Identifier of the sent message. */
    private int $message_id;
    /** @var string Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message. */
    private string $inline_message_id;

    /**
     * @param Request $request
     * @param int $user_id User identifier
     * @param int $score New score, must be non-negative
     */
    public function __construct(Request $request, int $user_id, int $score)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->score = $score;
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
