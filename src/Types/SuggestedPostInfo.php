<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Contains information about a suggested post.
 * @method self state(string $value) State of the suggested post. Currently, it can be one of “pending”, “approved”, “declined”.
 * @method self price(SuggestedPostPrice $value) <em>Optional</em>. Proposed price of the post. If the field is omitted, then the post is unpaid.
 * @method self send_date(int $value) <em>Optional</em>. Proposed send date of the post. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user or administrator who approves it.
 */
class SuggestedPostInfo
{
    /** @var string */
    public string $state;
    /** @var SuggestedPostPrice */
    public SuggestedPostPrice $price;
    /** @var int */
    public int $send_date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Contains information about a suggested post.
     * @param string|null $state State of the suggested post. Currently, it can be one of “pending”, “approved”, “declined”.
     * @param SuggestedPostPrice|null $price <em>Optional</em>. Proposed price of the post. If the field is omitted, then the post is unpaid.
     * @param int|null $send_date <em>Optional</em>. Proposed send date of the post. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user or administrator who approves it.
     */
    public static function make(string $state = null, SuggestedPostPrice $price = null, int $send_date = null): self
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