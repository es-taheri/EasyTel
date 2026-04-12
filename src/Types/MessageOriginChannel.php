<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The message was originally sent to a channel chat.
 * @method self type(string $value) Type of the message origin, always “channel”
 * @method self date(int $value) Date the message was sent originally in Unix time
 * @method self chat(Chat $value) Channel chat to which the message was originally sent
 * @method self message_id(int $value) Unique message identifier inside the chat
 * @method self author_signature(string $value) <em>Optional</em>. Signature of the original post author
 */
class MessageOriginChannel
{
    public string $type;
    public int $date;
    public Chat $chat;
    public int $message_id;
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
        if (isset($update['chat'])) $this->chat = new Chat($update['chat']);
    }

    /**
     * The message was originally sent to a channel chat.
     * @param string|null $type Type of the message origin, always “channel”
     * @param int|null $date Date the message was sent originally in Unix time
     * @param Chat|null $chat Channel chat to which the message was originally sent
     * @param int|null $message_id Unique message identifier inside the chat
     * @param string|null $author_signature <em>Optional</em>. Signature of the original post author
     */
    public static function make(string $type = null, int $date = null, Chat $chat = null, int $message_id = null, string $author_signature = null): self
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