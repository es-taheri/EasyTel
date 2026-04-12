<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about a chat boost.
 * @method self boost_id(string $value) Unique identifier of the boost
 * @method self add_date(int $value) Point in time (Unix timestamp) when the chat was boosted
 * @method self expiration_date(int $value) Point in time (Unix timestamp) when the boost will automatically expire, unless the booster&#39;s Telegram Premium subscription is prolonged
 * @method self source(ChatBoostSource $value) Source of the added boost
 */
class ChatBoost
{
    public string $boost_id;
    public int $add_date;
    public int $expiration_date;
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
        if (isset($update['source'])) $this->source = new ChatBoostSource($update['source']);
    }

    /**
     * This object contains information about a chat boost.
     * @param string|null $boost_id Unique identifier of the boost
     * @param int|null $add_date Point in time (Unix timestamp) when the chat was boosted
     * @param int|null $expiration_date Point in time (Unix timestamp) when the boost will automatically expire, unless the booster&#39;s Telegram Premium subscription is prolonged
     * @param ChatBoostSource|null $source Source of the added boost
     */
    public static function make(string $boost_id = null, int $add_date = null, int $expiration_date = null, ChatBoostSource $source = null): self
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