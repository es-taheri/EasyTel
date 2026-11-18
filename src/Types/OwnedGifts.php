<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Contains the list of gifts received and owned by a user or a chat.
 * @method self total_count(int $value) The total number of gifts owned by the user or the chat
 * @method self gifts(OwnedGift[] $value) The list of gifts
 * @method self next_offset(string $value) <em>Optional</em>. Offset for the next request. If empty, then there are no more results.
 */
class OwnedGifts
{
    /** @var int */
    public int $total_count;
    /** @var OwnedGift[] */
    public array $gifts;
    /** @var string */
    public string $next_offset;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Contains the list of gifts received and owned by a user or a chat.
     * @param int|null $total_count The total number of gifts owned by the user or the chat
     * @param OwnedGift[]|null $gifts The list of gifts
     * @param string|null $next_offset <em>Optional</em>. Offset for the next request. If empty, then there are no more results.
     */
    public static function make(int $total_count = null, array $gifts = null, string $next_offset = null): self
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