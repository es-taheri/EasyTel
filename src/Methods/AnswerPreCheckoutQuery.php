<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method AnswerPreCheckoutQuery error_message(string $value) Required if <em>ok</em> is <em>False</em>. Error message in human readable form that explains the reason for failure to proceed with the checkout (e.g. &quot;Sorry, somebody just bought the last of our amazing black T-shirts while you were busy filling out your payment details. Please choose a different color or garment!&quot;). Telegram will display this message to the user.
 */
class AnswerPreCheckoutQuery
{
    private Request $_request;
    private bool $_sent = false;
    private string $pre_checkout_query_id;
    private bool $ok;
    private string $error_message;

    public function __construct(Request $request, string $pre_checkout_query_id, bool $ok)
    {
        $this->_request = $request;
        $this->pre_checkout_query_id = $pre_checkout_query_id;
        $this->ok = $ok;
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