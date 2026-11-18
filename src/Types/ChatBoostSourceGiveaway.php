<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium giveaways and <em>prize_star_count</em> / 500 times for one year for Telegram Star giveaways.
 * @method self source(string $value) Source of the boost, always “giveaway”
 * @method self giveaway_message_id(int $value) Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn't sent yet.
 * @method self user(User $value) <em>Optional</em>. User that won the prize in the giveaway if any; for Telegram Premium giveaways only
 * @method self prize_star_count(int $value) <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 * @method self is_unclaimed(bool $value) <em>Optional</em>. <em>True</em>, if the giveaway was completed, but there was no user to win the prize
 */
class ChatBoostSourceGiveaway
{
    /** @var string */
    public string $source;
    /** @var int */
    public int $giveaway_message_id;
    /** @var User */
    public User $user;
    /** @var int */
    public int $prize_star_count;
    /** @var bool */
    public bool $is_unclaimed;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium giveaways and <em>prize_star_count</em> / 500 times for one year for Telegram Star giveaways.
     * @param string|null $source Source of the boost, always “giveaway”
     * @param int|null $giveaway_message_id Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn't sent yet.
     * @param User|null $user <em>Optional</em>. User that won the prize in the giveaway if any; for Telegram Premium giveaways only
     * @param int|null $prize_star_count <em>Optional</em>. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
     * @param bool|null $is_unclaimed <em>Optional</em>. <em>True</em>, if the giveaway was completed, but there was no user to win the prize
     */
    public static function make(string $source = null, int $giveaway_message_id = null, User $user = null, int $prize_star_count = null, bool $is_unclaimed = null): self
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