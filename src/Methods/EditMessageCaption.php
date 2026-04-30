<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method EditMessageCaption business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message to be edited was sent
 * @method EditMessageCaption chat_id(int|string $value) Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
 * @method EditMessageCaption message_id(int $value) Required if <em>inline_message_id</em> is not specified. Identifier of the message to edit
 * @method EditMessageCaption inline_message_id(string $value) Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message
 * @method EditMessageCaption caption(string $value) New caption of the message, 0-1024 characters after entities parsing
 * @method EditMessageCaption parse_mode(string $value) Mode for parsing entities in the message caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method EditMessageCaption caption_entities(string  $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method EditMessageCaption show_caption_above_media(bool $value) Pass <em>True</em>, if the caption must be shown above the message media. Supported only for animation, photo and video messages.
 * @method EditMessageCaption reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>.
 */
class EditMessageCaption
{
    private Request $_request;
    private bool $_sent = false;
    private string $business_connection_id;
    private int|string $chat_id;
    private int $message_id;
    private string $inline_message_id;
    private string $caption;
    private string $parse_mode;
    private string  $caption_entities;
    private bool $show_caption_above_media;
    private InlineKeyboardMarkup $reply_markup;

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