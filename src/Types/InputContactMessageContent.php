<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a contact message to be sent as the result of an inline query.
 * @method self phone_number(string $value) Contact&#39;s phone number
 * @method self first_name(string $value) Contact&#39;s first name
 * @method self last_name(string $value) <em>Optional</em>. Contact&#39;s last name
 * @method self vcard(string $value) <em>Optional</em>. Additional data about the contact in the form of a <a href="https://en.wikipedia.org/wiki/VCard">vCard</a>, 0-2048 bytes
 */
class InputContactMessageContent
{
    public string $phone_number;
    public string $first_name;
    public string $last_name;
    public string $vcard;

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
     * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a contact message to be sent as the result of an inline query.
     * @param string|null $phone_number Contact&#39;s phone number
     * @param string|null $first_name Contact&#39;s first name
     * @param string|null $last_name <em>Optional</em>. Contact&#39;s last name
     * @param string|null $vcard <em>Optional</em>. Additional data about the contact in the form of a <a href="https://en.wikipedia.org/wiki/VCard">vCard</a>, 0-2048 bytes
     */
    public static function make(string $phone_number = null, string $first_name = null, string $last_name = null, string $vcard = null): self
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