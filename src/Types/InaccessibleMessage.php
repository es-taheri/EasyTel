<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes a message that was deleted or is otherwise inaccessible to the bot.
 * @method self chat(Chat $value) Chat the message belonged to
 * @method self message_id(int $value) Unique message identifier inside the chat
 * @method self date(int $value) Always 0. The field can be used to differentiate regular and inaccessible messages.
 */
class InaccessibleMessage
{
    public Chat $chat;
    public int $message_id;
    public int $date;

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
     * This object describes a message that was deleted or is otherwise inaccessible to the bot.
     * @param Chat|null $chat Chat the message belonged to
     * @param int|null $message_id Unique message identifier inside the chat
     * @param int|null $date Always 0. The field can be used to differentiate regular and inaccessible messages.
     */
    public static function make(Chat $chat = null, int $message_id = null, int $date = null): self
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