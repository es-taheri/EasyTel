<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents reaction changes on a message with anonymous reactions.
 * @method self chat(Chat $value) The chat containing the message
 * @method self message_id(int $value) Unique message identifier inside the chat
 * @method self date(int $value) Date of the change in Unix time
 * @method self reactions(array $value) List of reactions that are present on the message
 */
class MessageReactionCountUpdated
{
    public Chat $chat;
    public int $message_id;
    public int $date;
    public array $reactions;

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
     * This object represents reaction changes on a message with anonymous reactions.
     * @param Chat|null $chat The chat containing the message
     * @param int|null $message_id Unique message identifier inside the chat
     * @param int|null $date Date of the change in Unix time
     * @param array|null $reactions List of reactions that are present on the message
     */
    public static function make(Chat $chat = null, int $message_id = null, int $date = null, array $reactions = null): self
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