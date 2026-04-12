<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Contains the list of gifts received and owned by a user or a chat.
 * @method self total_count(int $value) The total number of gifts owned by the user or the chat
 * @method self gifts(array $value) The list of gifts
 * @method self next_offset(string $value) <em>Optional</em>. Offset for the next request. If empty, then there are no more results
 */
class OwnedGifts
{
    public int $total_count;
    public array $gifts;
    public string $next_offset;

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
     * Contains the list of gifts received and owned by a user or a chat.
     * @param int|null $total_count The total number of gifts owned by the user or the chat
     * @param array|null $gifts The list of gifts
     * @param string|null $next_offset <em>Optional</em>. Offset for the next request. If empty, then there are no more results
     */
    public static function make(int $total_count = null, array $gifts = null, string $next_offset = null): self
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