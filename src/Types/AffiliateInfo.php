<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Contains information about the affiliate that received a commission via this transaction.
 * @method self affiliate_user(User $value) <em>Optional</em>. The bot or the user that received an affiliate commission if it was received by a bot or a user
 * @method self affiliate_chat(Chat $value) <em>Optional</em>. The chat that received an affiliate commission if it was received by a chat
 * @method self commission_per_mille(int $value) The number of Telegram Stars received by the affiliate for each 1000 Telegram Stars received by the bot from referred users
 * @method self amount(int $value) Integer amount of Telegram Stars received by the affiliate from the transaction, rounded to 0; can be negative for refunds
 * @method self nanostar_amount(int $value) <em>Optional</em>. The number of 1/1000000000 shares of Telegram Stars received by the affiliate; from -999999999 to 999999999; can be negative for refunds
 */
class AffiliateInfo
{
    /** @var User */
    public User $affiliate_user;
    /** @var Chat */
    public Chat $affiliate_chat;
    /** @var int */
    public int $commission_per_mille;
    /** @var int */
    public int $amount;
    /** @var int */
    public int $nanostar_amount;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Contains information about the affiliate that received a commission via this transaction.
     * @param User|null $affiliate_user <em>Optional</em>. The bot or the user that received an affiliate commission if it was received by a bot or a user
     * @param Chat|null $affiliate_chat <em>Optional</em>. The chat that received an affiliate commission if it was received by a chat
     * @param int|null $commission_per_mille The number of Telegram Stars received by the affiliate for each 1000 Telegram Stars received by the bot from referred users
     * @param int|null $amount Integer amount of Telegram Stars received by the affiliate from the transaction, rounded to 0; can be negative for refunds
     * @param int|null $nanostar_amount <em>Optional</em>. The number of 1/1000000000 shares of Telegram Stars received by the affiliate; from -999999999 to 999999999; can be negative for refunds
     */
    public static function make(User $affiliate_user = null, Chat $affiliate_chat = null, int $commission_per_mille = null, int $amount = null, int $nanostar_amount = null): self
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