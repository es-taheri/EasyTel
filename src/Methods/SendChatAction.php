<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns <em>True</em> on success.
 * We only recommend using this method when a response from the bot will take a <strong>noticeable</strong> amount of time to arrive.
 *
 * <p>Example: The <a href="https://t.me/imagebot">ImageBot</a> needs some time to process a request and upload the image. Instead of sending a text message along the lines of “Retrieving image, please wait…”, the bot may use <a href="https://core.telegram.org/bots/api#sendchataction">sendChatAction</a> with <em>action</em> = <em>upload_photo</em>. The user will see a “sending photo” status for the bot.</p>
 *
 * @method SendChatAction business_connection_id(string $value) Unique identifier of the business connection on behalf of which the action will be sent
 * @method SendChatAction message_thread_id(int $value) Unique identifier for the target message thread or topic of a forum; for supergroups and private chats of bots with forum topic mode enabled only
 *
 * @link https://core.telegram.org/bots/api#sendchataction
 */
class SendChatAction
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection on behalf of which the action will be sent */
    private string $business_connection_id;
    /** @var int|string Unique identifier for the target chat or username of the target bot or supergroup in the format <code>@username</code>. Channel chats and channel direct messages chats aren't supported. */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread or topic of a forum; for supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var string Type of action to broadcast. Choose one, depending on what the user is about to receive: <em>typing</em> for <a href="https://core.telegram.org/bots/api#sendmessage">text messages</a>, <em>upload_photo</em> for <a href="https://core.telegram.org/bots/api#sendphoto">photos</a>, <em>record_video</em> or <em>upload_video</em> for <a href="https://core.telegram.org/bots/api#sendvideo">videos</a>, <em>record_voice</em> or <em>upload_voice</em> for <a href="https://core.telegram.org/bots/api#sendvoice">voice notes</a>, <em>upload_document</em> for <a href="https://core.telegram.org/bots/api#senddocument">general files</a>, <em>choose_sticker</em> for <a href="https://core.telegram.org/bots/api#sendsticker">stickers</a>, <em>find_location</em> for <a href="https://core.telegram.org/bots/api#sendlocation">location data</a>, <em>record_video_note</em> or <em>upload_video_note</em> for <a href="https://core.telegram.org/bots/api#sendvideonote">video notes</a>. */
    private string $action;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target bot or supergroup in the format <code>@username</code>. Channel chats and channel direct messages chats aren't supported.
     * @param string $action Type of action to broadcast. Choose one, depending on what the user is about to receive: <em>typing</em> for <a href="https://core.telegram.org/bots/api#sendmessage">text messages</a>, <em>upload_photo</em> for <a href="https://core.telegram.org/bots/api#sendphoto">photos</a>, <em>record_video</em> or <em>upload_video</em> for <a href="https://core.telegram.org/bots/api#sendvideo">videos</a>, <em>record_voice</em> or <em>upload_voice</em> for <a href="https://core.telegram.org/bots/api#sendvoice">voice notes</a>, <em>upload_document</em> for <a href="https://core.telegram.org/bots/api#senddocument">general files</a>, <em>choose_sticker</em> for <a href="https://core.telegram.org/bots/api#sendsticker">stickers</a>, <em>find_location</em> for <a href="https://core.telegram.org/bots/api#sendlocation">location data</a>, <em>record_video_note</em> or <em>upload_video_note</em> for <a href="https://core.telegram.org/bots/api#sendvideonote">video notes</a>.
     */
    public function __construct(Request $request, int|string $chat_id, string $action)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->action = $action;
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
