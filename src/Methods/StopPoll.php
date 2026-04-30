<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method StopPoll business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message to be edited was sent
 * @method StopPoll reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for a new message <a href="/bots/features#inline-keyboards">inline keyboard</a>.
 */
class StopPoll
{
    private Request $_request;
    private bool $_sent = false;
    private int|string $chat_id;
    private int $message_id;
    private string $business_connection_id;
    private InlineKeyboardMarkup $reply_markup;

    public function __construct(Request $request, int|string $chat_id, int $message_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
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