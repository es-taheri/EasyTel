<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the state of a revenue withdrawal operation. Currently, it can be one of

 */
class RevenueWithdrawalState
{
    public RevenueWithdrawalStatePending $revenuewithdrawalstatepending;
    public RevenueWithdrawalStateSucceeded $revenuewithdrawalstatesucceeded;
    public RevenueWithdrawalStateFailed $revenuewithdrawalstatefailed;

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
        $this->revenuewithdrawalstatepending = new RevenueWithdrawalStatePending($update);
        $this->revenuewithdrawalstatesucceeded = new RevenueWithdrawalStateSucceeded($update);
        $this->revenuewithdrawalstatefailed = new RevenueWithdrawalStateFailed($update);
    }

    
    public static function make(RevenueWithdrawalStatePending $revenuewithdrawalstatepending=null, RevenueWithdrawalStateSucceeded $revenuewithdrawalstatesucceeded=null, RevenueWithdrawalStateFailed $revenuewithdrawalstatefailed=null): self
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