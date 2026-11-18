<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about the quoted part of a message that is replied to by the given message.
 * @method self text(string $value) Text of the quoted part of a message that is replied to by the given message; may be empty if the quote was chosen from a rich message
 * @method self entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the quote. Currently, only <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities are kept in quotes.
 * @method self rich_texts(RichText[] $value) <em>Optional</em>. Rich text of the quote if it was chosen from a <a href="https://core.telegram.org/bots/api#richmessage">rich message</a>. May contain only plain text, <a href="https://core.telegram.org/bots/api#richtextbold">RichTextBold</a>, <a href="https://core.telegram.org/bots/api#richtextitalic">RichTextItalic</a>, <a href="https://core.telegram.org/bots/api#richtextunderline">RichTextUnderline</a>, <a href="https://core.telegram.org/bots/api#richtextstrikethrough">RichTextStrikethrough</a>, <a href="https://core.telegram.org/bots/api#richtextspoiler">RichTextSpoiler</a>, <a href="https://core.telegram.org/bots/api#richtextsubscript">RichTextSubscript</a>, <a href="https://core.telegram.org/bots/api#richtextsuperscript">RichTextSuperscript</a>, <a href="https://core.telegram.org/bots/api#richtextdatetime">RichTextDateTime</a>, <a href="https://core.telegram.org/bots/api#richtextcustomemoji">RichTextCustomEmoji</a>, <a href="https://core.telegram.org/bots/api#richtextmathematicalexpression">RichTextMathematicalExpression</a>, and arrays of them.
 * @method self position(int $value) Approximate quote position in the original message in UTF-16 code units as specified by the sender. For quotes from rich messages, it is the position in the text formed by joining plain texts of all quotable texts of the message, separated by line feeds.
 * @method self is_manual(bool $value) <em>Optional</em>. <em>True</em>, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
 */
class TextQuote
{
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $entities;
    /** @var RichText[] */
    public array $rich_texts;
    /** @var int */
    public int $position;
    /** @var bool */
    public bool $is_manual;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about the quoted part of a message that is replied to by the given message.
     * @param string|null $text Text of the quoted part of a message that is replied to by the given message; may be empty if the quote was chosen from a rich message
     * @param MessageEntity[]|null $entities <em>Optional</em>. Special entities that appear in the quote. Currently, only <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities are kept in quotes.
     * @param RichText[]|null $rich_texts <em>Optional</em>. Rich text of the quote if it was chosen from a <a href="https://core.telegram.org/bots/api#richmessage">rich message</a>. May contain only plain text, <a href="https://core.telegram.org/bots/api#richtextbold">RichTextBold</a>, <a href="https://core.telegram.org/bots/api#richtextitalic">RichTextItalic</a>, <a href="https://core.telegram.org/bots/api#richtextunderline">RichTextUnderline</a>, <a href="https://core.telegram.org/bots/api#richtextstrikethrough">RichTextStrikethrough</a>, <a href="https://core.telegram.org/bots/api#richtextspoiler">RichTextSpoiler</a>, <a href="https://core.telegram.org/bots/api#richtextsubscript">RichTextSubscript</a>, <a href="https://core.telegram.org/bots/api#richtextsuperscript">RichTextSuperscript</a>, <a href="https://core.telegram.org/bots/api#richtextdatetime">RichTextDateTime</a>, <a href="https://core.telegram.org/bots/api#richtextcustomemoji">RichTextCustomEmoji</a>, <a href="https://core.telegram.org/bots/api#richtextmathematicalexpression">RichTextMathematicalExpression</a>, and arrays of them.
     * @param int|null $position Approximate quote position in the original message in UTF-16 code units as specified by the sender. For quotes from rich messages, it is the position in the text formed by joining plain texts of all quotable texts of the message, separated by line feeds.
     * @param bool|null $is_manual <em>Optional</em>. <em>True</em>, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
     */
    public static function make(string $text = null, array $entities = null, array $rich_texts = null, int $position = null, bool $is_manual = null): self
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