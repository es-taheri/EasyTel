<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\SuggestedPostParameters;
use EasyTel\Types\ReplyParameters;
use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardRemove;
use EasyTel\Types\ForceReply;
/**
 * @method SendVideo business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message will be sent
 * @method SendVideo message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendVideo direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
 * @method SendVideo duration(int $value) Duration of sent video in seconds
 * @method SendVideo width(int $value) Video width
 * @method SendVideo height(int $value) Video height
 * @method SendVideo thumbnail(mixed $value) Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail&#39;s width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can&#39;t be reused and can be only uploaded as a new file, so you can pass “attach://&lt;file_attach_name&gt;” if the thumbnail was uploaded using multipart/form-data under &lt;file_attach_name&gt;. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method SendVideo cover(mixed $value) Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://&lt;file_attach_name&gt;” to upload a new one using multipart/form-data under &lt;file_attach_name&gt; name. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method SendVideo start_timestamp(int $value) Start timestamp for the video in the message
 * @method SendVideo caption(string $value) Video caption (may also be used when resending videos by <em>file_id</em>), 0-1024 characters after entities parsing
 * @method SendVideo parse_mode(string $value) Mode for parsing entities in the video caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendVideo caption_entities(string  $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method SendVideo show_caption_above_media(bool $value) Pass <em>True</em>, if the caption must be shown above the message media
 * @method SendVideo has_spoiler(bool $value) Pass <em>True</em> if the video needs to be covered with a spoiler animation
 * @method SendVideo supports_streaming(bool $value) Pass <em>True</em> if the uploaded video is suitable for streaming
 * @method SendVideo disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendVideo protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendVideo allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot&#39;s balance
 * @method SendVideo message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendVideo suggested_post_parameters(SuggestedPostParameters $value) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
 * @method SendVideo reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendVideo reply_markup(InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $value) Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user
 */
class SendVideo
{
    private Request $_request;
    private bool $_sent = false;
    private int|string $chat_id;
    private mixed $video;
    private string $business_connection_id;
    private int $message_thread_id;
    private int $direct_messages_topic_id;
    private int $duration;
    private int $width;
    private int $height;
    private mixed $thumbnail;
    private mixed $cover;
    private int $start_timestamp;
    private string $caption;
    private string $parse_mode;
    private string  $caption_entities;
    private bool $show_caption_above_media;
    private bool $has_spoiler;
    private bool $supports_streaming;
    private bool $disable_notification;
    private bool $protect_content;
    private bool $allow_paid_broadcast;
    private string $message_effect_id;
    private SuggestedPostParameters $suggested_post_parameters;
    private ReplyParameters $reply_parameters;
    private InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $reply_markup;

    public function __construct(Request $request, int|string $chat_id, mixed $video)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->video = $video;
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