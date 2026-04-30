<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SendChatAction business_connection_id(string $value) Unique identifier of the business connection on behalf of which the action will be sent
 * @method SendChatAction message_thread_id(int $value) Unique identifier for the target message thread or topic of a forum; for supergroups and private chats of bots with forum topic mode enabled only
 */
class SendChatAction
{
    private Request $_request;
    private bool $_sent = false;
    private int|string $chat_id;
    private string $action;
    private string $business_connection_id;
    private int $message_thread_id;

    public function __construct(Request $request, int|string $chat_id, string $action)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->action = $action;
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