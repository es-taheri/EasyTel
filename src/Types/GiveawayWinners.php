<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a message about the completion of a giveaway with public winners.
 * @method self chat(Chat $value) The chat that created the giveaway
 * @method self giveaway_message_id(int $value) Identifier of the message with the giveaway in the chat
 * @method self winners_selection_date(int $value) Point in time (Unix timestamp) when winners of the giveaway were selected
 * @method self winner_count(int $value) Total number of winners in the giveaway
 * @method self winners(array $value) List of up to 100 winners of the giveaway
 * @method self additional_chat_count(int $value) <em>Optional</em>. The number of other chats the user had to join in order to be eligible for the giveaway
 * @method self prize_star_count(int $value) <em>Optional</em>. The number of Telegram Stars that were split between giveaway winners; for Telegram Star giveaways only
 * @method self premium_subscription_month_count(int $value) <em>Optional</em>. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
 * @method self unclaimed_prize_count(int $value) <em>Optional</em>. Number of undistributed prizes
 * @method self only_new_members(True $value) <em>Optional</em>. <em>True</em>, if only users who had joined the chats after the giveaway started were eligible to win
 * @method self was_refunded(True $value) <em>Optional</em>. <em>True</em>, if the giveaway was canceled because the payment for it was refunded
 * @method self prize_description(string $value) <em>Optional</em>. Description of additional giveaway prize
 */
class GiveawayWinners
{
    public Chat $chat;
    public int $giveaway_message_id;
    public int $winners_selection_date;
    public int $winner_count;
    public array $winners;
    public int $additional_chat_count;
    public int $prize_star_count;
    public int $premium_subscription_month_count;
    public int $unclaimed_prize_count;
    public True $only_new_members;
    public True $was_refunded;
    public string $prize_description;

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
        if (isset($update['chat'])) $this->chat = new Chat($update['chat']);
    }

    /**
     * This object represents a message about the completion of a giveaway with public winners.
     * @param Chat|null $chat The chat that created the giveaway
     * @param int|null $giveaway_message_id Identifier of the message with the giveaway in the chat
     * @param int|null $winners_selection_date Point in time (Unix timestamp) when winners of the giveaway were selected
     * @param int|null $winner_count Total number of winners in the giveaway
     * @param array|null $winners List of up to 100 winners of the giveaway
     * @param int|null $additional_chat_count <em>Optional</em>. The number of other chats the user had to join in order to be eligible for the giveaway
     * @param int|null $prize_star_count <em>Optional</em>. The number of Telegram Stars that were split between giveaway winners; for Telegram Star giveaways only
     * @param int|null $premium_subscription_month_count <em>Optional</em>. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
     * @param int|null $unclaimed_prize_count <em>Optional</em>. Number of undistributed prizes
     * @param True|null $only_new_members <em>Optional</em>. <em>True</em>, if only users who had joined the chats after the giveaway started were eligible to win
     * @param True|null $was_refunded <em>Optional</em>. <em>True</em>, if the giveaway was canceled because the payment for it was refunded
     * @param string|null $prize_description <em>Optional</em>. Description of additional giveaway prize
     */
    public static function make(Chat $chat = null, int $giveaway_message_id = null, int $winners_selection_date = null, int $winner_count = null, array $winners = null, int $additional_chat_count = null, int $prize_star_count = null, int $premium_subscription_month_count = null, int $unclaimed_prize_count = null, True $only_new_members = null, True $was_refunded = null, string $prize_description = null): self
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