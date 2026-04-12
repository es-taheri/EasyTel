<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the types of gifts that can be gifted to a user or a chat.
 * @method self unlimited_gifts(bool $value) <em>True</em>, if unlimited regular gifts are accepted
 * @method self limited_gifts(bool $value) <em>True</em>, if limited regular gifts are accepted
 * @method self unique_gifts(bool $value) <em>True</em>, if unique gifts or gifts that can be upgraded to unique for free are accepted
 * @method self premium_subscription(bool $value) <em>True</em>, if a Telegram Premium subscription is accepted
 * @method self gifts_from_channels(bool $value) <em>True</em>, if transfers of unique gifts from channels are accepted
 */
class AcceptedGiftTypes
{
    public bool $unlimited_gifts;
    public bool $limited_gifts;
    public bool $unique_gifts;
    public bool $premium_subscription;
    public bool $gifts_from_channels;

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
        
    }

    /**
     * This object describes the types of gifts that can be gifted to a user or a chat.
     * @param bool|null $unlimited_gifts <em>True</em>, if unlimited regular gifts are accepted
     * @param bool|null $limited_gifts <em>True</em>, if limited regular gifts are accepted
     * @param bool|null $unique_gifts <em>True</em>, if unique gifts or gifts that can be upgraded to unique for free are accepted
     * @param bool|null $premium_subscription <em>True</em>, if a Telegram Premium subscription is accepted
     * @param bool|null $gifts_from_channels <em>True</em>, if transfers of unique gifts from channels are accepted
     */
    public static function make(bool $unlimited_gifts = null, bool $limited_gifts = null, bool $unique_gifts = null, bool $premium_subscription = null, bool $gifts_from_channels = null): self
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