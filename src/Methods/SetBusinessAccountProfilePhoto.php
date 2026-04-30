<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputProfilePhoto;
/**
 * @method SetBusinessAccountProfilePhoto is_public(bool $value) Pass <em>True</em> to set the public photo, which will be visible even if the main photo is hidden by the business account&#39;s privacy settings. An account can have only one public photo.
 */
class SetBusinessAccountProfilePhoto
{
    private Request $_request;
    private bool $_sent = false;
    private string $business_connection_id;
    private InputProfilePhoto $photo;
    private bool $is_public;

    public function __construct(Request $request, string $business_connection_id, InputProfilePhoto $photo)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->photo = $photo;
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