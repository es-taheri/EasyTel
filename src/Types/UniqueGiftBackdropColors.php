<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the colors of the backdrop of a unique gift.
 * @method self center_color(int $value) The color in the center of the backdrop in RGB format
 * @method self edge_color(int $value) The color on the edges of the backdrop in RGB format
 * @method self symbol_color(int $value) The color to be applied to the symbol in RGB format
 * @method self text_color(int $value) The color for the text on the backdrop in RGB format
 */
class UniqueGiftBackdropColors
{
    public int $center_color;
    public int $edge_color;
    public int $symbol_color;
    public int $text_color;

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
     * This object describes the colors of the backdrop of a unique gift.
     * @param int|null $center_color The color in the center of the backdrop in RGB format
     * @param int|null $edge_color The color on the edges of the backdrop in RGB format
     * @param int|null $symbol_color The color to be applied to the symbol in RGB format
     * @param int|null $text_color The color for the text on the backdrop in RGB format
     */
    public static function make(int $center_color = null, int $edge_color = null, int $symbol_color = null, int $text_color = null): self
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