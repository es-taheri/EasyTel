<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SetUserEmojiStatus emoji_status_custom_emoji_id(string $value) Custom emoji identifier of the emoji status to set. Pass an empty string to remove the status.
 * @method SetUserEmojiStatus emoji_status_expiration_date(int $value) Expiration date of the emoji status, if any
 */
class SetUserEmojiStatus
{
    private Request $_request;
    private bool $_sent = false;
    private int $user_id;
    private string $emoji_status_custom_emoji_id;
    private int $emoji_status_expiration_date;

    public function __construct(Request $request, int $user_id)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
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