<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about the color scheme for a user's name, message replies and link previews based on a unique gift.
 * @method self model_custom_emoji_id(string $value) Custom emoji identifier of the unique gift's model
 * @method self symbol_custom_emoji_id(string $value) Custom emoji identifier of the unique gift's symbol
 * @method self light_theme_main_color(int $value) Main color used in light themes; RGB format
 * @method self light_theme_other_colors(int[] $value) List of 1-3 additional colors used in light themes; RGB format
 * @method self dark_theme_main_color(int $value) Main color used in dark themes; RGB format
 * @method self dark_theme_other_colors(int[] $value) List of 1-3 additional colors used in dark themes; RGB format
 */
class UniqueGiftColors
{
    /** @var string */
    public string $model_custom_emoji_id;
    /** @var string */
    public string $symbol_custom_emoji_id;
    /** @var int */
    public int $light_theme_main_color;
    /** @var int[] */
    public array $light_theme_other_colors;
    /** @var int */
    public int $dark_theme_main_color;
    /** @var int[] */
    public array $dark_theme_other_colors;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about the color scheme for a user's name, message replies and link previews based on a unique gift.
     * @param string|null $model_custom_emoji_id Custom emoji identifier of the unique gift's model
     * @param string|null $symbol_custom_emoji_id Custom emoji identifier of the unique gift's symbol
     * @param int|null $light_theme_main_color Main color used in light themes; RGB format
     * @param int[]|null $light_theme_other_colors List of 1-3 additional colors used in light themes; RGB format
     * @param int|null $dark_theme_main_color Main color used in dark themes; RGB format
     * @param int[]|null $dark_theme_other_colors List of 1-3 additional colors used in dark themes; RGB format
     */
    public static function make(string $model_custom_emoji_id = null, string $symbol_custom_emoji_id = null, int $light_theme_main_color = null, array $light_theme_other_colors = null, int $dark_theme_main_color = null, array $dark_theme_other_colors = null): self
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