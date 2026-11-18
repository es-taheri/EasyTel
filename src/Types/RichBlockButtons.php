<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <code><tg-button-row></code>.
 * @method self type(string $value) Type of the block, always “buttons”
 * @method self buttons(RichMessageButton[] $value) The buttons
 * @method self align(string $value) <em>Optional</em>. Horizontal alignment of the buttons. Currently, must be one of “left”, “center”, or “right”.
 */
class RichBlockButtons
{
    /** @var string */
    public string $type;
    /** @var RichMessageButton[] */
    public array $buttons;
    /** @var string */
    public string $align;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <code><tg-button-row></code>.
     * @param string|null $type Type of the block, always “buttons”
     * @param RichMessageButton[]|null $buttons The buttons
     * @param string|null $align <em>Optional</em>. Horizontal alignment of the buttons. Currently, must be one of “left”, “center”, or “right”.
     */
    public static function make(string $type = null, array $buttons = null, string $align = null): self
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