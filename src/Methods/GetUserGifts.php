<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method GetUserGifts exclude_unlimited(bool $value) Pass <em>True</em> to exclude gifts that can be purchased an unlimited number of times
 * @method GetUserGifts exclude_limited_upgradable(bool $value) Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
 * @method GetUserGifts exclude_limited_non_upgradable(bool $value) Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can&#39;t be upgraded to unique
 * @method GetUserGifts exclude_from_blockchain(bool $value) Pass <em>True</em> to exclude gifts that were assigned from the TON blockchain and can&#39;t be resold or transferred in Telegram
 * @method GetUserGifts exclude_unique(bool $value) Pass <em>True</em> to exclude unique gifts
 * @method GetUserGifts sort_by_price(bool $value) Pass <em>True</em> to sort results by gift price instead of send date. Sorting is applied before pagination.
 * @method GetUserGifts offset(string $value) Offset of the first entry to return as received from the previous request; use an empty string to get the first chunk of results
 * @method GetUserGifts limit(int $value) The maximum number of gifts to be returned; 1-100. Defaults to 100
 */
class GetUserGifts
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int $user_id;
    private bool $exclude_unlimited;
    private bool $exclude_limited_upgradable;
    private bool $exclude_limited_non_upgradable;
    private bool $exclude_from_blockchain;
    private bool $exclude_unique;
    private bool $sort_by_price;
    private string $offset;
    private int $limit;

    public function __construct(Request $request, int $user_id)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        $this->_returned = true;
        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];
        foreach ($this as $key => $value):
            if (isset($this->{$key}) && !in_array($key, ['_request', '_result'])):
                if (gettype($value) == 'object')
                    $parameters[$key] = (fn() => ($this->_output()))->bindTo($value, $value)();
                else
                    $parameters[$key] = $value;
            endif;
        endforeach;
        $r = new \ReflectionClass($this);
        $this->_sent = true;
        return $this->_request->send(lcfirst($r->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if (!$this->_returned && !$this->_sent) $this->_result();
    }
}