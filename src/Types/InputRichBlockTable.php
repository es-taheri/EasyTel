<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * A table, corresponding to the HTML tag <code><table></code>.
 * @method self type(string $value) Type of the block, always “table”
 * @method self cells(RichBlockTableCell[][] $value) Cells of the table
 * @method self is_bordered(bool $value) <em>Optional</em>. Pass <em>True</em> if the table has borders
 * @method self is_striped(bool $value) <em>Optional</em>. Pass <em>True</em> if the table is striped
 * @method self is_compact(bool $value) <em>Optional</em>. Pass <em>True</em> if table cells must have smaller indents
 * @method self caption(RichText|string|array $value) <em>Optional</em>. Caption of the table
 */
class InputRichBlockTable
{
    /** @var string */
    public string $type;
    /** @var RichBlockTableCell[][] */
    public array $cells;
    /** @var bool */
    public bool $is_bordered;
    /** @var bool */
    public bool $is_striped;
    /** @var bool */
    public bool $is_compact;
    /** @var RichText|string|array */
    public RichText|string|array $caption;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * A table, corresponding to the HTML tag <code><table></code>.
     * @param string|null $type Type of the block, always “table”
     * @param RichBlockTableCell[][]|null $cells Cells of the table
     * @param bool|null $is_bordered <em>Optional</em>. Pass <em>True</em> if the table has borders
     * @param bool|null $is_striped <em>Optional</em>. Pass <em>True</em> if the table is striped
     * @param bool|null $is_compact <em>Optional</em>. Pass <em>True</em> if table cells must have smaller indents
     * @param RichText|string|array|null $caption <em>Optional</em>. Caption of the table
     */
    public static function make(string $type = null, array $cells = null, bool $is_bordered = null, bool $is_striped = null, bool $is_compact = null, RichText|string|array $caption = null): self
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