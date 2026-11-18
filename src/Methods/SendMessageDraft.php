<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to stream a partial message to a user while the message is being generated. Note that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is finalized, you <strong>must</strong> call <a href="https://core.telegram.org/bots/api#sendmessage">sendMessage</a> with the complete message to persist it in the user's chat. Returns <em>True</em> on success.
 *
 * @method SendMessageDraft message_thread_id(int $value) Unique identifier for the target message thread
 * @method SendMessageDraft text(string $value) Text of the message to be sent, 0-4096 characters after entities parsing. Pass an empty text to show a “Thinking…” placeholder.
 * @method SendMessageDraft parse_mode(string $value) Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendMessageDraft entities(string $value) A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
 * @method SendMessageDraft can_stop(bool $value) Pass <em>True</em> to show the user a button to stop further drafts. The bot will receive an <a href="https://core.telegram.org/bots/api#update">Update</a> “stopped_message_generation” if the user presses the button.
 * @method SendMessageDraft keep_on_stop(bool $value) Pass <em>True</em> to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
 *
 * @link https://core.telegram.org/bots/api#sendmessagedraft
 */
class SendMessageDraft
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier for the target private chat */
    private int $chat_id;
    /** @var int Unique identifier for the target message thread */
    private int $message_thread_id;
    /** @var int Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation. */
    private int $draft_id;
    /** @var string Text of the message to be sent, 0-4096 characters after entities parsing. Pass an empty text to show a “Thinking…” placeholder. */
    private string $text;
    /** @var string Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em> */
    private string $entities;
    /** @var bool Pass <em>True</em> to show the user a button to stop further drafts. The bot will receive an <a href="https://core.telegram.org/bots/api#update">Update</a> “stopped_message_generation” if the user presses the button. */
    private bool $can_stop;
    /** @var bool Pass <em>True</em> to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message. */
    private bool $keep_on_stop;

    /**
     * @param Request $request
     * @param int $chat_id Unique identifier for the target private chat
     * @param int $draft_id Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation.
     */
    public function __construct(Request $request, int $chat_id, int $draft_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->draft_id = $draft_id;
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
