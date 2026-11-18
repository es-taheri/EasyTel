<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * An item of a list.
 * @method self label(string $value) Label of the item
 * @method self blocks(RichBlock[] $value) The content of the item
 * @method self has_checkbox(bool $value) <em>Optional</em>. <em>True</em>, if the item has a checkbox
 * @method self is_checked(bool $value) <em>Optional</em>. <em>True</em>, if the item has a checked checkbox
 * @method self value(int $value) <em>Optional</em>. For ordered lists, the numeric value of the item label
 * @method self type(string $value) <em>Optional</em>. For ordered lists, the type of the item label; must be one of “a” for lowercase letters, “A” for uppercase letters, “i” for lowercase Roman numerals, “I” for uppercase Roman numerals, or “1” for decimal numbers
 */
class RichBlockListItem
{
    /** @var string */
    public string $label;
    /** @var RichBlock[] */
    public array $blocks;
    /** @var bool */
    public bool $has_checkbox;
    /** @var bool */
    public bool $is_checked;
    /** @var int */
    public int $value;
    /** @var string */
    public string $type;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * An item of a list.
     * @param string|null $label Label of the item
     * @param RichBlock[]|null $blocks The content of the item
     * @param bool|null $has_checkbox <em>Optional</em>. <em>True</em>, if the item has a checkbox
     * @param bool|null $is_checked <em>Optional</em>. <em>True</em>, if the item has a checked checkbox
     * @param int|null $value <em>Optional</em>. For ordered lists, the numeric value of the item label
     * @param string|null $type <em>Optional</em>. For ordered lists, the type of the item label; must be one of “a” for lowercase letters, “A” for uppercase letters, “i” for lowercase Roman numerals, “I” for uppercase Roman numerals, or “1” for decimal numbers
     */
    public static function make(string $label = null, array $blocks = null, bool $has_checkbox = null, bool $is_checked = null, int $value = null, string $type = null): self
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