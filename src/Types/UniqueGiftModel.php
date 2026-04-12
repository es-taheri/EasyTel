<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the model of a unique gift.
 * @method self name(string $value) Name of the model
 * @method self sticker(Sticker $value) The sticker that represents the unique gift
 * @method self rarity_per_mille(int $value) The number of unique gifts that receive this model for every 1000 gift upgrades. Always 0 for crafted gifts.
 * @method self rarity(string $value) <em>Optional</em>. Rarity of the model if it is a crafted model. Currently, can be “uncommon”, “rare”, “epic”, or “legendary”.
 */
class UniqueGiftModel
{
    public string $name;
    public Sticker $sticker;
    public int $rarity_per_mille;
    public string $rarity;

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
        if (isset($update['sticker'])) $this->sticker = new Sticker($update['sticker']);
    }

    /**
     * This object describes the model of a unique gift.
     * @param string|null $name Name of the model
     * @param Sticker|null $sticker The sticker that represents the unique gift
     * @param int|null $rarity_per_mille The number of unique gifts that receive this model for every 1000 gift upgrades. Always 0 for crafted gifts.
     * @param string|null $rarity <em>Optional</em>. Rarity of the model if it is a crafted model. Currently, can be “uncommon”, “rare”, “epic”, or “legendary”.
     */
    public static function make(string $name = null, Sticker $sticker = null, int $rarity_per_mille = null, string $rarity = null): self
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