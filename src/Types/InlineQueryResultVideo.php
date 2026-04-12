<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a link to a page containing an embedded video player or a video file. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the video.
 * @method self type(string $value) Type of the result, must be <em>video</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 bytes
 * @method self video_url(string $value) A valid URL for the embedded video player or video file
 * @method self mime_type(string $value) MIME type of the content of the video URL, “text/html” or “video/mp4”
 * @method self thumbnail_url(string $value) URL of the thumbnail (JPEG only) for the video
 * @method self title(string $value) Title for the result
 * @method self caption(string $value) <em>Optional</em>. Caption of the video to be sent, 0-1024 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the video caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self caption_entities(array $value) <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method self show_caption_above_media(bool $value) <em>Optional</em>. Pass <em>True</em>, if the caption must be shown above the message media
 * @method self video_width(int $value) <em>Optional</em>. Video width
 * @method self video_height(int $value) <em>Optional</em>. Video height
 * @method self video_duration(int $value) <em>Optional</em>. Video duration in seconds
 * @method self description(string $value) <em>Optional</em>. Short description of the result
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self input_message_content(InputMessageContent $value) <em>Optional</em>. Content of the message to be sent instead of the video. This field is <strong>required</strong> if InlineQueryResultVideo is used to send an HTML-page as a result (e.g., a YouTube video).
 */
class InlineQueryResultVideo
{
    public string $type;
    public string $id;
    public string $video_url;
    public string $mime_type;
    public string $thumbnail_url;
    public string $title;
    public string $caption;
    public string $parse_mode;
    public array $caption_entities;
    public bool $show_caption_above_media;
    public int $video_width;
    public int $video_height;
    public int $video_duration;
    public string $description;
    public InlineKeyboardMarkup $reply_markup;
    public InputMessageContent $input_message_content;

    public function __construct(array $update = [])
    {
        $objects = array_keys($update);
        $r = new \ReflectionClass(static::class);
        foreach ($objects as $object):
            if ($r->hasProperty($object)):
                $prop = $r->getProperty($object);
                $type = $prop->getType();
                if (in_array(strtolower(trim($type)), ['string', 'true', 'false', 'bool', 'int', 'float', 'array', 'mixed']) || str_contains($type, '|'))
                    $this->{$object} = $update[$object];
            endif;
        endforeach;
        if (isset($update['reply_markup'])) $this->reply_markup = new InlineKeyboardMarkup($update['reply_markup']);
        if (isset($update['input_message_content'])) $this->input_message_content = new InputMessageContent($update['input_message_content']);
    }

    /**
     * Represents a link to a page containing an embedded video player or a video file. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the video.
     * @param string|null $type Type of the result, must be <em>video</em>
     * @param string|null $id Unique identifier for this result, 1-64 bytes
     * @param string|null $video_url A valid URL for the embedded video player or video file
     * @param string|null $mime_type MIME type of the content of the video URL, “text/html” or “video/mp4”
     * @param string|null $thumbnail_url URL of the thumbnail (JPEG only) for the video
     * @param string|null $title Title for the result
     * @param string|null $caption <em>Optional</em>. Caption of the video to be sent, 0-1024 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the video caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param array|null $caption_entities <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param bool|null $show_caption_above_media <em>Optional</em>. Pass <em>True</em>, if the caption must be shown above the message media
     * @param int|null $video_width <em>Optional</em>. Video width
     * @param int|null $video_height <em>Optional</em>. Video height
     * @param int|null $video_duration <em>Optional</em>. Video duration in seconds
     * @param string|null $description <em>Optional</em>. Short description of the result
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param InputMessageContent|null $input_message_content <em>Optional</em>. Content of the message to be sent instead of the video. This field is <strong>required</strong> if InlineQueryResultVideo is used to send an HTML-page as a result (e.g., a YouTube video).
     */
    public static function make(string $type = null, string $id = null, string $video_url = null, string $mime_type = null, string $thumbnail_url = null, string $title = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $show_caption_above_media = null, int $video_width = null, int $video_height = null, int $video_duration = null, string $description = null, InlineKeyboardMarkup $reply_markup = null, InputMessageContent $input_message_content = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => isset($v));
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
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (isset($this->{$name})):
                $value = $property->getValue($this);
                $property_type = $property->getType();
                if ($property_type == 'object')
                    $output[$name] = (fn() => ($this->_output($type)))->bindTo($value, $value)();
                else
                    $output[$name] = $value;
            endif;
        endforeach;
        return Statics::output($output, $type);
    }
}