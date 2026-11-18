<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a video to be sent.
 * @method self type(string $value) Type of the media, must be <em>video</em>
 * @method self media(string $value) File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method self thumbnail(string $value) <em>Optional</em>. Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method self cover(string $value) <em>Optional</em>. Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method self start_timestamp(int $value) <em>Optional</em>. Start timestamp for the video in the message
 * @method self caption(string $value) <em>Optional</em>. Caption of the video to be sent, 0-1024 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the video caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self caption_entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method self show_caption_above_media(bool $value) <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the message media
 * @method self width(int $value) <em>Optional</em>. Video width
 * @method self height(int $value) <em>Optional</em>. Video height
 * @method self duration(int $value) <em>Optional</em>. Video duration in seconds
 * @method self supports_streaming(bool $value) <em>Optional</em>. Pass <em>True</em> if the uploaded video is suitable for streaming
 * @method self has_spoiler(bool $value) <em>Optional</em>. Pass <em>True</em> if the video needs to be covered with a spoiler animation
 */
class InputMediaVideo
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $media;
    /** @var string */
    public string $thumbnail;
    /** @var string */
    public string $cover;
    /** @var int */
    public int $start_timestamp;
    /** @var string */
    public string $caption;
    /** @var string */
    public string $parse_mode;
    /** @var MessageEntity[] */
    public array $caption_entities;
    /** @var bool */
    public bool $show_caption_above_media;
    /** @var int */
    public int $width;
    /** @var int */
    public int $height;
    /** @var int */
    public int $duration;
    /** @var bool */
    public bool $supports_streaming;
    /** @var bool */
    public bool $has_spoiler;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a video to be sent.
     * @param string|null $type Type of the media, must be <em>video</em>
     * @param string|null $media File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @param string|null $thumbnail <em>Optional</em>. Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @param string|null $cover <em>Optional</em>. Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @param int|null $start_timestamp <em>Optional</em>. Start timestamp for the video in the message
     * @param string|null $caption <em>Optional</em>. Caption of the video to be sent, 0-1024 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the video caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $caption_entities <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param bool|null $show_caption_above_media <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the message media
     * @param int|null $width <em>Optional</em>. Video width
     * @param int|null $height <em>Optional</em>. Video height
     * @param int|null $duration <em>Optional</em>. Video duration in seconds
     * @param bool|null $supports_streaming <em>Optional</em>. Pass <em>True</em> if the uploaded video is suitable for streaming
     * @param bool|null $has_spoiler <em>Optional</em>. Pass <em>True</em> if the video needs to be covered with a spoiler animation
     */
    public static function make(string $type = null, string $media = null, string $thumbnail = null, string $cover = null, int $start_timestamp = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $show_caption_above_media = null, int $width = null, int $height = null, int $duration = null, bool $supports_streaming = null, bool $has_spoiler = null): self
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