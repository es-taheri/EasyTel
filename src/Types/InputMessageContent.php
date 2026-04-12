<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the content of a message to be sent as a result of an inline query. Telegram clients currently support the following 5 types:

 */
class InputMessageContent
{
    public InputTextMessageContent $inputtextmessagecontent;
    public InputLocationMessageContent $inputlocationmessagecontent;
    public InputVenueMessageContent $inputvenuemessagecontent;
    public InputContactMessageContent $inputcontactmessagecontent;
    public InputInvoiceMessageContent $inputinvoicemessagecontent;

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
        $this->inputtextmessagecontent = new InputTextMessageContent($update);
        $this->inputlocationmessagecontent = new InputLocationMessageContent($update);
        $this->inputvenuemessagecontent = new InputVenueMessageContent($update);
        $this->inputcontactmessagecontent = new InputContactMessageContent($update);
        $this->inputinvoicemessagecontent = new InputInvoiceMessageContent($update);
    }

    
    public static function make(InputTextMessageContent $inputtextmessagecontent=null, InputLocationMessageContent $inputlocationmessagecontent=null, InputVenueMessageContent $inputvenuemessagecontent=null, InputContactMessageContent $inputcontactmessagecontent=null, InputInvoiceMessageContent $inputinvoicemessagecontent=null): self
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