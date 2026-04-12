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
    public Chat $chat;
    public string $boost_id;
    public int $remove_date;
    public ChatBoostSource $source;

    public function __construct(array $update = [])
    {
        $objects = array_keys($update);
        $r = new \ReflectionClass(static::class);
        foreach ($objects as $object):
            if ($r->hasProperty($object)):
                $prop = $r->getProperty($object);
                $type = $prop->getType();
                if (in_array(strtolower(trim($type)), ['string', 'true', 'false', 'bool', 'int', 'float', 'array', 'mixed']) || str_contains($type, '|'))
                    $this->{$object} = $update[$object];
            endif;
        endforeach;
        if (isset($update['chat'])) $this->chat = new Chat($update['chat']);
        if (isset($update['source'])) $this->source = new ChatBoostSource($update['source']);
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
        $updates = array_filter($args, fn($v) => isset($v));
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
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (isset($this->{$name})):
                $value = $property->getValue($this);
                $property_type = $property->getType();
                if ($property_type == 'object')
                    $output[$name] = (fn() => ($this->_output($type)))->bindTo($value, $value)();
                else
                    $output[$name] = $value;
            endif;
        endforeach;
        return Statics::output($output, $type);
    }
}