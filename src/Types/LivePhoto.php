<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a live photo.
 * @method self photo(PhotoSize[] $value) <em>Optional</em>. Available sizes of the corresponding static photo
 * @method self file_id(string $value) Identifier for the video file which can be used to download or reuse the file
 * @method self file_unique_id(string $value) Unique identifier for the video file which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method self width(int $value) Video width as defined by the sender
 * @method self height(int $value) Video height as defined by the sender
 * @method self duration(int $value) Duration of the video in seconds as defined by the sender
 * @method self mime_type(string $value) <em>Optional</em>. MIME type of the file as defined by the sender
 * @method self file_size(int $value) <em>Optional</em>. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 */
class LivePhoto
{
    /** @var PhotoSize[] */
    public array $photo;
    /** @var string */
    public string $file_id;
    /** @var string */
    public string $file_unique_id;
    /** @var int */
    public int $width;
    /** @var int */
    public int $height;
    /** @var int */
    public int $duration;
    /** @var string */
    public string $mime_type;
    /** @var int */
    public int $file_size;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a live photo.
     * @param PhotoSize[]|null $photo <em>Optional</em>. Available sizes of the corresponding static photo
     * @param string|null $file_id Identifier for the video file which can be used to download or reuse the file
     * @param string|null $file_unique_id Unique identifier for the video file which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     * @param int|null $width Video width as defined by the sender
     * @param int|null $height Video height as defined by the sender
     * @param int|null $duration Duration of the video in seconds as defined by the sender
     * @param string|null $mime_type <em>Optional</em>. MIME type of the file as defined by the sender
     * @param int|null $file_size <em>Optional</em>. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
     */
    public static function make(array $photo = null, string $file_id = null, string $file_unique_id = null, int $width = null, int $height = null, int $duration = null, string $mime_type = null, int $file_size = null): self
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