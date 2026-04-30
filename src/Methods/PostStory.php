<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputStoryContent;
/**
 * @method PostStory caption(string $value) Caption of the story, 0-2048 characters after entities parsing
 * @method PostStory parse_mode(string $value) Mode for parsing entities in the story caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method PostStory caption_entities(string  $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method PostStory areas(string  $value) A JSON-serialized list of clickable areas to be shown on the story
 * @method PostStory post_to_chat_page(bool $value) Pass <em>True</em> to keep the story accessible after it expires
 * @method PostStory protect_content(bool $value) Pass <em>True</em> if the content of the story must be protected from forwarding and screenshotting
 */
class PostStory
{
    private Request $_request;
    private bool $_sent = false;
    private string $business_connection_id;
    private InputStoryContent $content;
    private int $active_period;
    private string $caption;
    private string $parse_mode;
    private string  $caption_entities;
    private string  $areas;
    private bool $post_to_chat_page;
    private bool $protect_content;

    public function __construct(Request $request, string $business_connection_id, InputStoryContent $content, int $active_period)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->content = $content;
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