<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering a specific member of a group or supergroup chat.
 * @method self type(string $value) Scope type, must be <em>chat_member</em>
 * @method self chat_id(int|string $value) Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>). Channel direct messages chats and channel chats aren&#39;t supported.
 * @method self user_id(int $value) Unique identifier of the target user
 */
class BotCommandScopeChatMember
{
    public string $type;
    public int|string $chat_id;
    public int $user_id;

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
     * Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering a specific member of a group or supergroup chat.
     * @param string|null $type Scope type, must be <em>chat_member</em>
     * @param int|string|null $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>). Channel direct messages chats and channel chats aren&#39;t supported.
     * @param int|null $user_id Unique identifier of the target user
     */
    public static function make(string $type = null, int|string $chat_id = null, int $user_id = null): self
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