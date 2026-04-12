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
    public User $affiliate_user;
    public Chat $affiliate_chat;
    public int $commission_per_mille;
    public int $amount;
    public int $nanostar_amount;

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
        if (isset($update['affiliate_user'])) $this->affiliate_user = new User($update['affiliate_user']);
        if (isset($update['affiliate_chat'])) $this->affiliate_chat = new Chat($update['affiliate_chat']);
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