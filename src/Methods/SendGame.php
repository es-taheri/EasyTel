<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\ReplyParameters;
use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method SendGame business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message will be sent
 * @method SendGame message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendGame disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendGame protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendGame allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot&#39;s balance
 * @method SendGame message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendGame reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendGame reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>. If empty, one &#39;Play game_title&#39; button will be shown. If not empty, the first button must launch the game.
 */
class SendGame
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int $chat_id;
    private string $game_short_name;
    private string $business_connection_id;
    private int $message_thread_id;
    private bool $disable_notification;
    private bool $protect_content;
    private bool $allow_paid_broadcast;
    private string $message_effect_id;
    private ReplyParameters $reply_parameters;
    private InlineKeyboardMarkup $reply_markup;

    public function __construct(Request $request, int $chat_id, string $game_short_name)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->game_short_name = $game_short_name;
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