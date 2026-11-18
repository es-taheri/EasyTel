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
 * @method self invite_link(ChatInviteLink $value) <em>Optional</em>. Chat invite link, which was used by the user to join the chat; for joining by invite link events only
 * @method self via_join_request(bool $value) <em>Optional</em>. <em>True</em>, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
 * @method self via_chat_folder_invite_link(bool $value) <em>Optional</em>. <em>True</em>, if the user joined the chat via a chat folder invite link
 */
class ChatMemberUpdated
{
    /** @var Chat */
    public Chat $chat;
    /** @var User */
    public User $from;
    /** @var int */
    public int $date;
    /** @var ChatMember */
    public ChatMember $old_chat_member;
    /** @var ChatMember */
    public ChatMember $new_chat_member;
    /** @var ChatInviteLink */
    public ChatInviteLink $invite_link;
    /** @var bool */
    public bool $via_join_request;
    /** @var bool */
    public bool $via_chat_folder_invite_link;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents changes in the status of a chat member.
     * @param Chat|null $chat Chat the user belongs to
     * @param User|null $from Performer of the action, which resulted in the change
     * @param int|null $date Date the change was done in Unix time
     * @param ChatMember|null $old_chat_member Previous information about the chat member
     * @param ChatMember|null $new_chat_member New information about the chat member
     * @param ChatInviteLink|null $invite_link <em>Optional</em>. Chat invite link, which was used by the user to join the chat; for joining by invite link events only
     * @param bool|null $via_join_request <em>Optional</em>. <em>True</em>, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
     * @param bool|null $via_chat_folder_invite_link <em>Optional</em>. <em>True</em>, if the user joined the chat via a chat folder invite link
     */
    public static function make(Chat $chat = null, User $from = null, int $date = null, ChatMember $old_chat_member = null, ChatMember $new_chat_member = null, ChatInviteLink $invite_link = null, bool $via_join_request = null, bool $via_chat_folder_invite_link = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => $v !== null);
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
        $union = $r->hasConstant('_UNION_BASE');

        $normalize = function ($value) use (&$normalize) {
            if (is_object($value) && method_exists($value, '_output'))
                return (fn() => $this->_output(Telegram::OUTPUT_ARRAY))->bindTo($value, $value)();
            if (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item)
                    $items[$key] = $normalize($item);
                return $items;
            }
            return $value;
        };

        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (!isset($this->{$name}))
                continue;

            $raw = $property->getValue($this);
            $value = $normalize($raw);

            // A union base variant property represents the payload itself, so
            // its fields replace the variant key.
            if ($union && is_object($raw) && is_array($value)
                && $name === strtolower((new \ReflectionClass($raw))->getShortName())):
                $output += $value;
                continue;
            endif;

            $output[$name] = $value;
        endforeach;
        return Statics::output($output, $type);
    }
}