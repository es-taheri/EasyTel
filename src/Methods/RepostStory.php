<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method RepostStory post_to_chat_page(bool $value) Pass <em>True</em> to keep the story accessible after it expires
 * @method RepostStory protect_content(bool $value) Pass <em>True</em> if the content of the story must be protected from forwarding and screenshotting
 */
class RepostStory
{
    private Request $_request;
    private bool $_sent = false;
    private string $business_connection_id;
    private int $from_chat_id;
    private int $from_story_id;
    private int $active_period;
    private bool $post_to_chat_page;
    private bool $protect_content;

    public function __construct(Request $request, string $business_connection_id, int $from_chat_id, int $from_story_id, int $active_period)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->from_chat_id = $from_chat_id;
        $this->from_story_id = $from_story_id;
        $this->active_period = $active_period;
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