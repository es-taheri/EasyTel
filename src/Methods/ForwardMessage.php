<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\SuggestedPostParameters;
/**
 * @method ForwardMessage message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method ForwardMessage direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the message will be forwarded; required if the message is forwarded to a direct messages chat
 * @method ForwardMessage video_start_timestamp(int $value) New start timestamp for the forwarded video in the message
 * @method ForwardMessage disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method ForwardMessage protect_content(bool $value) Protects the contents of the forwarded message from forwarding and saving
 * @method ForwardMessage message_effect_id(string $value) Unique identifier of the message effect to be added to the message; only available when forwarding to private chats
 * @method ForwardMessage suggested_post_parameters(SuggestedPostParameters $value) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only
 */
class ForwardMessage
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int|string $chat_id;
    private int|string $from_chat_id;
    private int $message_id;
    private int $message_thread_id;
    private int $direct_messages_topic_id;
    private int $video_start_timestamp;
    private bool $disable_notification;
    private bool $protect_content;
    private string $message_effect_id;
    private SuggestedPostParameters $suggested_post_parameters;

    public function __construct(Request $request, int|string $chat_id, int|string $from_chat_id, int $message_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->from_chat_id = $from_chat_id;
        $this->message_id = $message_id;
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