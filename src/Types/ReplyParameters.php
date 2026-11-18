<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes reply parameters for the message that is being sent.
 * @method self message_id(int $value) <em>Optional</em>. Identifier of the message that will be replied to in the current chat, or in the chat <em>chat_id</em> if it is specified. Required if <em>ephemeral_message_id</em> isn't specified.
 * @method self chat_id(int|string $value) <em>Optional</em>. If the message to be replied to is from a different chat, unique identifier for the chat or username of the bot, supergroup, or channel in the format <code>@username</code>. Not supported for messages sent on behalf of a business account, messages from channel direct messages chats and ephemeral messages.
 * @method self ephemeral_message_id(int $value) <em>Optional</em>. Identifier of the incoming ephemeral message that will be replied to in the current chat. A reply to an ephemeral message must itself be an ephemeral message. An ephemeral message may only be replied to within 15 seconds of being sent. Required if <em>message_id</em> isn't specified.
 * @method self allow_sending_without_reply(bool $value) <em>Optional</em>. Pass <em>True</em> if the message should be sent even if the specified message to be replied to is not found. Always <em>False</em> for replies in another chat or forum topic, and sent ephemeral messages. Always <em>True</em> for messages sent on behalf of a business account.
 * @method self quote(string $value) <em>Optional</em>. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote must be an exact substring of the message to be replied to, including <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities. The message will fail to send if the quote isn't found in the original message. Ignored for ephemeral messages.
 * @method self quote_parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the quote. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self quote_entities(MessageEntity[] $value) <em>Optional</em>. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of <em>quote_parse_mode</em>.
 * @method self quote_rich_texts(RichText[] $value) <em>Optional</em>. A JSON-serialized list of quoted parts of the <a href="https://core.telegram.org/bots/api#richmessage">rich message</a> to be replied to. Must contain one element for each quoted text of the message in the order of their appearance. Each of the following is a separate quoted text: the text of a paragraph, a heading, a preformatted block, a footer, or a mathematical expression; the text and the credit of a block quotation or a pull quotation; the summary of a details block; the caption and each cell of a table; the text and the credit of a caption of a media block, a collage, a slideshow, or a map. Texts of blocks nested in lists, block quotations, details, collages, and slideshows are separate quoted texts too. The first and the last elements can contain only a part of the corresponding text; all other elements must contain the whole text. Formatting of the quoted parts must be kept, and rich texts must be nested in the same way as in the message received from the server. Mathematical expressions must be quoted entirely. Buttons in <a href="https://core.telegram.org/bots/api#richblockbuttons">RichBlockButtons</a>, anchors and dividers can't be quoted. Up to 32 quoted texts with total length up to 1024 characters can be specified. Only plain text, <a href="https://core.telegram.org/bots/api#richtextbold">RichTextBold</a>, <a href="https://core.telegram.org/bots/api#richtextitalic">RichTextItalic</a>, <a href="https://core.telegram.org/bots/api#richtextunderline">RichTextUnderline</a>, <a href="https://core.telegram.org/bots/api#richtextstrikethrough">RichTextStrikethrough</a>, <a href="https://core.telegram.org/bots/api#richtextspoiler">RichTextSpoiler</a>, <a href="https://core.telegram.org/bots/api#richtextsubscript">RichTextSubscript</a>, <a href="https://core.telegram.org/bots/api#richtextsuperscript">RichTextSuperscript</a>, <a href="https://core.telegram.org/bots/api#richtextdatetime">RichTextDateTime</a>, <a href="https://core.telegram.org/bots/api#richtextcustomemoji">RichTextCustomEmoji</a>, <a href="https://core.telegram.org/bots/api#richtextmathematicalexpression">RichTextMathematicalExpression</a>, and arrays of them are kept in the quote; other rich text types are replaced with their text. If non-empty, then <em>quote</em>, <em>quote_parse_mode</em> and <em>quote_entities</em> are ignored.
 * @method self quote_position(int $value) <em>Optional</em>. Position of the quote in the original message in UTF-16 code units. For quotes from rich messages, it is the position in the text formed by joining plain texts of all quotable texts of the message, separated by line feeds.
 * @method self checklist_task_id(int $value) <em>Optional</em>. Identifier of the specific checklist task to be replied to
 * @method self poll_option_id(string $value) <em>Optional</em>. Persistent identifier of the specific poll option to be replied to
 */
