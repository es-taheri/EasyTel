<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a message about a scheduled giveaway.
 * @method self chats(Chat[] $value) The list of chats which the user must join to participate in the giveaway
 * @method self winners_selection_date(int $value) Point in time (Unix timestamp) when winners of the giveaway will be selected
 * @method self winner_count(int $value) The number of users which are supposed to be selected as winners of the giveaway
 * @method self only_new_members(bool $value) <em>Optional</em>. <em>True</em>, if only users who join the chats after the giveaway started should be eligible to win
 * @method self has_public_winners(bool $value) <em>Optional</em>. <em>True</em>, if the list of giveaway winners will be visible to everyone
 * @method self prize_description(string $value) <em>Optional</em>. Description of additional giveaway prize
 * @method self country_codes(string[] $value) <em>Optional</em>. A list of two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
 * @method self prize_star_count(int $value) <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 * @method self premium_subscription_month_count(int $value) <em>Optional</em>. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
 */
class Giveaway
{
    /** @var Chat[] */
    public array $chats;
    /** @var int */
    public int $winners_selection_date;
    /** @var int */
    public int $winner_count;
    /** @var bool */
    public bool $only_new_members;
    /** @var bool */
    public bool $has_public_winners;
    /** @var string */
    public string $prize_description;
    /** @var string[] */
    public array $country_codes;
    /** @var int */
    public int $prize_star_count;
    /** @var int */
    public int $premium_subscription_month_count;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a message about a scheduled giveaway.
     * @param Chat[]|null $chats The list of chats which the user must join to participate in the giveaway
     * @param int|null $winners_selection_date Point in time (Unix timestamp) when winners of the giveaway will be selected
     * @param int|null $winner_count The number of users which are supposed to be selected as winners of the giveaway
     * @param bool|null $only_new_members <em>Optional</em>. <em>True</em>, if only users who join the chats after the giveaway started should be eligible to win
     * @param bool|null $has_public_winners <em>Optional</em>. <em>True</em>, if the list of giveaway winners will be visible to everyone
     * @param string|null $prize_description <em>Optional</em>. Description of additional giveaway prize
     * @param string[]|null $country_codes <em>Optional</em>. A list of two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
     * @param int|null $prize_star_count <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     * @param int|null $premium_subscription_month_count <em>Optional</em>. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
     */
    public static function make(array $chats = null, int $winners_selection_date = null, int $winner_count = null, bool $only_new_members = null, bool $has_public_winners = null, string $prize_description = null, array $country_codes = null, int $prize_star_count = null, int $premium_subscription_month_count = null): self
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