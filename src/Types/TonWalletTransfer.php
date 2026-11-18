<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about a transfer with a TON wallet.
 * @method self transaction_id(string $value) Unique identifier of the transaction; may be empty if the transfer hasn't been sent yet
 * @method self peer_address(string $value) Address of the transfer peer in the TON blockchain
 * @method self amount(string $value) The amount of transferred cryptocurrency, in the <em>smallest units</em> of the cryptocurrency, as a decimal number; negative for outgoing transfers
 * @method self comment(string $value) <em>Optional</em>. Comment added to the transfer; omitted if the comment is encrypted
 */
class TonWalletTransfer
{
    /** @var string */
    public string $transaction_id;
    /** @var string */
    public string $peer_address;
    /** @var string */
    public string $amount;
    /** @var string */
    public string $comment;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a service message about a transfer with a TON wallet.
     * @param string|null $transaction_id Unique identifier of the transaction; may be empty if the transfer hasn't been sent yet
     * @param string|null $peer_address Address of the transfer peer in the TON blockchain
     * @param string|null $amount The amount of transferred cryptocurrency, in the <em>smallest units</em> of the cryptocurrency, as a decimal number; negative for outgoing transfers
     * @param string|null $comment <em>Optional</em>. Comment added to the transfer; omitted if the comment is encrypted
     */
    public static function make(string $transaction_id = null, string $peer_address = null, string $amount = null, string $comment = null): self
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