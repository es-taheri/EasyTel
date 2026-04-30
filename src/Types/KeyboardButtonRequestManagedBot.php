<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update <em>managed_bot</em> and a <a href="https://core.telegram.org/bots/api#message">Message</a> with the field <em>managed_bot_created</em>.
 * @method self request_id(int $value) Signed 32-bit identifier of the request. Must be unique within the message
 * @method self suggested_name(string $value) <em>Optional</em>. Suggested name for the bot
 * @method self suggested_username(string $value) <em>Optional</em>. Suggested username for the bot
 */
class KeyboardButtonRequestManagedBot
{
    public int $request_id;
    public string $suggested_name;
    public string $suggested_username;

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
     * This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update <em>managed_bot</em> and a <a href="https://core.telegram.org/bots/api#message">Message</a> with the field <em>managed_bot_created</em>.
     * @param int|null $request_id Signed 32-bit identifier of the request. Must be unique within the message
     * @param string|null $suggested_name <em>Optional</em>. Suggested name for the bot
     * @param string|null $suggested_username <em>Optional</em>. Suggested username for the bot
     */
    public static function make(int $request_id = null, string $suggested_name = null, string $suggested_username = null): self
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