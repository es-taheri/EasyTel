<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 * @method self winner_count(int $value) Number of winners in the giveaway
 * @method self unclaimed_prize_count(int $value) <em>Optional</em>. Number of undistributed prizes
 * @method self giveaway_message(Message $value) <em>Optional</em>. Message with the giveaway that was completed, if it wasn&#39;t deleted
 * @method self is_star_giveaway(True $value) <em>Optional</em>. <em>True</em>, if the giveaway is a Telegram Star giveaway. Otherwise, currently, the giveaway is a Telegram Premium giveaway.
 */
class GiveawayCompleted
{
    public int $winner_count;
    public int $unclaimed_prize_count;
    public Message $giveaway_message;
    public True $is_star_giveaway;

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
        if (isset($update['giveaway_message'])) $this->giveaway_message = new Message($update['giveaway_message']);
    }

    /**
     * This object represents a service message about the completion of a giveaway without public winners.
     * @param int|null $winner_count Number of winners in the giveaway
     * @param int|null $unclaimed_prize_count <em>Optional</em>. Number of undistributed prizes
     * @param Message|null $giveaway_message <em>Optional</em>. Message with the giveaway that was completed, if it wasn&#39;t deleted
     * @param True|null $is_star_giveaway <em>Optional</em>. <em>True</em>, if the giveaway is a Telegram Star giveaway. Otherwise, currently, the giveaway is a Telegram Premium giveaway.
     */
    public static function make(int $winner_count = null, int $unclaimed_prize_count = null, Message $giveaway_message = null, True $is_star_giveaway = null): self
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