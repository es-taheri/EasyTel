<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents changes in the status of a chat member.
 * @method self chat(Chat $value) Chat the user belongs to
 * @method self from(User $value) Performer of the action, which resulted in the change
 * @method self date(int $value) Date the change was done in Unix time
 * @method self old_chat_member(ChatMember $value) Previous information about the chat member
 * @method self new_chat_member(ChatMember $value) New information about the chat member
 * @method self invite_link(ChatInviteLink $value) <em>Optional</em>. Chat invite link, which was used by the user to join the chat; for joining by invite link events only.
 * @method self via_join_request(bool $value) <em>Optional</em>. <em>True</em>, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
 * @method self via_chat_folder_invite_link(bool $value) <em>Optional</em>. <em>True</em>, if the user joined the chat via a chat folder invite link
 */
class ChatMemberUpdated
{
    public Chat $chat;
    public User $from;
    public int $date;
    public ChatMember $old_chat_member;
    public ChatMember $new_chat_member;
    public ChatInviteLink $invite_link;
    public bool $via_join_request;
    public bool $via_chat_folder_invite_link;

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
        if (isset($update['old_chat_member'])) $this->old_chat_member = new ChatMember($update['old_chat_member']);
        if (isset($update['new_chat_member'])) $this->new_chat_member = new ChatMember($update['new_chat_member']);
        if (isset($update['invite_link'])) $this->invite_link = new ChatInviteLink($update['invite_link']);
    }

    /**
     * This object represents changes in the status of a chat member.
     * @param Chat|null $chat Chat the user belongs to
     * @param User|null $from Performer of the action, which resulted in the change
     * @param int|null $date Date the change was done in Unix time
     * @param ChatMember|null $old_chat_member Previous information about the chat member
     * @param ChatMember|null $new_chat_member New information about the chat member
     * @param ChatInviteLink|null $invite_link <em>Optional</em>. Chat invite link, which was used by the user to join the chat; for joining by invite link events only.
     * @param bool|null $via_join_request <em>Optional</em>. <em>True</em>, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
     * @param bool|null $via_chat_folder_invite_link <em>Optional</em>. <em>True</em>, if the user joined the chat via a chat folder invite link
     */
    public static function make(Chat $chat = null, User $from = null, int $date = null, ChatMember $old_chat_member = null, ChatMember $new_chat_member = null, ChatInviteLink $invite_link = null, bool $via_join_request = null, bool $via_chat_folder_invite_link = null): self
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