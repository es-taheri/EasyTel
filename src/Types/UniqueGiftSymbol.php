<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the symbol shown on the pattern of a unique gift.
 * @method self name(string $value) Name of the symbol
 * @method self sticker(Sticker $value) The sticker that represents the unique gift
 * @method self rarity_per_mille(int $value) The number of unique gifts that receive this model for every 1000 gifts upgraded
 */
class UniqueGiftSymbol
{
    /** @var string */
    public string $name;
    /** @var Sticker */
    public Sticker $sticker;
    /** @var int */
    public int $rarity_per_mille;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes the symbol shown on the pattern of a unique gift.
     * @param string|null $name Name of the symbol
     * @param Sticker|null $sticker The sticker that represents the unique gift
     * @param int|null $rarity_per_mille The number of unique gifts that receive this model for every 1000 gifts upgraded
     */
    public static function make(string $name = null, Sticker $sticker = null, int $rarity_per_mille = null): self
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