<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a withdrawal transaction with Fragment.
 * @method self type(string $value) Type of the transaction partner, always “fragment”
 * @method self withdrawal_state(RevenueWithdrawalState $value) <em>Optional</em>. State of the transaction if the transaction is outgoing
 */
class TransactionPartnerFragment
{
    public string $type;
    public RevenueWithdrawalState $withdrawal_state;

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
        if (isset($update['withdrawal_state'])) $this->withdrawal_state = new RevenueWithdrawalState($update['withdrawal_state']);
    }

    /**
     * Describes a withdrawal transaction with Fragment.
     * @param string|null $type Type of the transaction partner, always “fragment”
     * @param RevenueWithdrawalState|null $withdrawal_state <em>Optional</em>. State of the transaction if the transaction is outgoing
     */
    public static function make(string $type = null, RevenueWithdrawalState $withdrawal_state = null): self
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