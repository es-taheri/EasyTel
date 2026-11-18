<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a link to an article or web page.
 * @method self type(string $value) Type of the result, must be <em>article</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 Bytes
 * @method self title(string $value) Title of the result
 * @method self input_message_content(InputMessageContent $value) Content of the message to be sent
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self url(string $value) <em>Optional</em>. URL of the result
 * @method self description(string $value) <em>Optional</em>. Short description of the result
 * @method self thumbnail_url(string $value) <em>Optional</em>. Url of the thumbnail for the result
 * @method self thumbnail_width(int $value) <em>Optional</em>. Thumbnail width
 * @method self thumbnail_height(int $value) <em>Optional</em>. Thumbnail height
 */
class InlineQueryResultArticle
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $id;
    /** @var string */
    public string $title;
    /** @var InputMessageContent */
    public InputMessageContent $input_message_content;
    /** @var InlineKeyboardMarkup */
    public InlineKeyboardMarkup $reply_markup;
    /** @var string */
    public string $url;
    /** @var string */
    public string $description;
    /** @var string */
    public string $thumbnail_url;
    /** @var int */
    public int $thumbnail_width;
    /** @var int */
    public int $thumbnail_height;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a link to an article or web page.
     * @param string|null $type Type of the result, must be <em>article</em>
     * @param string|null $id Unique identifier for this result, 1-64 Bytes
     * @param string|null $title Title of the result
     * @param InputMessageContent|null $input_message_content Content of the message to be sent
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param string|null $url <em>Optional</em>. URL of the result
     * @param string|null $description <em>Optional</em>. Short description of the result
     * @param string|null $thumbnail_url <em>Optional</em>. Url of the thumbnail for the result
     * @param int|null $thumbnail_width <em>Optional</em>. Thumbnail width
     * @param int|null $thumbnail_height <em>Optional</em>. Thumbnail height
     */
    public static function make(string $type = null, string $id = null, string $title = null, InputMessageContent $input_message_content = null, InlineKeyboardMarkup $reply_markup = null, string $url = null, string $description = null, string $thumbnail_url = null, int $thumbnail_width = null, int $thumbnail_height = null): self
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