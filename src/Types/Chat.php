<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a chat.
 * @method self id(int $value) Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self type(string $value) Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
 * @method self title(string $value) <em>Optional</em>. Title, for supergroups, channels and group chats
 * @method self username(string $value) <em>Optional</em>. Username, for private chats, supergroups and channels if available
 * @method self first_name(string $value) <em>Optional</em>. First name of the other party in a private chat
 * @method self last_name(string $value) <em>Optional</em>. Last name of the other party in a private chat
 * @method self is_forum(True $value) <em>Optional</em>. <em>True</em>, if the supergroup chat is a forum (has <a href="https://telegram.org/blog/topics-in-groups-collectible-usernames#topics-in-groups">topics</a> enabled)
 * @method self is_direct_messages(True $value) <em>Optional</em>. <em>True</em>, if the chat is the direct messages chat of a channel
 */
class Chat
{
    public int $id;
    public string $type;
    public string $title;
    public string $username;
    public string $first_name;
    public string $last_name;
    public True $is_forum;
    public True $is_direct_messages;

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
     * This object represents a chat.
     * @param int|null $id Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param string|null $type Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
     * @param string|null $title <em>Optional</em>. Title, for supergroups, channels and group chats
     * @param string|null $username <em>Optional</em>. Username, for private chats, supergroups and channels if available
     * @param string|null $first_name <em>Optional</em>. First name of the other party in a private chat
     * @param string|null $last_name <em>Optional</em>. Last name of the other party in a private chat
     * @param True|null $is_forum <em>Optional</em>. <em>True</em>, if the supergroup chat is a forum (has <a href="https://telegram.org/blog/topics-in-groups-collectible-usernames#topics-in-groups">topics</a> enabled)
     * @param True|null $is_direct_messages <em>Optional</em>. <em>True</em>, if the chat is the direct messages chat of a channel
     */
    public static function make(int $id = null, string $type = null, string $title = null, string $username = null, string $first_name = null, string $last_name = null, True $is_forum = null, True $is_direct_messages = null): self
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