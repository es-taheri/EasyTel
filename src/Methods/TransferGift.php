<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Transfers an owned unique gift to another user. Requires the <em>can_transfer_and_upgrade_gifts</em> business bot right. Requires <em>can_transfer_stars</em> business bot right if the transfer is paid. Returns <em>True</em> on success.
 *
 * @method TransferGift star_count(int $value) The amount of Telegram Stars that will be paid for the transfer from the business account balance. If positive, then the <em>can_transfer_stars</em> business bot right is required.
 *
 * @link https://core.telegram.org/bots/api#transfergift
 */
class TransferGift
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection */
    private string $business_connection_id;
    /** @var string Unique identifier of the regular gift that should be transferred */
    private string $owned_gift_id;
    /** @var int Unique identifier of the chat which will own the gift. The chat must be active in the last 24 hours. */
    private int $new_owner_chat_id;
    /** @var int The amount of Telegram Stars that will be paid for the transfer from the business account balance. If positive, then the <em>can_transfer_stars</em> business bot right is required. */
    private int $star_count;

    /**
     * @param Request $request
     * @param string $business_connection_id Unique identifier of the business connection
     * @param string $owned_gift_id Unique identifier of the regular gift that should be transferred
     * @param int $new_owner_chat_id Unique identifier of the chat which will own the gift. The chat must be active in the last 24 hours.
     */
    public function __construct(Request $request, string $business_connection_id, string $owned_gift_id, int $new_owner_chat_id)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->owned_gift_id = $owned_gift_id;
        $this->new_owner_chat_id = $new_owner_chat_id;
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
