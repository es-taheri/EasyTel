<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a boost removed from a chat.
 * @method self chat(Chat $value) Chat which was boosted
 * @method self boost_id(string $value) Unique identifier of the boost
 * @method self remove_date(int $value) Point in time (Unix timestamp) when the boost was removed
 * @method self source(ChatBoostSource $value) Source of the removed boost
 */
class ChatBoostRemoved
{
    /** @var Chat */
    public Chat $chat;
    /** @var string */
    public string $boost_id;
    /** @var int */
    public int $remove_date;
    /** @var ChatBoostSource */
    public ChatBoostSource $source;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a boost removed from a chat.
     * @param Chat|null $chat Chat which was boosted
     * @param string|null $boost_id Unique identifier of the boost
     * @param int|null $remove_date Point in time (Unix timestamp) when the boost was removed
     * @param ChatBoostSource|null $source Source of the removed boost
     */
    public static function make(Chat $chat = null, string $boost_id = null, int $remove_date = null, ChatBoostSource $source = null): self
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