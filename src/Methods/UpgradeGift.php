<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method UpgradeGift keep_original_details(bool $value) Pass <em>True</em> to keep the original gift text, sender and receiver in the upgraded gift
 * @method UpgradeGift star_count(int $value) The amount of Telegram Stars that will be paid for the upgrade from the business account balance. If <code>gift.prepaid_upgrade_star_count &gt; 0</code>, then pass 0, otherwise, the <em>can_transfer_stars</em> business bot right is required and <code>gift.upgrade_star_count</code> must be passed.
 */
class UpgradeGift
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private string $business_connection_id;
    private string $owned_gift_id;
    private bool $keep_original_details;
    private int $star_count;

    public function __construct(Request $request, string $business_connection_id, string $owned_gift_id)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->owned_gift_id = $owned_gift_id;
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