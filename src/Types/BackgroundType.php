<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the type of a background. Currently, it can be one of
 * @method self backgroundtypefill(BackgroundTypeFill $value)
 * @method self backgroundtypewallpaper(BackgroundTypeWallpaper $value)
 * @method self backgroundtypepattern(BackgroundTypePattern $value)
 * @method self backgroundtypechattheme(BackgroundTypeChatTheme $value)
 */
class BackgroundType
{
    protected const _UNION_BASE = true;
    /** @var BackgroundTypeFill */
    public BackgroundTypeFill $backgroundtypefill;
    /** @var BackgroundTypeWallpaper */
    public BackgroundTypeWallpaper $backgroundtypewallpaper;
    /** @var BackgroundTypePattern */
    public BackgroundTypePattern $backgroundtypepattern;
    /** @var BackgroundTypeChatTheme */
    public BackgroundTypeChatTheme $backgroundtypechattheme;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes the type of a background. Currently, it can be one of
     * @param BackgroundTypeFill|null $backgroundtypefill
     * @param BackgroundTypeWallpaper|null $backgroundtypewallpaper
     * @param BackgroundTypePattern|null $backgroundtypepattern
     * @param BackgroundTypeChatTheme|null $backgroundtypechattheme
     */
    public static function make(BackgroundTypeFill $backgroundtypefill = null, BackgroundTypeWallpaper $backgroundtypewallpaper = null, BackgroundTypePattern $backgroundtypepattern = null, BackgroundTypeChatTheme $backgroundtypechattheme = null): self
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