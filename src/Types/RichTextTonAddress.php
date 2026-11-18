<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * An address in the TON blockchain.
 * @method self type(string $value) Type of the rich text, always “ton_address”
 * @method self text(RichText|string|array $value) Text of the address
 * @method self address(string $value) The address
 */
class RichTextTonAddress
{
    /** @var string */
    public string $type;
    /** @var RichText|string|array */
    public RichText|string|array $text;
    /** @var string */
    public string $address;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * An address in the TON blockchain.
     * @param string|null $type Type of the rich text, always “ton_address”
     * @param RichText|string|array|null $text Text of the address
     * @param string|null $address The address
     */
    public static function make(string $type = null, RichText|string|array $text = null, string $address = null): self
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