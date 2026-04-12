<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the scope to which bot commands are applied. Currently, the following 7 scopes are supported:

 */
class BotCommandScope
{
    public BotCommandScopeDefault $botcommandscopedefault;
    public BotCommandScopeAllPrivateChats $botcommandscopeallprivatechats;
    public BotCommandScopeAllGroupChats $botcommandscopeallgroupchats;
    public BotCommandScopeAllChatAdministrators $botcommandscopeallchatadministrators;
    public BotCommandScopeChat $botcommandscopechat;
    public BotCommandScopeChatAdministrators $botcommandscopechatadministrators;
    public BotCommandScopeChatMember $botcommandscopechatmember;

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
        $this->botcommandscopedefault = new BotCommandScopeDefault($update);
        $this->botcommandscopeallprivatechats = new BotCommandScopeAllPrivateChats($update);
        $this->botcommandscopeallgroupchats = new BotCommandScopeAllGroupChats($update);
        $this->botcommandscopeallchatadministrators = new BotCommandScopeAllChatAdministrators($update);
        $this->botcommandscopechat = new BotCommandScopeChat($update);
        $this->botcommandscopechatadministrators = new BotCommandScopeChatAdministrators($update);
        $this->botcommandscopechatmember = new BotCommandScopeChatMember($update);
    }

    
    public static function make(BotCommandScopeDefault $botcommandscopedefault=null, BotCommandScopeAllPrivateChats $botcommandscopeallprivatechats=null, BotCommandScopeAllGroupChats $botcommandscopeallgroupchats=null, BotCommandScopeAllChatAdministrators $botcommandscopeallchatadministrators=null, BotCommandScopeChat $botcommandscopechat=null, BotCommandScopeChatAdministrators $botcommandscopechatadministrators=null, BotCommandScopeChatMember $botcommandscopechatmember=null): self
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