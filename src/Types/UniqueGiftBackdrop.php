<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the backdrop of a unique gift.
 * @method self name(string $value) Name of the backdrop
 * @method self colors(UniqueGiftBackdropColors $value) Colors of the backdrop
 * @method self rarity_per_mille(int $value) The number of unique gifts that receive this backdrop for every 1000 gifts upgraded
 */
class UniqueGiftBackdrop
{
    public string $name;
    public UniqueGiftBackdropColors $colors;
    public int $rarity_per_mille;

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
        if (isset($update['colors'])) $this->colors = new UniqueGiftBackdropColors($update['colors']);
    }

    /**
     * This object describes the backdrop of a unique gift.
     * @param string|null $name Name of the backdrop
     * @param UniqueGiftBackdropColors|null $colors Colors of the backdrop
     * @param int|null $rarity_per_mille The number of unique gifts that receive this backdrop for every 1000 gifts upgraded
     */
    public static function make(string $name = null, UniqueGiftBackdropColors $colors = null, int $rarity_per_mille = null): self
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