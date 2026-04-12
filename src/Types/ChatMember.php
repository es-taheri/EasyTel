<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:

 */
class ChatMember
{
    public ChatMemberOwner $chatmemberowner;
    public ChatMemberAdministrator $chatmemberadministrator;
    public ChatMemberMember $chatmembermember;
    public ChatMemberRestricted $chatmemberrestricted;
    public ChatMemberLeft $chatmemberleft;
    public ChatMemberBanned $chatmemberbanned;

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
        $this->chatmemberowner = new ChatMemberOwner($update);
        $this->chatmemberadministrator = new ChatMemberAdministrator($update);
        $this->chatmembermember = new ChatMemberMember($update);
        $this->chatmemberrestricted = new ChatMemberRestricted($update);
        $this->chatmemberleft = new ChatMemberLeft($update);
        $this->chatmemberbanned = new ChatMemberBanned($update);
    }

    
    public static function make(ChatMemberOwner $chatmemberowner=null, ChatMemberAdministrator $chatmemberadministrator=null, ChatMemberMember $chatmembermember=null, ChatMemberRestricted $chatmemberrestricted=null, ChatMemberLeft $chatmemberleft=null, ChatMemberBanned $chatmemberbanned=null): self
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