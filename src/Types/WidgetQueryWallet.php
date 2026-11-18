<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes a TON wallet of the user, which is used in a query from a widget.
 * @method self address(string $value) Address of the wallet
 * @method self amount(string $value) Balance of the wallet, in the <em>smallest units</em> of the cryptocurrency, as a decimal number
 * @method self public_key(string $value) <em>Optional</em>. Hex-encoded public key of the wallet; omitted if unknown
 * @method self state_init(string $value) <em>Optional</em>. Base64-encoded serialized StateInit of the wallet contract; omitted if and only if <em>public_key</em> is omitted
 */
class WidgetQueryWallet
{
    /** @var string */
    public string $address;
    /** @var string */
    public string $amount;
    /** @var string */
    public string $public_key;
    /** @var string */
    public string $state_init;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes a TON wallet of the user, which is used in a query from a widget.
     * @param string|null $address Address of the wallet
     * @param string|null $amount Balance of the wallet, in the <em>smallest units</em> of the cryptocurrency, as a decimal number
     * @param string|null $public_key <em>Optional</em>. Hex-encoded public key of the wallet; omitted if unknown
     * @param string|null $state_init <em>Optional</em>. Base64-encoded serialized StateInit of the wallet contract; omitted if and only if <em>public_key</em> is omitted
     */
    public static function make(string $address = null, string $amount = null, string $public_key = null, string $state_init = null): self
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