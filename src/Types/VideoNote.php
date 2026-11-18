<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a <a href="https://telegram.org/blog/video-messages-and-telescope">video message</a>.
 * @method self file_id(string $value) Identifier for this file, which can be used to download or reuse the file
 * @method self file_unique_id(string $value) Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method self length(int $value) Video width and height (diameter of the video message) as defined by the sender
 * @method self duration(int $value) Duration of the video in seconds as defined by the sender
 * @method self thumbnail(PhotoSize $value) <em>Optional</em>. Video thumbnail
 * @method self file_size(int $value) <em>Optional</em>. File size in bytes
 */
class VideoNote
{
    /** @var string */
    public string $file_id;
    /** @var string */
    public string $file_unique_id;
    /** @var int */
    public int $length;
    /** @var int */
    public int $duration;
    /** @var PhotoSize */
    public PhotoSize $thumbnail;
    /** @var int */
    public int $file_size;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a <a href="https://telegram.org/blog/video-messages-and-telescope">video message</a>.
     * @param string|null $file_id Identifier for this file, which can be used to download or reuse the file
     * @param string|null $file_unique_id Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     * @param int|null $length Video width and height (diameter of the video message) as defined by the sender
     * @param int|null $duration Duration of the video in seconds as defined by the sender
     * @param PhotoSize|null $thumbnail <em>Optional</em>. Video thumbnail
     * @param int|null $file_size <em>Optional</em>. File size in bytes
     */
    public static function make(string $file_id = null, string $file_unique_id = null, int $length = null, int $duration = null, PhotoSize $thumbnail = null, int $file_size = null): self
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