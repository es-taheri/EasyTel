<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * An expandable block for details disclosure, corresponding to the HTML tag <code><details></code>.
 * @method self type(string $value) Type of the block, always “details”
 * @method self summary(RichText|string|array $value) Always shown summary of the block
 * @method self blocks(RichBlock[] $value) Content of the block
 * @method self is_open(bool $value) <em>Optional</em>. <em>True</em>, if the content of the block is visible by default
 */
class RichBlockDetails
{
    /** @var string */
    public string $type;
    /** @var RichText|string|array */
    public RichText|string|array $summary;
    /** @var RichBlock[] */
    public array $blocks;
    /** @var bool */
    public bool $is_open;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * An expandable block for details disclosure, corresponding to the HTML tag <code><details></code>.
     * @param string|null $type Type of the block, always “details”
     * @param RichText|string|array|null $summary Always shown summary of the block
     * @param RichBlock[]|null $blocks Content of the block
     * @param bool|null $is_open <em>Optional</em>. <em>True</em>, if the content of the block is visible by default
     */
    public static function make(string $type = null, RichText|string|array $summary = null, array $blocks = null, bool $is_open = null): self
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