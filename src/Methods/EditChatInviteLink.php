<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method EditChatInviteLink name(string $value) Invite link name; 0-32 characters
 * @method EditChatInviteLink expire_date(int $value) Point in time (Unix timestamp) when the link will expire
 * @method EditChatInviteLink member_limit(int $value) The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 * @method EditChatInviteLink creates_join_request(bool $value) <em>True</em>, if users joining the chat via the link need to be approved by chat administrators. If <em>True</em>, <em>member_limit</em> can&#39;t be specified
 */
class EditChatInviteLink
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int|string $chat_id;
    private string $invite_link;
    private string $name;
    private int $expire_date;
    private int $member_limit;
    private bool $creates_join_request;

    public function __construct(Request $request, int|string $chat_id, string $invite_link)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->invite_link = $invite_link;
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