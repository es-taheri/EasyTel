<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a transaction with a chat.
 * @method self type(string $value) Type of the transaction partner, always “chat”
 * @method self chat(Chat $value) Information about the chat
 * @method self gift(Gift $value) <em>Optional</em>. The gift sent to the chat by the bot
 */
class TransactionPartnerChat
{
    public string $type;
    public Chat $chat;
    public Gift $gift;

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
        if (isset($update['gift'])) $this->gift = new Gift($update['gift']);
    }

    /**
     * Describes a transaction with a chat.
     * @param string|null $type Type of the transaction partner, always “chat”
     * @param Chat|null $chat Information about the chat
     * @param Gift|null $gift <em>Optional</em>. The gift sent to the chat by the bot
     */
    public static function make(string $type = null, Chat $chat = null, Gift $gift = null): self
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