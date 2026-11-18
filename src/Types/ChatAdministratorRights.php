<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the rights of an administrator in a chat.
 * @method self is_anonymous(bool $value) <em>True</em>, if the user's presence in the chat is hidden
 * @method self can_manage_chat(bool $value) <em>True</em>, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
 * @method self can_delete_messages(bool $value) <em>True</em>, if the administrator can delete messages of other users
 * @method self can_manage_video_chats(bool $value) <em>True</em>, if the administrator can manage video chats
 * @method self can_restrict_members(bool $value) <em>True</em>, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
 * @method self can_promote_members(bool $value) <em>True</em>, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user)
 * @method self can_change_info(bool $value) <em>True</em>, if the user is allowed to change the chat title, photo and other settings
 * @method self can_invite_users(bool $value) <em>True</em>, if the user is allowed to invite new users to the chat
 * @method self can_post_stories(bool $value) <em>True</em>, if the administrator can post stories to the chat
 * @method self can_edit_stories(bool $value) <em>True</em>, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
 * @method self can_delete_stories(bool $value) <em>True</em>, if the administrator can delete stories posted by other users
 * @method self can_post_messages(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
 * @method self can_edit_messages(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can edit messages of other users and can pin messages; for channels only
 * @method self can_pin_messages(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to pin messages; for groups and supergroups only
 * @method self can_manage_topics(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method self can_manage_direct_messages(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can manage direct messages of the channel and decline suggested posts; for channels only
 * @method self can_manage_tags(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can edit the tags of regular members; for groups and supergroups only
 * @method self can_send_welcome_messages(bool $value) <em>True</em>, if the administrator can manage chat welcome messages or directly send them in the case of bots
 */
class ChatAdministratorRights
{
    /** @var bool */
    public bool $is_anonymous;
    /** @var bool */
    public bool $can_manage_chat;
    /** @var bool */
    public bool $can_delete_messages;
    /** @var bool */
    public bool $can_manage_video_chats;
    /** @var bool */
    public bool $can_restrict_members;
    /** @var bool */
    public bool $can_promote_members;
    /** @var bool */
    public bool $can_change_info;
    /** @var bool */
    public bool $can_invite_users;
    /** @var bool */
    public bool $can_post_stories;
    /** @var bool */
    public bool $can_edit_stories;
    /** @var bool */
    public bool $can_delete_stories;
    /** @var bool */
    public bool $can_post_messages;
    /** @var bool */
    public bool $can_edit_messages;
    /** @var bool */
    public bool $can_pin_messages;
    /** @var bool */
    public bool $can_manage_topics;
    /** @var bool */
    public bool $can_manage_direct_messages;
    /** @var bool */
    public bool $can_manage_tags;
    /** @var bool */
    public bool $can_send_welcome_messages;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents the rights of an administrator in a chat.
     * @param bool|null $is_anonymous <em>True</em>, if the user's presence in the chat is hidden
     * @param bool|null $can_manage_chat <em>True</em>, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
     * @param bool|null $can_delete_messages <em>True</em>, if the administrator can delete messages of other users
     * @param bool|null $can_manage_video_chats <em>True</em>, if the administrator can manage video chats
     * @param bool|null $can_restrict_members <em>True</em>, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
     * @param bool|null $can_promote_members <em>True</em>, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user)
     * @param bool|null $can_change_info <em>True</em>, if the user is allowed to change the chat title, photo and other settings
     * @param bool|null $can_invite_users <em>True</em>, if the user is allowed to invite new users to the chat
     * @param bool|null $can_post_stories <em>True</em>, if the administrator can post stories to the chat
     * @param bool|null $can_edit_stories <em>True</em>, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
     * @param bool|null $can_delete_stories <em>True</em>, if the administrator can delete stories posted by other users
     * @param bool|null $can_post_messages <em>Optional</em>. <em>True</em>, if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
     * @param bool|null $can_edit_messages <em>Optional</em>. <em>True</em>, if the administrator can edit messages of other users and can pin messages; for channels only
     * @param bool|null $can_pin_messages <em>Optional</em>. <em>True</em>, if the user is allowed to pin messages; for groups and supergroups only
     * @param bool|null $can_manage_topics <em>Optional</em>. <em>True</em>, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
     * @param bool|null $can_manage_direct_messages <em>Optional</em>. <em>True</em>, if the administrator can manage direct messages of the channel and decline suggested posts; for channels only
     * @param bool|null $can_manage_tags <em>Optional</em>. <em>True</em>, if the administrator can edit the tags of regular members; for groups and supergroups only
     * @param bool|null $can_send_welcome_messages <em>True</em>, if the administrator can manage chat welcome messages or directly send them in the case of bots
     */
    public static function make(bool $is_anonymous = null, bool $can_manage_chat = null, bool $can_delete_messages = null, bool $can_manage_video_chats = null, bool $can_restrict_members = null, bool $can_promote_members = null, bool $can_change_info = null, bool $can_invite_users = null, bool $can_post_stories = null, bool $can_edit_stories = null, bool $can_delete_stories = null, bool $can_post_messages = null, bool $can_edit_messages = null, bool $can_pin_messages = null, bool $can_manage_topics = null, bool $can_manage_direct_messages = null, bool $can_manage_tags = null, bool $can_send_welcome_messages = null): self
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