<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Upgrades a given regular gift to a unique gift. Requires the <em>can_transfer_and_upgrade_gifts</em> business bot right. Additionally requires the <em>can_transfer_stars</em> business bot right if the upgrade is paid. Returns <em>True</em> on success.
 *
 * @method UpgradeGift keep_original_details(bool $value) Pass <em>True</em> to keep the original gift text, sender and receiver in the upgraded gift
 * @method UpgradeGift star_count(int $value) The amount of Telegram Stars that will be paid for the upgrade from the business account balance. If <code>gift.prepaid_upgrade_star_count > 0</code>, then pass 0, otherwise, the <em>can_transfer_stars</em> business bot right is required and <code>gift.upgrade_star_count</code> must be passed.
 *
 * @link https://core.telegram.org/bots/api#upgradegift
 */
class UpgradeGift
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection */
    private string $business_connection_id;
    /** @var string Unique identifier of the regular gift that should be upgraded to a unique one */
    private string $owned_gift_id;
    /** @var bool Pass <em>True</em> to keep the original gift text, sender and receiver in the upgraded gift */
    private bool $keep_original_details;
    /** @var int The amount of Telegram Stars that will be paid for the upgrade from the business account balance. If <code>gift.prepaid_upgrade_star_count > 0</code>, then pass 0, otherwise, the <em>can_transfer_stars</em> business bot right is required and <code>gift.upgrade_star_count</code> must be passed. */
    private int $star_count;

    /**
     * @param Request $request
     * @param string $business_connection_id Unique identifier of the business connection
     * @param string $owned_gift_id Unique identifier of the regular gift that should be upgraded to a unique one
     */
    public function __construct(Request $request, string $business_connection_id, string $owned_gift_id)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->owned_gift_id = $owned_gift_id;
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
