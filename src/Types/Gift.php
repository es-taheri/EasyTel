<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a gift that can be sent by the bot.
 * @method self id(string $value) Unique identifier of the gift
 * @method self sticker(Sticker $value) The sticker that represents the gift
 * @method self star_count(int $value) The number of Telegram Stars that must be paid to send the sticker
 * @method self upgrade_star_count(int $value) <em>Optional</em>. The number of Telegram Stars that must be paid to upgrade the gift to a unique one
 * @method self is_premium(True $value) <em>Optional</em>. <em>True</em>, if the gift can only be purchased by Telegram Premium subscribers
 * @method self has_colors(True $value) <em>Optional</em>. <em>True</em>, if the gift can be used (after being upgraded) to customize a user&#39;s appearance
 * @method self total_count(int $value) <em>Optional</em>. The total number of gifts of this type that can be sent by all users; for limited gifts only
 * @method self remaining_count(int $value) <em>Optional</em>. The number of remaining gifts of this type that can be sent by all users; for limited gifts only
 * @method self personal_total_count(int $value) <em>Optional</em>. The total number of gifts of this type that can be sent by the bot; for limited gifts only
 * @method self personal_remaining_count(int $value) <em>Optional</em>. The number of remaining gifts of this type that can be sent by the bot; for limited gifts only
 * @method self background(GiftBackground $value) <em>Optional</em>. Background of the gift
 * @method self unique_gift_variant_count(int $value) <em>Optional</em>. The total number of different unique gifts that can be obtained by upgrading the gift
 * @method self publisher_chat(Chat $value) <em>Optional</em>. Information about the chat that published the gift
 */
class Gift
{
    public string $id;
    public Sticker $sticker;
    public int $star_count;
    public int $upgrade_star_count;
    public True $is_premium;
    public True $has_colors;
    public int $total_count;
    public int $remaining_count;
    public int $personal_total_count;
    public int $personal_remaining_count;
    public GiftBackground $background;
    public int $unique_gift_variant_count;
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
        if (isset($update['sticker'])) $this->sticker = new Sticker($update['sticker']);
        if (isset($update['background'])) $this->background = new GiftBackground($update['background']);
        if (isset($update['publisher_chat'])) $this->publisher_chat = new Chat($update['publisher_chat']);
    }

    /**
     * This object represents a gift that can be sent by the bot.
     * @param string|null $id Unique identifier of the gift
     * @param Sticker|null $sticker The sticker that represents the gift
     * @param int|null $star_count The number of Telegram Stars that must be paid to send the sticker
     * @param int|null $upgrade_star_count <em>Optional</em>. The number of Telegram Stars that must be paid to upgrade the gift to a unique one
     * @param True|null $is_premium <em>Optional</em>. <em>True</em>, if the gift can only be purchased by Telegram Premium subscribers
     * @param True|null $has_colors <em>Optional</em>. <em>True</em>, if the gift can be used (after being upgraded) to customize a user&#39;s appearance
     * @param int|null $total_count <em>Optional</em>. The total number of gifts of this type that can be sent by all users; for limited gifts only
     * @param int|null $remaining_count <em>Optional</em>. The number of remaining gifts of this type that can be sent by all users; for limited gifts only
     * @param int|null $personal_total_count <em>Optional</em>. The total number of gifts of this type that can be sent by the bot; for limited gifts only
     * @param int|null $personal_remaining_count <em>Optional</em>. The number of remaining gifts of this type that can be sent by the bot; for limited gifts only
     * @param GiftBackground|null $background <em>Optional</em>. Background of the gift
     * @param int|null $unique_gift_variant_count <em>Optional</em>. The total number of different unique gifts that can be obtained by upgrading the gift
     * @param Chat|null $publisher_chat <em>Optional</em>. Information about the chat that published the gift
     */
    public static function make(string $id = null, Sticker $sticker = null, int $star_count = null, int $upgrade_star_count = null, True $is_premium = null, True $has_colors = null, int $total_count = null, int $remaining_count = null, int $personal_total_count = null, int $personal_remaining_count = null, GiftBackground $background = null, int $unique_gift_variant_count = null, Chat $publisher_chat = null): self
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