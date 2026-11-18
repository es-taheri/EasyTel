<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Allows the bot to cancel or re-enable extension of a subscription paid in Telegram Stars. Returns <em>True</em> on success.
 *
 * @link https://core.telegram.org/bots/api#edituserstarsubscription
 */
class EditUserStarSubscription
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Identifier of the user whose subscription will be edited */
    private int $user_id;
    /** @var string Telegram payment identifier for the subscription */
    private string $telegram_payment_charge_id;
    /** @var bool Pass <em>True</em> to cancel extension of the user subscription; the subscription must be active up to the end of the current subscription period. Pass <em>False</em> to allow the user to re-enable a subscription that was previously canceled by the bot. */
    private bool $is_canceled;

    /**
     * @param Request $request
     * @param int $user_id Identifier of the user whose subscription will be edited
     * @param string $telegram_payment_charge_id Telegram payment identifier for the subscription
     * @param bool $is_canceled Pass <em>True</em> to cancel extension of the user subscription; the subscription must be active up to the end of the current subscription period. Pass <em>False</em> to allow the user to re-enable a subscription that was previously canceled by the bot.
     */
    public function __construct(Request $request, int $user_id, string $telegram_payment_charge_id, bool $is_canceled)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->telegram_payment_charge_id = $telegram_payment_charge_id;
        $this->is_canceled = $is_canceled;
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
