<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the affiliate program that issued the affiliate commission received via this transaction.
 * @method self type(string $value) Type of the transaction partner, always “affiliate_program”
 * @method self sponsor_user(User $value) <em>Optional</em>. Information about the bot that sponsored the affiliate program
 * @method self commission_per_mille(int $value) The number of Telegram Stars received by the bot for each 1000 Telegram Stars received by the affiliate program sponsor from referred users
 */
class TransactionPartnerAffiliateProgram
{
    public string $type;
    public User $sponsor_user;
    public int $commission_per_mille;

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
        if (isset($update['sponsor_user'])) $this->sponsor_user = new User($update['sponsor_user']);
    }

    /**
     * Describes the affiliate program that issued the affiliate commission received via this transaction.
     * @param string|null $type Type of the transaction partner, always “affiliate_program”
     * @param User|null $sponsor_user <em>Optional</em>. Information about the bot that sponsored the affiliate program
     * @param int|null $commission_per_mille The number of Telegram Stars received by the bot for each 1000 Telegram Stars received by the affiliate program sponsor from referred users
     */
    public static function make(string $type = null, User $sponsor_user = null, int $commission_per_mille = null): self
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