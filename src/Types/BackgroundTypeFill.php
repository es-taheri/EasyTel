<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The background is automatically filled based on the selected colors.
 * @method self type(string $value) Type of the background, always “fill”
 * @method self fill(BackgroundFill $value) The background fill
 * @method self dark_theme_dimming(int $value) Dimming of the background in dark themes, as a percentage; 0-100
 */
class BackgroundTypeFill
{
    public string $type;
    public BackgroundFill $fill;
    public int $dark_theme_dimming;

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
        if (isset($update['fill'])) $this->fill = new BackgroundFill($update['fill']);
    }

    /**
     * The background is automatically filled based on the selected colors.
     * @param string|null $type Type of the background, always “fill”
     * @param BackgroundFill|null $fill The background fill
     * @param int|null $dark_theme_dimming Dimming of the background in dark themes, as a percentage; 0-100
     */
    public static function make(string $type = null, BackgroundFill $fill = null, int $dark_theme_dimming = null): self
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