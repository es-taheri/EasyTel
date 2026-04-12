<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method GetBusinessAccountGifts exclude_unsaved(bool $value) Pass <em>True</em> to exclude gifts that aren&#39;t saved to the account&#39;s profile page
 * @method GetBusinessAccountGifts exclude_saved(bool $value) Pass <em>True</em> to exclude gifts that are saved to the account&#39;s profile page
 * @method GetBusinessAccountGifts exclude_unlimited(bool $value) Pass <em>True</em> to exclude gifts that can be purchased an unlimited number of times
 * @method GetBusinessAccountGifts exclude_limited_upgradable(bool $value) Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
 * @method GetBusinessAccountGifts exclude_limited_non_upgradable(bool $value) Pass <em>True</em> to exclude gifts that can be purchased a limited number of times and can&#39;t be upgraded to unique
 * @method GetBusinessAccountGifts exclude_unique(bool $value) Pass <em>True</em> to exclude unique gifts
 * @method GetBusinessAccountGifts exclude_from_blockchain(bool $value) Pass <em>True</em> to exclude gifts that were assigned from the TON blockchain and can&#39;t be resold or transferred in Telegram
 * @method GetBusinessAccountGifts sort_by_price(bool $value) Pass <em>True</em> to sort results by gift price instead of send date. Sorting is applied before pagination.
 * @method GetBusinessAccountGifts offset(string $value) Offset of the first entry to return as received from the previous request; use empty string to get the first chunk of results
 * @method GetBusinessAccountGifts limit(int $value) The maximum number of gifts to be returned; 1-100. Defaults to 100
 */
class GetBusinessAccountGifts
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private string $business_connection_id;
    private bool $exclude_unsaved;
    private bool $exclude_saved;
    private bool $exclude_unlimited;
    private bool $exclude_limited_upgradable;
    private bool $exclude_limited_non_upgradable;
    private bool $exclude_unique;
    private bool $exclude_from_blockchain;
    private bool $sort_by_price;
    private string $offset;
    private int $limit;

    public function __construct(Request $request, string $business_connection_id)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
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