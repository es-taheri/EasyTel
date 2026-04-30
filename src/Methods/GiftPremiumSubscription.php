<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method GiftPremiumSubscription text(string $value) Text that will be shown along with the service message about the subscription; 0-128 characters
 * @method GiftPremiumSubscription text_parse_mode(string $value) Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 * @method GiftPremiumSubscription text_entities(string  $value) A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of <em>text_parse_mode</em>. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 */
class GiftPremiumSubscription
{
    private Request $_request;
    private bool $_sent = false;
    private int $user_id;
    private int $month_count;
    private int $star_count;
    private string $text;
    private string $text_parse_mode;
    private string  $text_entities;

    public function __construct(Request $request, int $user_id, int $month_count, int $star_count)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->month_count = $month_count;
        $this->star_count = $star_count;
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