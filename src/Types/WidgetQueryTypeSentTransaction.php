<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The query notifies the bot about a transaction sent from a TON wallet of the user who sent the query.
 * @method self type(string $value) Type of the query, always “sent_transaction”
 * @method self wallet(WidgetQueryWallet $value) The wallet from which the transaction was sent
 * @method self msg_hash(string $value) Value of <em>msg_hash</em> of the transaction
 */
class WidgetQueryTypeSentTransaction
{
    /** @var string */
    public string $type;
    /** @var WidgetQueryWallet */
    public WidgetQueryWallet $wallet;
    /** @var string */
    public string $msg_hash;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The query notifies the bot about a transaction sent from a TON wallet of the user who sent the query.
     * @param string|null $type Type of the query, always “sent_transaction”
     * @param WidgetQueryWallet|null $wallet The wallet from which the transaction was sent
     * @param string|null $msg_hash Value of <em>msg_hash</em> of the transaction
     */
    public static function make(string $type = null, WidgetQueryWallet $wallet = null, string $msg_hash = null): self
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