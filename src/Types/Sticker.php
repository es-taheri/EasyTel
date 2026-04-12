<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a sticker.
 * @method self file_id(string $value) Identifier for this file, which can be used to download or reuse the file
 * @method self file_unique_id(string $value) Unique identifier for this file, which is supposed to be the same over time and for different bots. Can&#39;t be used to download or reuse the file.
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
 * @method self needs_repainting(True $value) <em>Optional</em>. <em>True</em>, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
 * @method self file_size(int $value) <em>Optional</em>. File size in bytes
 */
class Sticker
{
    public string $file_id;
    public string $file_unique_id;
    public string $type;
    public int $width;
    public int $height;
    public bool $is_animated;
    public bool $is_video;
    public PhotoSize $thumbnail;
    public string $emoji;
    public string $set_name;
    public File $premium_animation;
    public MaskPosition $mask_position;
    public string $custom_emoji_id;
    public True $needs_repainting;
    public int $file_size;

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
        if (isset($update['premium_animation'])) $this->premium_animation = new File($update['premium_animation']);
        if (isset($update['mask_position'])) $this->mask_position = new MaskPosition($update['mask_position']);
    }

    /**
     * This object represents a sticker.
     * @param string|null $file_id Identifier for this file, which can be used to download or reuse the file
     * @param string|null $file_unique_id Unique identifier for this file, which is supposed to be the same over time and for different bots. Can&#39;t be used to download or reuse the file.
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
     * @param True|null $needs_repainting <em>Optional</em>. <em>True</em>, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
     * @param int|null $file_size <em>Optional</em>. File size in bytes
     */
    public static function make(string $file_id = null, string $file_unique_id = null, string $type = null, int $width = null, int $height = null, bool $is_animated = null, bool $is_video = null, PhotoSize $thumbnail = null, string $emoji = null, string $set_name = null, File $premium_animation = null, MaskPosition $mask_position = null, string $custom_emoji_id = null, True $needs_repainting = null, int $file_size = null): self
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