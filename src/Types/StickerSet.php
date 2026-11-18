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
 * @method self stickers(Sticker[] $value) List of all set stickers
 * @method self thumbnail(PhotoSize $value) <em>Optional</em>. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
 */
class StickerSet
{
    /** @var string */
    public string $name;
    /** @var string */
    public string $title;
    /** @var string */
    public string $sticker_type;
    /** @var Sticker[] */
    public array $stickers;
    /** @var PhotoSize */
    public PhotoSize $thumbnail;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a sticker set.
     * @param string|null $name Sticker set name
     * @param string|null $title Sticker set title
     * @param string|null $sticker_type Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji”
     * @param Sticker[]|null $stickers List of all set stickers
     * @param PhotoSize|null $thumbnail <em>Optional</em>. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
     */
    public static function make(string $name = null, string $title = null, string $sticker_type = null, array $stickers = null, PhotoSize $thumbnail = null): self
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