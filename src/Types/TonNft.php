<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an NFT in the TON blockchain.
 * @method self address(string $value) Address of the NFT
 * @method self collection_address(string $value) <em>Optional</em>. Address of the collection to which the NFT belongs; omitted if unknown
 * @method self index(string $value) Index of the NFT among all NFTs from the collection as a decimal number
 * @method self name(string $value) Name of the NFT
 * @method self description(string $value) Description of the NFT
 * @method self attributes(TonNftAttribute[] $value) <em>Optional</em>. Attributes of the NFT
 * @method self gift(UniqueGift $value) <em>Optional</em>. The unique gift corresponding to the NFT
 */
class TonNft
{
    /** @var string */
    public string $address;
    /** @var string */
    public string $collection_address;
    /** @var string */
    public string $index;
    /** @var string */
    public string $name;
    /** @var string */
    public string $description;
    /** @var TonNftAttribute[] */
    public array $attributes;
    /** @var UniqueGift */
    public UniqueGift $gift;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents an NFT in the TON blockchain.
     * @param string|null $address Address of the NFT
     * @param string|null $collection_address <em>Optional</em>. Address of the collection to which the NFT belongs; omitted if unknown
     * @param string|null $index Index of the NFT among all NFTs from the collection as a decimal number
     * @param string|null $name Name of the NFT
     * @param string|null $description Description of the NFT
     * @param TonNftAttribute[]|null $attributes <em>Optional</em>. Attributes of the NFT
     * @param UniqueGift|null $gift <em>Optional</em>. The unique gift corresponding to the NFT
     */
    public static function make(string $address = null, string $collection_address = null, string $index = null, string $name = null, string $description = null, array $attributes = null, UniqueGift $gift = null): self
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