<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SetMyDescription description(string $value) New bot description; 0-512 characters. Pass an empty string to remove the dedicated description for the given language.
 * @method SetMyDescription language_code(string $value) A two-letter ISO 639-1 language code. If empty, the description will be applied to all users for whose language there is no dedicated description.
 */
class SetMyDescription
{
    private Request $_request;
    private bool $_sent = false;
    private string $description;
    private string $language_code;

    public function __construct(Request $request)
    {
        $this->_request = $request;
        
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