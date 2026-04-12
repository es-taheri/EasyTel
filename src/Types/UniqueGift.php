<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes a unique gift that was upgraded from a regular gift.
 * @method self gift_id(string $value) Identifier of the regular gift from which the gift was upgraded
 * @method self base_name(string $value) Human-readable name of the regular gift from which this unique gift was upgraded
 * @method self name(string $value) Unique name of the gift. This name can be used in <code>https://t.me/nft/...</code> links and story areas
 * @method self number(int $value) Unique number of the upgraded gift among gifts upgraded from the same regular gift
 * @method self model(UniqueGiftModel $value) Model of the gift
 * @method self symbol(UniqueGiftSymbol $value) Symbol of the gift
 * @method self backdrop(UniqueGiftBackdrop $value) Backdrop of the gift
 * @method self is_premium(True $value) <em>Optional</em>. <em>True</em>, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
 * @method self is_burned(True $value) <em>Optional</em>. <em>True</em>, if the gift was used to craft another gift and isn&#39;t available anymore
 * @method self is_from_blockchain(True $value) <em>Optional</em>. <em>True</em>, if the gift is assigned from the TON blockchain and can&#39;t be resold or transferred in Telegram
 * @method self colors(UniqueGiftColors $value) <em>Optional</em>. The color scheme that can be used by the gift&#39;s owner for the chat&#39;s name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
 * @method self publisher_chat(Chat $value) <em>Optional</em>. Information about the chat that published the gift
 */
class UniqueGift
{
    public string $gift_id;
    public string $base_name;
    public string $name;
    public int $number;
    public UniqueGiftModel $model;
    public UniqueGiftSymbol $symbol;
    public UniqueGiftBackdrop $backdrop;
    public True $is_premium;
    public True $is_burned;
    public True $is_from_blockchain;
    public UniqueGiftColors $colors;
    public Chat $publisher_chat;

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
        if (isset($update['model'])) $this->model = new UniqueGiftModel($update['model']);
        if (isset($update['symbol'])) $this->symbol = new UniqueGiftSymbol($update['symbol']);
        if (isset($update['backdrop'])) $this->backdrop = new UniqueGiftBackdrop($update['backdrop']);
        if (isset($update['colors'])) $this->colors = new UniqueGiftColors($update['colors']);
        if (isset($update['publisher_chat'])) $this->publisher_chat = new Chat($update['publisher_chat']);
    }

    /**
     * This object describes a unique gift that was upgraded from a regular gift.
     * @param string|null $gift_id Identifier of the regular gift from which the gift was upgraded
     * @param string|null $base_name Human-readable name of the regular gift from which this unique gift was upgraded
     * @param string|null $name Unique name of the gift. This name can be used in <code>https://t.me/nft/...</code> links and story areas
     * @param int|null $number Unique number of the upgraded gift among gifts upgraded from the same regular gift
     * @param UniqueGiftModel|null $model Model of the gift
     * @param UniqueGiftSymbol|null $symbol Symbol of the gift
     * @param UniqueGiftBackdrop|null $backdrop Backdrop of the gift
     * @param True|null $is_premium <em>Optional</em>. <em>True</em>, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
     * @param True|null $is_burned <em>Optional</em>. <em>True</em>, if the gift was used to craft another gift and isn&#39;t available anymore
     * @param True|null $is_from_blockchain <em>Optional</em>. <em>True</em>, if the gift is assigned from the TON blockchain and can&#39;t be resold or transferred in Telegram
     * @param UniqueGiftColors|null $colors <em>Optional</em>. The color scheme that can be used by the gift&#39;s owner for the chat&#39;s name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
     * @param Chat|null $publisher_chat <em>Optional</em>. Information about the chat that published the gift
     */
    public static function make(string $gift_id = null, string $base_name = null, string $name = null, int $number = null, UniqueGiftModel $model = null, UniqueGiftSymbol $symbol = null, UniqueGiftBackdrop $backdrop = null, True $is_premium = null, True $is_burned = null, True $is_from_blockchain = null, UniqueGiftColors $colors = null, Chat $publisher_chat = null): self
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