<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\EphemeralMessageParameters;
use EasyTel\Types\ForceReply;
use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardRemove;
use EasyTel\Types\ReplyParameters;
use EasyTel\Types\SuggestedPostParameters;

/**
 * Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 *
 * @method SendAnimation business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message will be sent
 * @method SendAnimation chat_id(int|string $value) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>; required unless the message is sent as an ephemeral reply to a callback query
 * @method SendAnimation message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendAnimation direct_messages_topic_id(int $value) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
 * @method SendAnimation ephemeral_message_parameters(EphemeralMessageParameters $value) A JSON-serialized object containing the parameters of the ephemeral message to send
 * @method SendAnimation duration(int $value) Duration of sent animation in seconds
 * @method SendAnimation width(int $value) Animation width
 * @method SendAnimation height(int $value) Animation height
 * @method SendAnimation thumbnail(mixed $value) Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method SendAnimation caption(string $value) Animation caption (may also be used when resending animation by <em>file_id</em>), 0-1024 characters after entities parsing
 * @method SendAnimation parse_mode(string $value) Mode for parsing entities in the animation caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendAnimation caption_entities(string $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method SendAnimation show_caption_above_media(bool $value) Pass <em>True</em> if the caption must be shown above the message media
 * @method SendAnimation has_spoiler(bool $value) Pass <em>True</em> if the animation needs to be covered with a spoiler animation
 * @method SendAnimation disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendAnimation protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendAnimation allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
 * @method SendAnimation message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendAnimation suggested_post_parameters(SuggestedPostParameters $value) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
 * @method SendAnimation reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendAnimation reply_markup(InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $value) Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>, <a href="https://core.telegram.org/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
 *
 * @link https://core.telegram.org/bots/api#sendanimation
 */
class SendAnimation
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection on behalf of which the message will be sent */
    private string $business_connection_id;
    /** @var int|string Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>; required unless the message is sent as an ephemeral reply to a callback query */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var int Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat */
    private int $direct_messages_topic_id;
    /** @var EphemeralMessageParameters A JSON-serialized object containing the parameters of the ephemeral message to send */
    private EphemeralMessageParameters $ephemeral_message_parameters;
    /** @var mixed Animation to send. Pass a file_id as String to send an animation that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an animation from the Internet, or upload a new animation using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a> */
    private mixed $animation;
    /** @var int Duration of sent animation in seconds */
    private int $duration;
    /** @var int Animation width */
    private int $width;
    /** @var int Animation height */
    private int $height;
    /** @var mixed Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a> */
    private mixed $thumbnail;
    /** @var string Animation caption (may also be used when resending animation by <em>file_id</em>), 0-1024 characters after entities parsing */
    private string $caption;
    /** @var string Mode for parsing entities in the animation caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em> */
    private string $caption_entities;
    /** @var bool Pass <em>True</em> if the caption must be shown above the message media */
    private bool $show_caption_above_media;
    /** @var bool Pass <em>True</em> if the animation needs to be covered with a spoiler animation */
    private bool $has_spoiler;
    /** @var bool Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the sent message from forwarding and saving */
    private bool $protect_content;
    /** @var bool Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance. */
    private bool $allow_paid_broadcast;
    /** @var string Unique identifier of the message effect to be added to the message; for private chats only */
    private string $message_effect_id;
    /** @var SuggestedPostParameters A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined. */
    private SuggestedPostParameters $suggested_post_parameters;
    /** @var ReplyParameters Description of the message to reply to */
    private ReplyParameters $reply_parameters;
    /** @var InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>, <a href="https://core.telegram.org/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user. */
    private InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $reply_markup;

    /**
     * @param Request $request
     * @param mixed $animation Animation to send. Pass a file_id as String to send an animation that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an animation from the Internet, or upload a new animation using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     */
    public function __construct(Request $request, mixed $animation)
    {
        $this->_request = $request;
        $this->animation = $animation;
    }

    public function __call(string $name, array $arguments)
    {
        if (!property_exists($this, $name)) {
            $this->_sent = true; // never send a half built request from __destruct()
            throw new \BadMethodCallException(
                sprintf('Unknown parameter "%s" for %s.', $name, static::class)
            );
        }

        if ($arguments === []) {
            $this->_sent = true;
            throw new \InvalidArgumentException(
                sprintf('Parameter "%s" of %s requires a value.', $name, static::class)
            );
        }

        try {
            $this->{$name} = array_shift($arguments);
        } catch (\Throwable $e) {
            $this->_sent = true; // the value did not fit: the request is invalid
            throw $e;
        }

        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];

        foreach ($this as $key => $value) {
            if (str_starts_with($key, '_') || !isset($this->{$key})) {
                continue;
            }

            if (is_object($value)) {
                $parameters[$key] = method_exists($value, '_output')
                    ? (fn() => $this->_output())->bindTo($value, $value)()
                    : $value;
            } else {
                $parameters[$key] = $value;
            }
        }

        $reflection = new \ReflectionClass($this);
        $this->_sent = true;

        return $this->_request->send(lcfirst($reflection->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if ($this->_sent)
            return;

        // Fire-and-forget call from the destructor must never raise inside
        // shutdown; the explicit _result() call is the error-reporting path.
        try {
            $this->_result();
        } catch (\Throwable) {
            // Ignored on purpose.
        }
    }
}
