<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a link to a video animation (H.264/MPEG-4 AVC video without sound). By default, this animated MPEG-4 file will be sent by the user with optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the animation.
 * @method self type(string $value) Type of the result, must be <em>mpeg4_gif</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 bytes
 * @method self mpeg4_url(string $value) A valid URL for the MPEG4 file
 * @method self mpeg4_width(int $value) <em>Optional</em>. Video width
 * @method self mpeg4_height(int $value) <em>Optional</em>. Video height
 * @method self mpeg4_duration(int $value) <em>Optional</em>. Video duration in seconds
 * @method self thumbnail_url(string $value) URL of the static (JPEG or GIF) or animated (MPEG4) thumbnail for the result
 * @method self thumbnail_mime_type(string $value) <em>Optional</em>. MIME type of the thumbnail, must be one of “image/jpeg”, “image/gif”, or “video/mp4”. Defaults to “image/jpeg”.
 * @method self title(string $value) <em>Optional</em>. Title for the result
 * @method self caption(string $value) <em>Optional</em>. Caption of the MPEG-4 file to be sent, 0-1024 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self caption_entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method self show_caption_above_media(bool $value) <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the message media
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self input_message_content(InputMessageContent $value) <em>Optional</em>. Content of the message to be sent instead of the video animation
 */
class InlineQueryResultMpeg4Gif
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $id;
    /** @var string */
    public string $mpeg4_url;
    /** @var int */
    public int $mpeg4_width;
    /** @var int */
    public int $mpeg4_height;
    /** @var int */
    public int $mpeg4_duration;
    /** @var string */
    public string $thumbnail_url;
    /** @var string */
    public string $thumbnail_mime_type;
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
     * Represents a link to a video animation (H.264/MPEG-4 AVC video without sound). By default, this animated MPEG-4 file will be sent by the user with optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the animation.
     * @param string|null $type Type of the result, must be <em>mpeg4_gif</em>
     * @param string|null $id Unique identifier for this result, 1-64 bytes
     * @param string|null $mpeg4_url A valid URL for the MPEG4 file
     * @param int|null $mpeg4_width <em>Optional</em>. Video width
     * @param int|null $mpeg4_height <em>Optional</em>. Video height
     * @param int|null $mpeg4_duration <em>Optional</em>. Video duration in seconds
     * @param string|null $thumbnail_url URL of the static (JPEG or GIF) or animated (MPEG4) thumbnail for the result
     * @param string|null $thumbnail_mime_type <em>Optional</em>. MIME type of the thumbnail, must be one of “image/jpeg”, “image/gif”, or “video/mp4”. Defaults to “image/jpeg”.
     * @param string|null $title <em>Optional</em>. Title for the result
     * @param string|null $caption <em>Optional</em>. Caption of the MPEG-4 file to be sent, 0-1024 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $caption_entities <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param bool|null $show_caption_above_media <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the message media
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param InputMessageContent|null $input_message_content <em>Optional</em>. Content of the message to be sent instead of the video animation
     */
    public static function make(string $type = null, string $id = null, string $mpeg4_url = null, int $mpeg4_width = null, int $mpeg4_height = null, int $mpeg4_duration = null, string $thumbnail_url = null, string $thumbnail_mime_type = null, string $title = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $show_caption_above_media = null, InlineKeyboardMarkup $reply_markup = null, InputMessageContent $input_message_content = null): self
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