<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a sticker.
 * @method self file_id(string $value) Identifier for this file, which can be used to download or reuse the file
 * @method self file_unique_id(string $value) Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method self type(string $value) Type of the sticker, currently one of “regular”, “mask”, “custom_emoji”. The type of the sticker is independent from its format, which is determined by the fields <em>is_animated</em> and <em>is_video</em>.
 * @method self width(int $value) Sticker width
 * @method self height(int $value) Sticker height
 * @method self is_animated(bool $value) <em>True</em>, if the sticker is <a href="https://telegram.org/blog/animated-stickers">animated</a>
 * @method self is_video(bool $value) <em>True</em>, if the sticker is a <a href="https://telegram.org/blog/video-stickers-better-reactions">video sticker</a>
 * @method self thumbnail(PhotoSize $value) <em>Optional</em>. Sticker thumbnail in the .WEBP or .JPG format
 * @method self emoji(string $value) <em>Optional</em>. Emoji associated with the sticker
 * @method self set_name(string $value) <em>Optional</em>. Name of the sticker set to which the sticker belongs
 * @method self premium_animation(File $value) <em>Optional</em>. For premium regular stickers, premium animation for the sticker
 * @method self mask_position(MaskPosition $value) <em>Optional</em>. For mask stickers, the position where the mask should be placed
 * @method self custom_emoji_id(string $value) <em>Optional</em>. For custom emoji stickers, unique identifier of the custom emoji
 * @method self needs_repainting(bool $value) <em>Optional</em>. <em>True</em>, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
 * @method self file_size(int $value) <em>Optional</em>. File size in bytes
 */
class Sticker
{
    /** @var string */
    public string $file_id;
    /** @var string */
    public string $file_unique_id;
    /** @var string */
    public string $type;
    /** @var int */
    public int $width;
    /** @var int */
    public int $height;
    /** @var bool */
    public bool $is_animated;
    /** @var bool */
    public bool $is_video;
    /** @var PhotoSize */
    public PhotoSize $thumbnail;
    /** @var string */
    public string $emoji;
    /** @var string */
    public string $set_name;
    /** @var File */
    public File $premium_animation;
    /** @var MaskPosition */
    public MaskPosition $mask_position;
    /** @var string */
    public string $custom_emoji_id;
    /** @var bool */
    public bool $needs_repainting;
    /** @var int */
    public int $file_size;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a sticker.
     * @param string|null $file_id Identifier for this file, which can be used to download or reuse the file
     * @param string|null $file_unique_id Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
     * @param string|null $type Type of the sticker, currently one of “regular”, “mask”, “custom_emoji”. The type of the sticker is independent from its format, which is determined by the fields <em>is_animated</em> and <em>is_video</em>.
     * @param int|null $width Sticker width
     * @param int|null $height Sticker height
     * @param bool|null $is_animated <em>True</em>, if the sticker is <a href="https://telegram.org/blog/animated-stickers">animated</a>
     * @param bool|null $is_video <em>True</em>, if the sticker is a <a href="https://telegram.org/blog/video-stickers-better-reactions">video sticker</a>
     * @param PhotoSize|null $thumbnail <em>Optional</em>. Sticker thumbnail in the .WEBP or .JPG format
     * @param string|null $emoji <em>Optional</em>. Emoji associated with the sticker
     * @param string|null $set_name <em>Optional</em>. Name of the sticker set to which the sticker belongs
     * @param File|null $premium_animation <em>Optional</em>. For premium regular stickers, premium animation for the sticker
     * @param MaskPosition|null $mask_position <em>Optional</em>. For mask stickers, the position where the mask should be placed
     * @param string|null $custom_emoji_id <em>Optional</em>. For custom emoji stickers, unique identifier of the custom emoji
     * @param bool|null $needs_repainting <em>Optional</em>. <em>True</em>, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
     * @param int|null $file_size <em>Optional</em>. File size in bytes
     */
    public static function make(string $file_id = null, string $file_unique_id = null, string $type = null, int $width = null, int $height = null, bool $is_animated = null, bool $is_video = null, PhotoSize $thumbnail = null, string $emoji = null, string $set_name = null, File $premium_animation = null, MaskPosition $mask_position = null, string $custom_emoji_id = null, bool $needs_repainting = null, int $file_size = null): self
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