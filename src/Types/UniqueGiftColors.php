<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about the color scheme for a user&#39;s name, message replies and link previews based on a unique gift.
 * @method self model_custom_emoji_id(string $value) Custom emoji identifier of the unique gift&#39;s model
 * @method self symbol_custom_emoji_id(string $value) Custom emoji identifier of the unique gift&#39;s symbol
 * @method self light_theme_main_color(int $value) Main color used in light themes; RGB format
 * @method self light_theme_other_colors(array $value) List of 1-3 additional colors used in light themes; RGB format
 * @method self dark_theme_main_color(int $value) Main color used in dark themes; RGB format
 * @method self dark_theme_other_colors(array $value) List of 1-3 additional colors used in dark themes; RGB format
 */
class UniqueGiftColors
{
    public string $model_custom_emoji_id;
    public string $symbol_custom_emoji_id;
    public int $light_theme_main_color;
    public array $light_theme_other_colors;
    public int $dark_theme_main_color;
    public array $dark_theme_other_colors;

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
        
    }

    /**
     * This object contains information about the color scheme for a user&#39;s name, message replies and link previews based on a unique gift.
     * @param string|null $model_custom_emoji_id Custom emoji identifier of the unique gift&#39;s model
     * @param string|null $symbol_custom_emoji_id Custom emoji identifier of the unique gift&#39;s symbol
     * @param int|null $light_theme_main_color Main color used in light themes; RGB format
     * @param array|null $light_theme_other_colors List of 1-3 additional colors used in light themes; RGB format
     * @param int|null $dark_theme_main_color Main color used in dark themes; RGB format
     * @param array|null $dark_theme_other_colors List of 1-3 additional colors used in dark themes; RGB format
     */
    public static function make(string $model_custom_emoji_id = null, string $symbol_custom_emoji_id = null, int $light_theme_main_color = null, array $light_theme_other_colors = null, int $dark_theme_main_color = null, array $dark_theme_other_colors = null): self
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