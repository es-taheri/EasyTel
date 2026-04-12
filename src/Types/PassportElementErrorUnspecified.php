<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents an issue in an unspecified place. The error is considered resolved when new data is added.
 * @method self source(string $value) Error source, must be <em>unspecified</em>
 * @method self type(string $value) Type of element of the user&#39;s Telegram Passport which has the issue
 * @method self element_hash(string $value) Base64-encoded element hash
 * @method self message(string $value) Error message
 */
class PassportElementErrorUnspecified
{
    public string $source;
    public string $type;
    public string $element_hash;
    public string $message;

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
     * Represents an issue in an unspecified place. The error is considered resolved when new data is added.
     * @param string|null $source Error source, must be <em>unspecified</em>
     * @param string|null $type Type of element of the user&#39;s Telegram Passport which has the issue
     * @param string|null $element_hash Base64-encoded element hash
     * @param string|null $message Error message
     */
    public static function make(string $source = null, string $type = null, string $element_hash = null, string $message = null): self
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