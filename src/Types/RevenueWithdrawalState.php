<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the state of a revenue withdrawal operation. Currently, it can be one of
 * @method self revenuewithdrawalstatepending(RevenueWithdrawalStatePending $value)
 * @method self revenuewithdrawalstatesucceeded(RevenueWithdrawalStateSucceeded $value)
 * @method self revenuewithdrawalstatefailed(RevenueWithdrawalStateFailed $value)
 */
class RevenueWithdrawalState
{
    protected const _UNION_BASE = true;
    /** @var RevenueWithdrawalStatePending */
    public RevenueWithdrawalStatePending $revenuewithdrawalstatepending;
    /** @var RevenueWithdrawalStateSucceeded */
    public RevenueWithdrawalStateSucceeded $revenuewithdrawalstatesucceeded;
    /** @var RevenueWithdrawalStateFailed */
    public RevenueWithdrawalStateFailed $revenuewithdrawalstatefailed;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes the state of a revenue withdrawal operation. Currently, it can be one of
     * @param RevenueWithdrawalStatePending|null $revenuewithdrawalstatepending
     * @param RevenueWithdrawalStateSucceeded|null $revenuewithdrawalstatesucceeded
     * @param RevenueWithdrawalStateFailed|null $revenuewithdrawalstatefailed
     */
    public static function make(RevenueWithdrawalStatePending $revenuewithdrawalstatepending = null, RevenueWithdrawalStateSucceeded $revenuewithdrawalstatesucceeded = null, RevenueWithdrawalStateFailed $revenuewithdrawalstatefailed = null): self
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