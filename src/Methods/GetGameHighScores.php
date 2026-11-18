<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. Returns an Array of <a href="https://core.telegram.org/bots/api#gamehighscore">GameHighScore</a> objects.
 *
 * <p>This method will currently return scores for the target user, plus two of their closest neighbors on each side. Will also return the top three users if the user and their neighbors are not among them. Please note that this behavior is subject to change.</p>
 *
 * @method GetGameHighScores chat_id(int $value) Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat.
 * @method GetGameHighScores message_id(int $value) Required if <em>inline_message_id</em> is not specified. Identifier of the sent message.
 * @method GetGameHighScores inline_message_id(string $value) Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message.
 *
 * @link https://core.telegram.org/bots/api#getgamehighscores
 */
class GetGameHighScores
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Target user id */
    private int $user_id;
    /** @var int Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat. */
    private int $chat_id;
    /** @var int Required if <em>inline_message_id</em> is not specified. Identifier of the sent message. */
    private int $message_id;
    /** @var string Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message. */
    private string $inline_message_id;

    /**
     * @param Request $request
     * @param int $user_id Target user id
     */
    public function __construct(Request $request, int $user_id)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
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
