<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a join request sent to a chat.
 * @method self chat(Chat $value) Chat to which the request was sent
 * @method self from(User $value) User that sent the join request
 * @method self user_chat_id(int $value) Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
 * @method self date(int $value) Date the request was sent in Unix time
 * @method self bio(string $value) <em>Optional</em>. Bio of the user.
 * @method self invite_link(ChatInviteLink $value) <em>Optional</em>. Chat invite link that was used by the user to send the join request
 */
class ChatJoinRequest
{
    public Chat $chat;
    public User $from;
    public int $user_chat_id;
    public int $date;
    public string $bio;
    public ChatInviteLink $invite_link;

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
        if (isset($update['from'])) $this->from = new User($update['from']);
        if (isset($update['invite_link'])) $this->invite_link = new ChatInviteLink($update['invite_link']);
    }

    /**
     * Represents a join request sent to a chat.
     * @param Chat|null $chat Chat to which the request was sent
     * @param User|null $from User that sent the join request
     * @param int|null $user_chat_id Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
     * @param int|null $date Date the request was sent in Unix time
     * @param string|null $bio <em>Optional</em>. Bio of the user.
     * @param ChatInviteLink|null $invite_link <em>Optional</em>. Chat invite link that was used by the user to send the join request
     */
    public static function make(Chat $chat = null, User $from = null, int $user_chat_id = null, int $date = null, string $bio = null, ChatInviteLink $invite_link = null): self
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