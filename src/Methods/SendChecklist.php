<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputChecklist;
use EasyTel\Types\ReplyParameters;
use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method SendChecklist disable_notification(bool $value) Sends the message silently. Users will receive a notification with no sound.
 * @method SendChecklist protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendChecklist message_effect_id(string $value) Unique identifier of the message effect to be added to the message
 * @method SendChecklist reply_parameters(ReplyParameters $value) A JSON-serialized object for description of the message to reply to
 * @method SendChecklist reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>
 */
class SendChecklist
{
    private Request $_request;
    private bool $_sent = false;
    private string $business_connection_id;
    private int $chat_id;
    private InputChecklist $checklist;
    private bool $disable_notification;
    private bool $protect_content;
    private string $message_effect_id;
    private ReplyParameters $reply_parameters;
    private InlineKeyboardMarkup $reply_markup;

    public function __construct(Request $request, string $business_connection_id, int $chat_id, InputChecklist $checklist)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->chat_id = $chat_id;
        $this->checklist = $checklist;
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