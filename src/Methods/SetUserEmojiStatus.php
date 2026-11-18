<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Changes the emoji status for a given user that previously allowed the bot to manage their emoji status via the Mini App method <a href="https://core.telegram.org/bots/webapps#initializing-mini-apps">requestEmojiStatusAccess</a>. Returns <em>True</em> on success.
 *
 * @method SetUserEmojiStatus emoji_status_custom_emoji_id(string $value) Custom emoji identifier of the emoji status to set. Pass an empty string to remove the status.
 * @method SetUserEmojiStatus emoji_status_expiration_date(int $value) Expiration date of the emoji status, if any
 *
 * @link https://core.telegram.org/bots/api#setuseremojistatus
 */
class SetUserEmojiStatus
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the target user */
    private int $user_id;
    /** @var string Custom emoji identifier of the emoji status to set. Pass an empty string to remove the status. */
    private string $emoji_status_custom_emoji_id;
    /** @var int Expiration date of the emoji status, if any */
    private int $emoji_status_expiration_date;

    /**
     * @param Request $request
     * @param int $user_id Unique identifier of the target user
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
