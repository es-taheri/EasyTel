<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SendGift user_id(int $value) Required if <em>chat_id</em> is not specified. Unique identifier of the target user who will receive the gift.
 * @method SendGift chat_id(int|string $value) Required if <em>user_id</em> is not specified. Unique identifier for the chat or username of the channel (in the format <code>@channelusername</code>) that will receive the gift.
 * @method SendGift pay_for_upgrade(bool $value) Pass <em>True</em> to pay for the gift upgrade from the bot&#39;s balance, thereby making the upgrade free for the receiver
 * @method SendGift text(string $value) Text that will be shown along with the gift; 0-128 characters
 * @method SendGift text_parse_mode(string $value) Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 * @method SendGift text_entities(string  $value) A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of <em>text_parse_mode</em>. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 */
class SendGift
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private string $gift_id;
    private int $user_id;
    private int|string $chat_id;
    private bool $pay_for_upgrade;
    private string $text;
    private string $text_parse_mode;
    private string  $text_entities;

    public function __construct(Request $request, string $gift_id)
    {
        $this->_request = $request;
        $this->gift_id = $gift_id;
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