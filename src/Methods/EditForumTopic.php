<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method EditForumTopic name(string $value) New topic name, 0-128 characters. If not specified or empty, the current name of the topic will be kept
 * @method EditForumTopic icon_custom_emoji_id(string $value) New unique identifier of the custom emoji shown as the topic icon. Use <a href="https://core.telegram.org/bots/api#getforumtopiciconstickers">getForumTopicIconStickers</a> to get all allowed custom emoji identifiers. Pass an empty string to remove the icon. If not specified, the current icon will be kept
 */
class EditForumTopic
{
    private Request $_request;
    private bool $_sent = false;
    private int|string $chat_id;
    private int $message_thread_id;
    private string $name;
    private string $icon_custom_emoji_id;

    public function __construct(Request $request, int|string $chat_id, int $message_thread_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->message_thread_id = $message_thread_id;
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