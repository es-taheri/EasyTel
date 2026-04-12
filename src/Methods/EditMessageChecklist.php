<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputChecklist;
use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method EditMessageChecklist reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for the new <a href="/bots/features#inline-keyboards">inline keyboard</a> for the message
 */
class EditMessageChecklist
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private string $business_connection_id;
    private int $chat_id;
    private int $message_id;
    private InputChecklist $checklist;
    private InlineKeyboardMarkup $reply_markup;

    public function __construct(Request $request, string $business_connection_id, int $chat_id, int $message_id, InputChecklist $checklist)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->checklist = $checklist;
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