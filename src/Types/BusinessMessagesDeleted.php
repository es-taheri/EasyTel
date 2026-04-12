<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object is received when messages are deleted from a connected business account.
 * @method self business_connection_id(string $value) Unique identifier of the business connection
 * @method self chat(Chat $value) Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
 * @method self message_ids(array $value) The list of identifiers of deleted messages in the chat of the business account
 */
class BusinessMessagesDeleted
{
    public string $business_connection_id;
    public Chat $chat;
    public array $message_ids;

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
    }

    /**
     * This object is received when messages are deleted from a connected business account.
     * @param string|null $business_connection_id Unique identifier of the business connection
     * @param Chat|null $chat Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
     * @param array|null $message_ids The list of identifiers of deleted messages in the chat of the business account
     */
    public static function make(string $business_connection_id = null, Chat $chat = null, array $message_ids = null): self
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