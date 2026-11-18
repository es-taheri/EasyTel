<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * A preformatted text block, corresponding to the nested HTML tags <code><pre></code> and <code><code></code>.
 * @method self type(string $value) Type of the block, always “pre”
 * @method self text(RichText|string|array $value) Text of the block
 * @method self language(string $value) <em>Optional</em>. The programming language of the text
 */
class RichBlockPreformatted
{
    /** @var string */
    public string $type;
    /** @var RichText|string|array */
    public RichText|string|array $text;
    /** @var string */
    public string $language;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * A preformatted text block, corresponding to the nested HTML tags <code><pre></code> and <code><code></code>.
     * @param string|null $type Type of the block, always “pre”
     * @param RichText|string|array|null $text Text of the block
     * @param string|null $language <em>Optional</em>. The programming language of the text
     */
    public static function make(string $type = null, RichText|string|array $text = null, string $language = null): self
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