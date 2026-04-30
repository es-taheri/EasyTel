<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method AnswerShippingQuery shipping_options(string  $value) Required if <em>ok</em> is <em>True</em>. A JSON-serialized array of available shipping options.
 * @method AnswerShippingQuery error_message(string $value) Required if <em>ok</em> is <em>False</em>. Error message in human readable form that explains why it is impossible to complete the order (e.g. “Sorry, delivery to your desired address is unavailable”). Telegram will display this message to the user.
 */
class AnswerShippingQuery
{
    private Request $_request;
    private bool $_sent = false;
    private string $shipping_query_id;
    private bool $ok;
    private string  $shipping_options;
    private string $error_message;

    public function __construct(Request $request, string $shipping_query_id, bool $ok)
    {
        $this->_request = $request;
        $this->shipping_query_id = $shipping_query_id;
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