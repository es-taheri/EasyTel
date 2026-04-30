<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;



class UploadStickerFile
{
    private Request $_request;
    private bool $_sent = false;
    private int $user_id;
    private mixed $sticker;
    private string $sticker_format;

    public function __construct(Request $request, int $user_id, mixed $sticker, string $sticker_format)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->sticker = $sticker;
        $this->sticker_format = $sticker_format;
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