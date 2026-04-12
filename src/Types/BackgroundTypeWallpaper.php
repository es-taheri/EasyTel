<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The background is a wallpaper in the JPEG format.
 * @method self type(string $value) Type of the background, always “wallpaper”
 * @method self document(Document $value) Document with the wallpaper
 * @method self dark_theme_dimming(int $value) Dimming of the background in dark themes, as a percentage; 0-100
 * @method self is_blurred(True $value) <em>Optional</em>. <em>True</em>, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
 * @method self is_moving(True $value) <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
 */
class BackgroundTypeWallpaper
{
    public string $type;
    public Document $document;
    public int $dark_theme_dimming;
    public True $is_blurred;
    public True $is_moving;

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
        if (isset($update['document'])) $this->document = new Document($update['document']);
    }

    /**
     * The background is a wallpaper in the JPEG format.
     * @param string|null $type Type of the background, always “wallpaper”
     * @param Document|null $document Document with the wallpaper
     * @param int|null $dark_theme_dimming Dimming of the background in dark themes, as a percentage; 0-100
     * @param True|null $is_blurred <em>Optional</em>. <em>True</em>, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
     * @param True|null $is_moving <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
     */
    public static function make(string $type = null, Document $document = null, int $dark_theme_dimming = null, True $is_blurred = null, True $is_moving = null): self
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