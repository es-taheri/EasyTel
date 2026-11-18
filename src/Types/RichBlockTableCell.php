<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Cell in a table.
 * @method self text(RichText|string|array $value) <em>Optional</em>. Text in the cell. If omitted, then the cell is invisible.
 * @method self is_header(bool $value) <em>Optional</em>. <em>True</em>, if the cell is a header cell
 * @method self colspan(int $value) <em>Optional</em>. The number of columns the cell spans if it is bigger than 1
 * @method self rowspan(int $value) <em>Optional</em>. The number of rows the cell spans if it is bigger than 1
 * @method self align(string $value) Horizontal cell content alignment. Currently, must be one of “left”, “center”, or “right”.
 * @method self valign(string $value) Vertical cell content alignment. Currently, must be one of “top”, “middle”, or “bottom”.
 */
class RichBlockTableCell
{
    /** @var RichText|string|array */
    public RichText|string|array $text;
    /** @var bool */
    public bool $is_header;
    /** @var int */
    public int $colspan;
    /** @var int */
    public int $rowspan;
    /** @var string */
    public string $align;
    /** @var string */
    public string $valign;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Cell in a table.
     * @param RichText|string|array|null $text <em>Optional</em>. Text in the cell. If omitted, then the cell is invisible.
     * @param bool|null $is_header <em>Optional</em>. <em>True</em>, if the cell is a header cell
     * @param int|null $colspan <em>Optional</em>. The number of columns the cell spans if it is bigger than 1
     * @param int|null $rowspan <em>Optional</em>. The number of rows the cell spans if it is bigger than 1
     * @param string|null $align Horizontal cell content alignment. Currently, must be one of “left”, “center”, or “right”.
     * @param string|null $valign Vertical cell content alignment. Currently, must be one of “top”, “middle”, or “bottom”.
     */
    public static function make(RichText|string|array $text = null, bool $is_header = null, int $colspan = null, int $rowspan = null, string $align = null, string $valign = null): self
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