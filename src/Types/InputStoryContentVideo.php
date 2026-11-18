<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a video to post as a story.
 * @method self type(string $value) Type of the content, must be <em>video</em>
 * @method self video(string $value) The video to post as a story. The video must be of the size 720x1280, streamable, encoded with H.265 codec, with key frames added each second in the MPEG4 format, and must not exceed 30 MB. The video can't be reused and can only be uploaded as a new file, so you can pass “attach://<file_attach_name>” if the video was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method self duration(float $value) <em>Optional</em>. Precise duration of the video in seconds; 0-60
 * @method self cover_frame_timestamp(float $value) <em>Optional</em>. Timestamp in seconds of the frame that will be used as the static cover for the story. Defaults to 0.0.
 * @method self is_animation(bool $value) <em>Optional</em>. Pass <em>True</em> if the video has no sound
 */
class InputStoryContentVideo
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $video;
    /** @var float */
    public float $duration;
    /** @var float */
    public float $cover_frame_timestamp;
    /** @var bool */
    public bool $is_animation;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a video to post as a story.
     * @param string|null $type Type of the content, must be <em>video</em>
     * @param string|null $video The video to post as a story. The video must be of the size 720x1280, streamable, encoded with H.265 codec, with key frames added each second in the MPEG4 format, and must not exceed 30 MB. The video can't be reused and can only be uploaded as a new file, so you can pass “attach://<file_attach_name>” if the video was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @param float|null $duration <em>Optional</em>. Precise duration of the video in seconds; 0-60
     * @param float|null $cover_frame_timestamp <em>Optional</em>. Timestamp in seconds of the frame that will be used as the static cover for the story. Defaults to 0.0.
     * @param bool|null $is_animation <em>Optional</em>. Pass <em>True</em> if the video has no sound
     */
    public static function make(string $type = null, string $video = null, float $duration = null, float $cover_frame_timestamp = null, bool $is_animation = null): self
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