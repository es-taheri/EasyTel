<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a link to a sticker stored on the Telegram servers. By default, this sticker will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the sticker.
 * @method self type(string $value) Type of the result, must be <em>sticker</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 bytes
 * @method self sticker_file_id(string $value) A valid file identifier of the sticker
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self input_message_content(InputMessageContent $value) <em>Optional</em>. Content of the message to be sent instead of the sticker
 */
class InlineQueryResultCachedSticker
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $id;
    /** @var string */
    public string $sticker_file_id;
    /** @var InlineKeyboardMarkup */
    public InlineKeyboardMarkup $reply_markup;
    /** @var InputMessageContent */
    public InputMessageContent $input_message_content;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a link to a sticker stored on the Telegram servers. By default, this sticker will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the sticker.
     * @param string|null $type Type of the result, must be <em>sticker</em>
     * @param string|null $id Unique identifier for this result, 1-64 bytes
     * @param string|null $sticker_file_id A valid file identifier of the sticker
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param InputMessageContent|null $input_message_content <em>Optional</em>. Content of the message to be sent instead of the sticker
     */
    public static function make(string $type = null, string $id = null, string $sticker_file_id = null, InlineKeyboardMarkup $reply_markup = null, InputMessageContent $input_message_content = null): self
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