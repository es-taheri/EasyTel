<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SendMessageDraft message_thread_id(int $value) Unique identifier for the target message thread
 * @method SendMessageDraft parse_mode(string $value) Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendMessageDraft entities(string  $value) A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
 */
class SendMessageDraft
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int $chat_id;
    private int $draft_id;
    private string $text;
    private int $message_thread_id;
    private string $parse_mode;
    private string  $entities;

    public function __construct(Request $request, int $chat_id, int $draft_id, string $text)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->draft_id = $draft_id;
        $this->text = $text;
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