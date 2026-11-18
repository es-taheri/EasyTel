<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes a unique gift that was upgraded from a regular gift.
 * @method self gift_id(string $value) Identifier of the regular gift from which the gift was upgraded
 * @method self base_name(string $value) Human-readable name of the regular gift from which this unique gift was upgraded
 * @method self name(string $value) Unique name of the gift. This name can be used in <code>https://t.me/nft/...</code> links and story areas.
 * @method self number(int $value) Unique number of the upgraded gift among gifts upgraded from the same regular gift
 * @method self model(UniqueGiftModel $value) Model of the gift
 * @method self symbol(UniqueGiftSymbol $value) Symbol of the gift
 * @method self backdrop(UniqueGiftBackdrop $value) Backdrop of the gift
 * @method self is_premium(bool $value) <em>Optional</em>. <em>True</em>, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
 * @method self is_burned(bool $value) <em>Optional</em>. <em>True</em>, if the gift was used to craft another gift and isn't available anymore
 * @method self is_from_blockchain(bool $value) <em>Optional</em>. <em>True</em>, if the gift is assigned from the TON blockchain and can't be resold or transferred in Telegram
 * @method self colors(UniqueGiftColors $value) <em>Optional</em>. The color scheme that can be used by the gift's owner for the chat's name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
 * @method self publisher_chat(Chat $value) <em>Optional</em>. Information about the chat that published the gift
 */
class UniqueGift
{
    /** @var string */
    public string $gift_id;
    /** @var string */
    public string $base_name;
    /** @var string */
    public string $name;
    /** @var int */
    public int $number;
    /** @var UniqueGiftModel */
    public UniqueGiftModel $model;
    /** @var UniqueGiftSymbol */
    public UniqueGiftSymbol $symbol;
    /** @var UniqueGiftBackdrop */
    public UniqueGiftBackdrop $backdrop;
    /** @var bool */
    public bool $is_premium;
    /** @var bool */
    public bool $is_burned;
    /** @var bool */
    public bool $is_from_blockchain;
    /** @var UniqueGiftColors */
    public UniqueGiftColors $colors;
    /** @var Chat */
    public Chat $publisher_chat;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes a unique gift that was upgraded from a regular gift.
     * @param string|null $gift_id Identifier of the regular gift from which the gift was upgraded
     * @param string|null $base_name Human-readable name of the regular gift from which this unique gift was upgraded
     * @param string|null $name Unique name of the gift. This name can be used in <code>https://t.me/nft/...</code> links and story areas.
     * @param int|null $number Unique number of the upgraded gift among gifts upgraded from the same regular gift
     * @param UniqueGiftModel|null $model Model of the gift
     * @param UniqueGiftSymbol|null $symbol Symbol of the gift
     * @param UniqueGiftBackdrop|null $backdrop Backdrop of the gift
     * @param bool|null $is_premium <em>Optional</em>. <em>True</em>, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
     * @param bool|null $is_burned <em>Optional</em>. <em>True</em>, if the gift was used to craft another gift and isn't available anymore
     * @param bool|null $is_from_blockchain <em>Optional</em>. <em>True</em>, if the gift is assigned from the TON blockchain and can't be resold or transferred in Telegram
     * @param UniqueGiftColors|null $colors <em>Optional</em>. The color scheme that can be used by the gift's owner for the chat's name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
     * @param Chat|null $publisher_chat <em>Optional</em>. Information about the chat that published the gift
     */
    public static function make(string $gift_id = null, string $base_name = null, string $name = null, int $number = null, UniqueGiftModel $model = null, UniqueGiftSymbol $symbol = null, UniqueGiftBackdrop $backdrop = null, bool $is_premium = null, bool $is_burned = null, bool $is_from_blockchain = null, UniqueGiftColors $colors = null, Chat $publisher_chat = null): self
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