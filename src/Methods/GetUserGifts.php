<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Returns the gifts owned and hosted by a user. Returns <a href="https://core.telegram.org/bots/api#ownedgifts">OwnedGifts</a> on success.
 *
 * @method GetUserGifts exclude_unlimited(bool $value) Pass <em>True</em> to exclude gifts that can be purchased an unlimited number of times
 * @method GetUserGifts exclude_limited_upgradable(bool $value) Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
 * @method GetUserGifts exclude_limited_non_upgradable(bool $value) Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can't be upgraded to unique
 * @method GetUserGifts exclude_from_blockchain(bool $value) Pass <em>True</em> to exclude gifts that were assigned from the TON blockchain and can't be resold or transferred in Telegram
 * @method GetUserGifts exclude_unique(bool $value) Pass <em>True</em> to exclude unique gifts
 * @method GetUserGifts sort_by_price(bool $value) Pass <em>True</em> to sort results by gift price instead of send date. Sorting is applied before pagination.
 * @method GetUserGifts offset(string $value) Offset of the first entry to return as received from the previous request; use an empty string to get the first chunk of results
 * @method GetUserGifts limit(int $value) The maximum number of gifts to be returned; 1-100. Defaults to 100.
 *
 * @link https://core.telegram.org/bots/api#getusergifts
 */
class GetUserGifts
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the user */
    private int $user_id;
    /** @var bool Pass <em>True</em> to exclude gifts that can be purchased an unlimited number of times */
    private bool $exclude_unlimited;
    /** @var bool Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can be upgraded to unique */
    private bool $exclude_limited_upgradable;
    /** @var bool Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can't be upgraded to unique */
    private bool $exclude_limited_non_upgradable;
    /** @var bool Pass <em>True</em> to exclude gifts that were assigned from the TON blockchain and can't be resold or transferred in Telegram */
    private bool $exclude_from_blockchain;
    /** @var bool Pass <em>True</em> to exclude unique gifts */
    private bool $exclude_unique;
    /** @var bool Pass <em>True</em> to sort results by gift price instead of send date. Sorting is applied before pagination. */
    private bool $sort_by_price;
    /** @var string Offset of the first entry to return as received from the previous request; use an empty string to get the first chunk of results */
    private string $offset;
    /** @var int The maximum number of gifts to be returned; 1-100. Defaults to 100. */
    private int $limit;

    /**
     * @param Request $request
     * @param int $user_id Unique identifier of the user
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
