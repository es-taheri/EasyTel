<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The message was originally sent on behalf of a chat to a group chat.
 * @method self type(string $value) Type of the message origin, always “chat”
 * @method self date(int $value) Date the message was sent originally in Unix time
 * @method self sender_chat(Chat $value) Chat that sent the message originally
 * @method self author_signature(string $value) <em>Optional</em>. For messages originally sent by an anonymous chat administrator, original message author signature
 */
class MessageOriginChat
{
    public string $type;
    public int $date;
    public Chat $sender_chat;
    public string $author_signature;

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
        if (isset($update['sender_chat'])) $this->sender_chat = new Chat($update['sender_chat']);
    }

    /**
     * The message was originally sent on behalf of a chat to a group chat.
     * @param string|null $type Type of the message origin, always “chat”
     * @param int|null $date Date the message was sent originally in Unix time
     * @param Chat|null $sender_chat Chat that sent the message originally
     * @param string|null $author_signature <em>Optional</em>. For messages originally sent by an anonymous chat administrator, original message author signature
     */
    public static function make(string $type = null, int $date = null, Chat $sender_chat = null, string $author_signature = null): self
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