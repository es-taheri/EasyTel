<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium giveaways and <em>prize_star_count</em> / 500 times for one year for Telegram Star giveaways.
 * @method self source(string $value) Source of the boost, always “giveaway”
 * @method self giveaway_message_id(int $value) Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn&#39;t sent yet.
 * @method self user(User $value) <em>Optional</em>. User that won the prize in the giveaway if any; for Telegram Premium giveaways only
 * @method self prize_star_count(int $value) <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 * @method self is_unclaimed(True $value) <em>Optional</em>. <em>True</em>, if the giveaway was completed, but there was no user to win the prize
 */
class ChatBoostSourceGiveaway
{
    public string $source;
    public int $giveaway_message_id;
    public User $user;
    public int $prize_star_count;
    public True $is_unclaimed;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
    }

    /**
     * The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium giveaways and <em>prize_star_count</em> / 500 times for one year for Telegram Star giveaways.
     * @param string|null $source Source of the boost, always “giveaway”
     * @param int|null $giveaway_message_id Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn&#39;t sent yet.
     * @param User|null $user <em>Optional</em>. User that won the prize in the giveaway if any; for Telegram Premium giveaways only
     * @param int|null $prize_star_count <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     * @param True|null $is_unclaimed <em>Optional</em>. <em>True</em>, if the giveaway was completed, but there was no user to win the prize
     */
    public static function make(string $source = null, int $giveaway_message_id = null, User $user = null, int $prize_star_count = null, True $is_unclaimed = null): self
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