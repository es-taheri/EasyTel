<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an audio file to be treated as music by the Telegram clients.
 * @method self file_id(string $value) Identifier for this file, which can be used to download or reuse the file
 * @method self file_unique_id(string $value) Unique identifier for this file, which is supposed to be the same over time and for different bots. Can&#39;t be used to download or reuse the file.
 * @method self duration(int $value) Duration of the audio in seconds as defined by the sender
 * @method self performer(string $value) <em>Optional</em>. Performer of the audio as defined by the sender or by audio tags
 * @method self title(string $value) <em>Optional</em>. Title of the audio as defined by the sender or by audio tags
 * @method self file_name(string $value) <em>Optional</em>. Original filename as defined by the sender
 * @method self mime_type(string $value) <em>Optional</em>. MIME type of the file as defined by the sender
 * @method self file_size(int $value) <em>Optional</em>. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 * @method self thumbnail(PhotoSize $value) <em>Optional</em>. Thumbnail of the album cover to which the music file belongs
 */
class Audio
{
    public string $file_id;
    public string $file_unique_id;
    public int $duration;
    public string $performer;
    public string $title;
    public string $file_name;
    public string $mime_type;
    public int $file_size;
    public PhotoSize $thumbnail;

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
        if (isset($update['thumbnail'])) $this->thumbnail = new PhotoSize($update['thumbnail']);
    }

    /**
     * This object represents an audio file to be treated as music by the Telegram clients.
     * @param string|null $file_id Identifier for this file, which can be used to download or reuse the file
     * @param string|null $file_unique_id Unique identifier for this file, which is supposed to be the same over time and for different bots. Can&#39;t be used to download or reuse the file.
     * @param int|null $duration Duration of the audio in seconds as defined by the sender
     * @param string|null $performer <em>Optional</em>. Performer of the audio as defined by the sender or by audio tags
     * @param string|null $title <em>Optional</em>. Title of the audio as defined by the sender or by audio tags
     * @param string|null $file_name <em>Optional</em>. Original filename as defined by the sender
     * @param string|null $mime_type <em>Optional</em>. MIME type of the file as defined by the sender
     * @param int|null $file_size <em>Optional</em>. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
     * @param PhotoSize|null $thumbnail <em>Optional</em>. Thumbnail of the album cover to which the music file belongs
     */
    public static function make(string $file_id = null, string $file_unique_id = null, int $duration = null, string $performer = null, string $title = null, string $file_name = null, string $mime_type = null, int $file_size = null, PhotoSize $thumbnail = null): self
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