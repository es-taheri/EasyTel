<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Verifies a user <a href="https://telegram.org/verify#third-party-verification">on behalf of the organization</a> which is represented by the bot. Returns <em>True</em> on success.
 *
 * @method VerifyUser custom_description(string $value) Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
 * @method VerifyUser custom_description_parse_mode(string $value) Mode for parsing entities in the custom description. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Only “text_link” entities are supported.
 * @method VerifyUser custom_description_entities(string $value) A JSON-serialized list of special entities that appear in the custom description, which can be specified instead of <em>custom_description_parse_mode</em>. Only “text_link” entities are supported.
 *
 * @link https://core.telegram.org/bots/api#verifyuser
 */
class VerifyUser
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the target user */
    private int $user_id;
    /** @var string Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description. */
    private string $custom_description;
    /** @var string Mode for parsing entities in the custom description. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Only “text_link” entities are supported. */
    private string $custom_description_parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the custom description, which can be specified instead of <em>custom_description_parse_mode</em>. Only “text_link” entities are supported. */
    private string $custom_description_entities;

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