class ReplyParameters
{
    /** @var int */
    public int $message_id;
    /** @var int|string */
    public int|string $chat_id;
    /** @var int */
    public int $ephemeral_message_id;
    /** @var bool */
    public bool $allow_sending_without_reply;
    /** @var string */
    public string $quote;
    /** @var string */
    public string $quote_parse_mode;
    /** @var MessageEntity[] */
    public array $quote_entities;
    /** @var RichText[] */
    public array $quote_rich_texts;
    /** @var int */
    public int $quote_position;
    /** @var int */
    public int $checklist_task_id;
    /** @var string */
    public string $poll_option_id;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes reply parameters for the message that is being sent.
     * @param int|null $message_id <em>Optional</em>. Identifier of the message that will be replied to in the current chat, or in the chat <em>chat_id</em> if it is specified. Required if <em>ephemeral_message_id</em> isn't specified.
     * @param int|string|null $chat_id <em>Optional</em>. If the message to be replied to is from a different chat, unique identifier for the chat or username of the bot, supergroup, or channel in the format <code>@username</code>. Not supported for messages sent on behalf of a business account, messages from channel direct messages chats and ephemeral messages.
     * @param int|null $ephemeral_message_id <em>Optional</em>. Identifier of the incoming ephemeral message that will be replied to in the current chat. A reply to an ephemeral message must itself be an ephemeral message. An ephemeral message may only be replied to within 15 seconds of being sent. Required if <em>message_id</em> isn't specified.
     * @param bool|null $allow_sending_without_reply <em>Optional</em>. Pass <em>True</em> if the message should be sent even if the specified message to be replied to is not found. Always <em>False</em> for replies in another chat or forum topic, and sent ephemeral messages. Always <em>True</em> for messages sent on behalf of a business account.
     * @param string|null $quote <em>Optional</em>. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote must be an exact substring of the message to be replied to, including <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities. The message will fail to send if the quote isn't found in the original message. Ignored for ephemeral messages.
     * @param string|null $quote_parse_mode <em>Optional</em>. Mode for parsing entities in the quote. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $quote_entities <em>Optional</em>. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of <em>quote_parse_mode</em>.
     * @param RichText[]|null $quote_rich_texts <em>Optional</em>. A JSON-serialized list of quoted parts of the <a href="https://core.telegram.org/bots/api#richmessage">rich message</a> to be replied to. Must contain one element for each quoted text of the message in the order of their appearance. Each of the following is a separate quoted text: the text of a paragraph, a heading, a preformatted block, a footer, or a mathematical expression; the text and the credit of a block quotation or a pull quotation; the summary of a details block; the caption and each cell of a table; the text and the credit of a caption of a media block, a collage, a slideshow, or a map. Texts of blocks nested in lists, block quotations, details, collages, and slideshows are separate quoted texts too. The first and the last elements can contain only a part of the corresponding text; all other elements must contain the whole text. Formatting of the quoted parts must be kept, and rich texts must be nested in the same way as in the message received from the server. Mathematical expressions must be quoted entirely. Buttons in <a href="https://core.telegram.org/bots/api#richblockbuttons">RichBlockButtons</a>, anchors and dividers can't be quoted. Up to 32 quoted texts with total length up to 1024 characters can be specified. Only plain text, <a href="https://core.telegram.org/bots/api#richtextbold">RichTextBold</a>, <a href="https://core.telegram.org/bots/api#richtextitalic">RichTextItalic</a>, <a href="https://core.telegram.org/bots/api#richtextunderline">RichTextUnderline</a>, <a href="https://core.telegram.org/bots/api#richtextstrikethrough">RichTextStrikethrough</a>, <a href="https://core.telegram.org/bots/api#richtextspoiler">RichTextSpoiler</a>, <a href="https://core.telegram.org/bots/api#richtextsubscript">RichTextSubscript</a>, <a href="https://core.telegram.org/bots/api#richtextsuperscript">RichTextSuperscript</a>, <a href="https://core.telegram.org/bots/api#richtextdatetime">RichTextDateTime</a>, <a href="https://core.telegram.org/bots/api#richtextcustomemoji">RichTextCustomEmoji</a>, <a href="https://core.telegram.org/bots/api#richtextmathematicalexpression">RichTextMathematicalExpression</a>, and arrays of them are kept in the quote; other rich text types are replaced with their text. If non-empty, then <em>quote</em>, <em>quote_parse_mode</em> and <em>quote_entities</em> are ignored.
     * @param int|null $quote_position <em>Optional</em>. Position of the quote in the original message in UTF-16 code units. For quotes from rich messages, it is the position in the text formed by joining plain texts of all quotable texts of the message, separated by line feeds.
     * @param int|null $checklist_task_id <em>Optional</em>. Identifier of the specific checklist task to be replied to
     * @param string|null $poll_option_id <em>Optional</em>. Persistent identifier of the specific poll option to be replied to
     */
    public static function make(int $message_id = null, int|string $chat_id = null, int $ephemeral_message_id = null, bool $allow_sending_without_reply = null, string $quote = null, string $quote_parse_mode = null, array $quote_entities = null, array $quote_rich_texts = null, int $quote_position = null, int $checklist_task_id = null, string $poll_option_id = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => $v !== null);
        return new self($updates);
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    protected function _output(int $type = Telegram::OUTPUT_JSON): object|array|string
    {
        $output = [];
        $r = new \ReflectionClass(static::class);
        $union = $r->hasConstant('_UNION_BASE');

        $normalize = function ($value) use (&$normalize) {
            if (is_object($value) && method_exists($value, '_output'))
                return (fn() => $this->_output(Telegram::OUTPUT_ARRAY))->bindTo($value, $value)();
            if (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item)
                    $items[$key] = $normalize($item);
                return $items;
            }
            return $value;
        };

        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (!isset($this->{$name}))
                continue;

            $raw = $property->getValue($this);
            $value = $normalize($raw);

            // A union base variant property represents the payload itself, so
            // its fields replace the variant key.
            if ($union && is_object($raw) && is_array($value)
                && $name === strtolower((new \ReflectionClass($raw))->getShortName())):
                $output += $value;
                continue;
            endif;

            $output[$name] = $value;
        endforeach;
        return Statics::output($output, $type);
    }
}