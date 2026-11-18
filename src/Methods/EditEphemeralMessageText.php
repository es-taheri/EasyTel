<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\InputRichMessage;
use EasyTel\Types\LinkPreviewOptions;

/**
 * Use this method to edit an ephemeral text or rich message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, <em>True</em> is returned.
 *
 * @method EditEphemeralMessageText text(string $value) New text of the message, 1-4096 characters after entity parsing; required if <em>rich_message</em> isn't specified
 * @method EditEphemeralMessageText parse_mode(string $value) Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method EditEphemeralMessageText entities(string $value) A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
 * @method EditEphemeralMessageText rich_message(InputRichMessage $value) New rich content of the message; required if <em>text</em> isn't specified
 * @method EditEphemeralMessageText link_preview_options(LinkPreviewOptions $value) Link preview generation options for the message
 * @method EditEphemeralMessageText reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>
 *
 * @link https://core.telegram.org/bots/api#editephemeralmessagetext
 */
class EditEphemeralMessageText
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Identifier of the user who received the message */
    private int $receiver_user_id;
    /** @var int Identifier of the ephemeral message to edit */
    private int $ephemeral_message_id;
    /** @var string New text of the message, 1-4096 characters after entity parsing; required if <em>rich_message</em> isn't specified */
    private string $text;
    /** @var string Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em> */
    private string $entities;
    /** @var InputRichMessage New rich content of the message; required if <em>text</em> isn't specified */
    private InputRichMessage $rich_message;
    /** @var LinkPreviewOptions Link preview generation options for the message */
    private LinkPreviewOptions $link_preview_options;
    /** @var InlineKeyboardMarkup A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a> */
    private InlineKeyboardMarkup $reply_markup;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code>
     * @param int $receiver_user_id Identifier of the user who received the message
     * @param int $ephemeral_message_id Identifier of the ephemeral message to edit
     */
    public function __construct(Request $request, int|string $chat_id, int $receiver_user_id, int $ephemeral_message_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->receiver_user_id = $receiver_user_id;
        $this->ephemeral_message_id = $ephemeral_message_id;
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
