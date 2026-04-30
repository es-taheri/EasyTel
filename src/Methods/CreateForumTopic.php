<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method CreateForumTopic icon_color(int $value) Color of the topic icon in RGB format. Currently, must be one of 7322096 (0x6FB9F0), 16766590 (0xFFD67E), 13338331 (0xCB86DB), 9367192 (0x8EEE98), 16749490 (0xFF93B2), or 16478047 (0xFB6F5F)
 * @method CreateForumTopic icon_custom_emoji_id(string $value) Unique identifier of the custom emoji shown as the topic icon. Use <a href="https://core.telegram.org/bots/api#getforumtopiciconstickers">getForumTopicIconStickers</a> to get all allowed custom emoji identifiers.
 */
class CreateForumTopic
{
    private Request $_request;
    private bool $_sent = false;
    private int|string $chat_id;
    private string $name;
    private int $icon_color;
    private string $icon_custom_emoji_id;

    public function __construct(Request $request, int|string $chat_id, string $name)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->name = $name;
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