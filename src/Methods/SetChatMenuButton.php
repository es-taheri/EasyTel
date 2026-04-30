<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\MenuButton;
/**
 * @method SetChatMenuButton chat_id(int $value) Unique identifier for the target private chat. If not specified, default bot&#39;s menu button will be changed
 * @method SetChatMenuButton menu_button(MenuButton $value) A JSON-serialized object for the bot&#39;s new menu button. Defaults to <a href="https://core.telegram.org/bots/api#menubuttondefault">MenuButtonDefault</a>
 */
class SetChatMenuButton
{
    private Request $_request;
    private bool $_sent = false;
    private int $chat_id;
    private MenuButton $menu_button;

    public function __construct(Request $request)
    {
        $this->_request = $request;
        
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