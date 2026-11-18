<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a link to an animated GIF file stored on the Telegram servers. By default, this animated GIF file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with specified content instead of the animation.
 * @method self type(string $value) Type of the result, must be <em>gif</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 bytes
 * @method self gif_file_id(string $value) A valid file identifier for the GIF file
 * @method self title(string $value) <em>Optional</em>. Title for the result
 * @method self caption(string $value) <em>Optional</em>. Caption of the GIF file to be sent, 0-1024 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self caption_entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method self show_caption_above_media(bool $value) <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the message media
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self input_message_content(InputMessageContent $value) <em>Optional</em>. Content of the message to be sent instead of the GIF animation
 */
class InlineQueryResultCachedGif
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $id;
    /** @var string */
    public string $gif_file_id;
    /** @var string */
    public string $title;
    /** @var string */
    public string $caption;
    /** @var string */
    public string $parse_mode;
    /** @var MessageEntity[] */
    public array $caption_entities;
    /** @var bool */
    public bool $show_caption_above_media;
    /** @var InlineKeyboardMarkup */
    public InlineKeyboardMarkup $reply_markup;
    /** @var InputMessageContent */
    public InputMessageContent $input_message_content;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a link to an animated GIF file stored on the Telegram servers. By default, this animated GIF file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with specified content instead of the animation.
     * @param string|null $type Type of the result, must be <em>gif</em>
     * @param string|null $id Unique identifier for this result, 1-64 bytes
     * @param string|null $gif_file_id A valid file identifier for the GIF file
     * @param string|null $title <em>Optional</em>. Title for the result
     * @param string|null $caption <em>Optional</em>. Caption of the GIF file to be sent, 0-1024 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $caption_entities <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param bool|null $show_caption_above_media <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the message media
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param InputMessageContent|null $input_message_content <em>Optional</em>. Content of the message to be sent instead of the GIF animation
     */
    public static function make(string $type = null, string $id = null, string $gif_file_id = null, string $title = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $show_caption_above_media = null, InlineKeyboardMarkup $reply_markup = null, InputMessageContent $input_message_content = null): self
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