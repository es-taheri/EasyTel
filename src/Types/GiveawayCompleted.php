<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 * @method self winner_count(int $value) Number of winners in the giveaway
 * @method self unclaimed_prize_count(int $value) <em>Optional</em>. Number of undistributed prizes
 * @method self giveaway_message(Message $value) <em>Optional</em>. Message with the giveaway that was completed, if it wasn't deleted
 * @method self is_star_giveaway(bool $value) <em>Optional</em>. <em>True</em>, if the giveaway is a Telegram Star giveaway. Otherwise, currently, the giveaway is a Telegram Premium giveaway.
 */
class GiveawayCompleted
{
    /** @var int */
    public int $winner_count;
    /** @var int */
    public int $unclaimed_prize_count;
    /** @var Message */
    public Message $giveaway_message;
    /** @var bool */
    public bool $is_star_giveaway;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a service message about the completion of a giveaway without public winners.
     * @param int|null $winner_count Number of winners in the giveaway
     * @param int|null $unclaimed_prize_count <em>Optional</em>. Number of undistributed prizes
     * @param Message|null $giveaway_message <em>Optional</em>. Message with the giveaway that was completed, if it wasn't deleted
     * @param bool|null $is_star_giveaway <em>Optional</em>. <em>True</em>, if the giveaway is a Telegram Star giveaway. Otherwise, currently, the giveaway is a Telegram Premium giveaway.
     */
    public static function make(int $winner_count = null, int $unclaimed_prize_count = null, Message $giveaway_message = null, bool $is_star_giveaway = null): self
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