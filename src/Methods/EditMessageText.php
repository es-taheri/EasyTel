<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\InputRichMessage;
use EasyTel\Types\LinkPreviewOptions;

/**
 * Use this method to edit text, rich and <a href="https://core.telegram.org/bots/api#games">game</a> messages. On success, if the edited message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within <strong>48 hours</strong> from the time they were sent.
 *
 * @method EditMessageText business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message to be edited was sent
 * @method EditMessageText chat_id(int|string $value) Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>.
 * @method EditMessageText message_id(int $value) Required if <em>inline_message_id</em> is not specified. Identifier of the message to edit.
 * @method EditMessageText inline_message_id(string $value) Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message.
 * @method EditMessageText text(string $value) New text of the message, 1-4096 characters after entity parsing; required if <em>rich_message</em> isn't specified
 * @method EditMessageText parse_mode(string $value) Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method EditMessageText entities(string $value) A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
 * @method EditMessageText link_preview_options(LinkPreviewOptions $value) Link preview generation options for the message
 * @method EditMessageText rich_message(InputRichMessage $value) New rich content of the message; required if <em>text</em> isn't specified. Direct upload of new files and explicit upload of files by a URL isn't supported when an inline message is edited.
 * @method EditMessageText reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>
 *
 * @link https://core.telegram.org/bots/api#editmessagetext
 */
class EditMessageText
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection on behalf of which the message to be edited was sent */
    private string $business_connection_id;
    /** @var int|string Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>. */
    private int|string $chat_id;
    /** @var int Required if <em>inline_message_id</em> is not specified. Identifier of the message to edit. */
    private int $message_id;
    /** @var string Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message. */
    private string $inline_message_id;
    /** @var string New text of the message, 1-4096 characters after entity parsing; required if <em>rich_message</em> isn't specified */
    private string $text;
    /** @var string Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em> */
    private string $entities;
    /** @var LinkPreviewOptions Link preview generation options for the message */
    private LinkPreviewOptions $link_preview_options;
    /** @var InputRichMessage New rich content of the message; required if <em>text</em> isn't specified. Direct upload of new files and explicit upload of files by a URL isn't supported when an inline message is edited. */
    private InputRichMessage $rich_message;
    /** @var InlineKeyboardMarkup A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a> */
    private InlineKeyboardMarkup $reply_markup;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->_request = $request;
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
