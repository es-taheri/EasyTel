<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method RemoveBusinessAccountProfilePhoto is_public(bool $value) Pass <em>True</em> to remove the public photo, which is visible even if the main photo is hidden by the business account&#39;s privacy settings. After the main photo is removed, the previous profile photo (if present) becomes the main photo.
 */
class RemoveBusinessAccountProfilePhoto
{
    private Request $_request;
    private bool $_sent = false;
    private string $business_connection_id;
    private bool $is_public;

    public function __construct(Request $request, string $business_connection_id)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
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
        if (!$this->_sent) $this->_result();
    }
}