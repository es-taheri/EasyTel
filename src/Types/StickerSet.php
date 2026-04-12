<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a sticker set.
 * @method self name(string $value) Sticker set name
 * @method self title(string $value) Sticker set title
 * @method self sticker_type(string $value) Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji”
 * @method self stickers(array $value) List of all set stickers
 * @method self thumbnail(PhotoSize $value) <em>Optional</em>. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
 */
class StickerSet
{
    public string $name;
    public string $title;
    public string $sticker_type;
    public array $stickers;
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
     * This object represents a sticker set.
     * @param string|null $name Sticker set name
     * @param string|null $title Sticker set title
     * @param string|null $sticker_type Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji”
     * @param array|null $stickers List of all set stickers
     * @param PhotoSize|null $thumbnail <em>Optional</em>. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
     */
    public static function make(string $name = null, string $title = null, string $sticker_type = null, array $stickers = null, PhotoSize $thumbnail = null): self
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