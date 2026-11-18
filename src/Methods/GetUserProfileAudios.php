<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to get a list of profile audios for a user. Returns a <a href="https://core.telegram.org/bots/api#userprofileaudios">UserProfileAudios</a> object.
 *
 * @method GetUserProfileAudios offset(int $value) Sequential number of the first audio to be returned. By default, all audios are returned.
 * @method GetUserProfileAudios limit(int $value) Limits the number of audios to be retrieved. Values between 1-100 are accepted. Defaults to 100.
 *
 * @link https://core.telegram.org/bots/api#getuserprofileaudios
 */
class GetUserProfileAudios
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the target user */
    private int $user_id;
    /** @var int Sequential number of the first audio to be returned. By default, all audios are returned. */
    private int $offset;
    /** @var int Limits the number of audios to be retrieved. Values between 1-100 are accepted. Defaults to 100. */
    private int $limit;

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
