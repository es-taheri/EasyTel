<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents an issue in one of the data fields that was provided by the user. The error is considered resolved when the field&#39;s value changes.
 * @method self source(string $value) Error source, must be <em>data</em>
 * @method self type(string $value) The section of the user&#39;s Telegram Passport which has the error, one of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”
 * @method self field_name(string $value) Name of the data field which has the error
 * @method self data_hash(string $value) Base64-encoded data hash
 * @method self message(string $value) Error message
 */
class PassportElementErrorDataField
{
    public string $source;
    public string $type;
    public string $field_name;
    public string $data_hash;
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
     * Represents an issue in one of the data fields that was provided by the user. The error is considered resolved when the field&#39;s value changes.
     * @param string|null $source Error source, must be <em>data</em>
     * @param string|null $type The section of the user&#39;s Telegram Passport which has the error, one of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”
     * @param string|null $field_name Name of the data field which has the error
     * @param string|null $data_hash Base64-encoded data hash
     * @param string|null $message Error message
     */
    public static function make(string $source = null, string $type = null, string $field_name = null, string $data_hash = null, string $message = null): self
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