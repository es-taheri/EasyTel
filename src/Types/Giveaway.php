<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a message about a scheduled giveaway.
 * @method self chats(array $value) The list of chats which the user must join to participate in the giveaway
 * @method self winners_selection_date(int $value) Point in time (Unix timestamp) when winners of the giveaway will be selected
 * @method self winner_count(int $value) The number of users which are supposed to be selected as winners of the giveaway
 * @method self only_new_members(True $value) <em>Optional</em>. <em>True</em>, if only users who join the chats after the giveaway started should be eligible to win
 * @method self has_public_winners(True $value) <em>Optional</em>. <em>True</em>, if the list of giveaway winners will be visible to everyone
 * @method self prize_description(string $value) <em>Optional</em>. Description of additional giveaway prize
 * @method self country_codes(array $value) <em>Optional</em>. A list of two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
 * @method self prize_star_count(int $value) <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 * @method self premium_subscription_month_count(int $value) <em>Optional</em>. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
 */
class Giveaway
{
    public array $chats;
    public int $winners_selection_date;
    public int $winner_count;
    public True $only_new_members;
    public True $has_public_winners;
    public string $prize_description;
    public array $country_codes;
    public int $prize_star_count;
    public int $premium_subscription_month_count;

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
     * This object represents a message about a scheduled giveaway.
     * @param array|null $chats The list of chats which the user must join to participate in the giveaway
     * @param int|null $winners_selection_date Point in time (Unix timestamp) when winners of the giveaway will be selected
     * @param int|null $winner_count The number of users which are supposed to be selected as winners of the giveaway
     * @param True|null $only_new_members <em>Optional</em>. <em>True</em>, if only users who join the chats after the giveaway started should be eligible to win
     * @param True|null $has_public_winners <em>Optional</em>. <em>True</em>, if the list of giveaway winners will be visible to everyone
     * @param string|null $prize_description <em>Optional</em>. Description of additional giveaway prize
     * @param array|null $country_codes <em>Optional</em>. A list of two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
     * @param int|null $prize_star_count <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     * @param int|null $premium_subscription_month_count <em>Optional</em>. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
     */
    public static function make(array $chats = null, int $winners_selection_date = null, int $winner_count = null, True $only_new_members = null, True $has_public_winners = null, string $prize_description = null, array $country_codes = null, int $prize_star_count = null, int $premium_subscription_month_count = null): self
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