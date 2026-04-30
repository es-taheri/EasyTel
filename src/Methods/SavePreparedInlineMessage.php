<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineQueryResult;
/**
 * @method SavePreparedInlineMessage allow_user_chats(bool $value) Pass <em>True</em> if the message can be sent to private chats with users
 * @method SavePreparedInlineMessage allow_bot_chats(bool $value) Pass <em>True</em> if the message can be sent to private chats with bots
 * @method SavePreparedInlineMessage allow_group_chats(bool $value) Pass <em>True</em> if the message can be sent to group and supergroup chats
 * @method SavePreparedInlineMessage allow_channel_chats(bool $value) Pass <em>True</em> if the message can be sent to channel chats
 */
class SavePreparedInlineMessage
{
    private Request $_request;
    private bool $_sent = false;
    private int $user_id;
    private InlineQueryResult $result;
    private bool $allow_user_chats;
    private bool $allow_bot_chats;
    private bool $allow_group_chats;
    private bool $allow_channel_chats;

    public function __construct(Request $request, int $user_id, InlineQueryResult $result)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->result = $result;
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