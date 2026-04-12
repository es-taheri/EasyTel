<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SetStickerKeywords keywords(string  $value) A JSON-serialized list of 0-20 search keywords for the sticker with total length of up to 64 characters
 */
class SetStickerKeywords
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private string $sticker;
    private string  $keywords;

    public function __construct(Request $request, string $sticker)
    {
        $this->_request = $request;
        $this->sticker = $sticker;
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