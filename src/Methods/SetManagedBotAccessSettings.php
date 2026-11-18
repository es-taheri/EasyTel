<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to change the access settings of a managed bot. Returns <em>True</em> on success.
 *
 * @method SetManagedBotAccessSettings added_user_ids(string $value) A JSON-serialized list of up to 10 identifiers of users who will have access to the bot in addition to its owner. Ignored if <em>is_access_restricted</em> is <em>False</em>.
 *
 * @link https://core.telegram.org/bots/api#setmanagedbotaccesssettings
 */
class SetManagedBotAccessSettings
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int User identifier of the managed bot whose access settings will be changed */
    private int $user_id;
    /** @var bool Pass <em>True</em> if only selected users can access the bot. The bot's owner can always access it. */
    private bool $is_access_restricted;
    /** @var string A JSON-serialized list of up to 10 identifiers of users who will have access to the bot in addition to its owner. Ignored if <em>is_access_restricted</em> is <em>False</em>. */
    private string $added_user_ids;

    /**
     * @param Request $request
     * @param int $user_id User identifier of the managed bot whose access settings will be changed
     * @param bool $is_access_restricted Pass <em>True</em> if only selected users can access the bot. The bot's owner can always access it.
     */
    public function __construct(Request $request, int $user_id, bool $is_access_restricted)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->is_access_restricted = $is_access_restricted;
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
