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
 * @method self is_blurred(bool $value) <em>Optional</em>. <em>True</em>, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
 * @method self is_moving(bool $value) <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
 */
class BackgroundTypeWallpaper
{
    /** @var string */
    public string $type;
    /** @var Document */
    public Document $document;
    /** @var int */
    public int $dark_theme_dimming;
    /** @var bool */
    public bool $is_blurred;
    /** @var bool */
    public bool $is_moving;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The background is a wallpaper in the JPEG format.
     * @param string|null $type Type of the background, always “wallpaper”
     * @param Document|null $document Document with the wallpaper
     * @param int|null $dark_theme_dimming Dimming of the background in dark themes, as a percentage; 0-100
     * @param bool|null $is_blurred <em>Optional</em>. <em>True</em>, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
     * @param bool|null $is_moving <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
     */
    public static function make(string $type = null, Document $document = null, int $dark_theme_dimming = null, bool $is_blurred = null, bool $is_moving = null): self
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