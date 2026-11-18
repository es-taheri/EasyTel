<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputRichMessage;

/**
 * Use this method to stream a partial rich message to a user while the message is being generated. Note that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is finalized, you <strong>must</strong> call <a href="https://core.telegram.org/bots/api#sendrichmessage">sendRichMessage</a> with the complete message to persist it in the user's chat. Returns <em>True</em> on success.
 *
 * @method SendRichMessageDraft message_thread_id(int $value) Unique identifier for the target message thread
 * @method SendRichMessageDraft can_stop(bool $value) Pass <em>True</em> to show the user a button to stop further drafts. The bot will receive an <a href="https://core.telegram.org/bots/api#update">Update</a> “stopped_message_generation” if the user presses the button.
 * @method SendRichMessageDraft keep_on_stop(bool $value) Pass <em>True</em> to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
 *
 * @link https://core.telegram.org/bots/api#sendrichmessagedraft
 */
class SendRichMessageDraft
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier for the target private chat */
    private int $chat_id;
    /** @var int Unique identifier for the target message thread */
    private int $message_thread_id;
    /** @var int Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation. */
    private int $draft_id;
    /** @var InputRichMessage The partial message to be streamed. Direct upload of new files and explicit upload of files by a URL isn't supported. */
    private InputRichMessage $rich_message;
    /** @var bool Pass <em>True</em> to show the user a button to stop further drafts. The bot will receive an <a href="https://core.telegram.org/bots/api#update">Update</a> “stopped_message_generation” if the user presses the button. */
    private bool $can_stop;
    /** @var bool Pass <em>True</em> to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message. */
    private bool $keep_on_stop;

    /**
     * @param Request $request
     * @param int $chat_id Unique identifier for the target private chat
     * @param int $draft_id Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation.
     * @param InputRichMessage $rich_message The partial message to be streamed. Direct upload of new files and explicit upload of files by a URL isn't supported.
     */
    public function __construct(Request $request, int $chat_id, int $draft_id, InputRichMessage $rich_message)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->draft_id = $draft_id;
        $this->rich_message = $rich_message;
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
