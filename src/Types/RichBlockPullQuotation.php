<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * A quotation with centered text, loosely corresponding to the HTML tag <code><aside></code>.
 * @method self type(string $value) Type of the block, always “pullquote”
 * @method self text(RichText|string|array $value) Text of the block
 * @method self credit(RichText|string|array $value) <em>Optional</em>. Credit of the block
 */
class RichBlockPullQuotation
{
    /** @var string */
    public string $type;
    /** @var RichText|string|array */
    public RichText|string|array $text;
    /** @var RichText|string|array */
    public RichText|string|array $credit;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * A quotation with centered text, loosely corresponding to the HTML tag <code><aside></code>.
     * @param string|null $type Type of the block, always “pullquote”
     * @param RichText|string|array|null $text Text of the block
     * @param RichText|string|array|null $credit <em>Optional</em>. Credit of the block
     */
    public static function make(string $type = null, RichText|string|array $text = null, RichText|string|array $credit = null): self
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