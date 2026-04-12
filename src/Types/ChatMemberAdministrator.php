<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has some additional privileges.
 * @method self status(string $value) The member&#39;s status in the chat, always “administrator”
 * @method self user(User $value) Information about the user
 * @method self can_be_edited(bool $value) <em>True</em>, if the bot is allowed to edit administrator privileges of that user
 * @method self is_anonymous(bool $value) <em>True</em>, if the user&#39;s presence in the chat is hidden
 * @method self can_manage_chat(bool $value) <em>True</em>, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
 * @method self can_delete_messages(bool $value) <em>True</em>, if the administrator can delete messages of other users
 * @method self can_manage_video_chats(bool $value) <em>True</em>, if the administrator can manage video chats
 * @method self can_restrict_members(bool $value) <em>True</em>, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
 * @method self can_promote_members(bool $value) <em>True</em>, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user)
 * @method self can_change_info(bool $value) <em>True</em>, if the user is allowed to change the chat title, photo and other settings
 * @method self can_invite_users(bool $value) <em>True</em>, if the user is allowed to invite new users to the chat
 * @method self can_post_stories(bool $value) <em>True</em>, if the administrator can post stories to the chat
 * @method self can_edit_stories(bool $value) <em>True</em>, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat&#39;s story archive
 * @method self can_delete_stories(bool $value) <em>True</em>, if the administrator can delete stories posted by other users
 * @method self can_post_messages(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
 * @method self can_edit_messages(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can edit messages of other users and can pin messages; for channels only
 * @method self can_pin_messages(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to pin messages; for groups and supergroups only
 * @method self can_manage_topics(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method self can_manage_direct_messages(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can manage direct messages of the channel and decline suggested posts; for channels only
 * @method self can_manage_tags(bool $value) <em>Optional</em>. <em>True</em>, if the administrator can edit the tags of regular members; for groups and supergroups only. If omitted defaults to the value of can_pin_messages.
 * @method self custom_title(string $value) <em>Optional</em>. Custom title for this user
 */
class ChatMemberAdministrator
{
    public string $status;
    public User $user;
    public bool $can_be_edited;
    public bool $is_anonymous;
    public bool $can_manage_chat;
    public bool $can_delete_messages;
    public bool $can_manage_video_chats;
    public bool $can_restrict_members;
    public bool $can_promote_members;
    public bool $can_change_info;
    public bool $can_invite_users;
    public bool $can_post_stories;
    public bool $can_edit_stories;
    public bool $can_delete_stories;
    public bool $can_post_messages;
    public bool $can_edit_messages;
    public bool $can_pin_messages;
    public bool $can_manage_topics;
    public bool $can_manage_direct_messages;
    public bool $can_manage_tags;
    public string $custom_title;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
    }

    /**
     * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has some additional privileges.
     * @param string|null $status The member&#39;s status in the chat, always “administrator”
     * @param User|null $user Information about the user
     * @param bool|null $can_be_edited <em>True</em>, if the bot is allowed to edit administrator privileges of that user
     * @param bool|null $is_anonymous <em>True</em>, if the user&#39;s presence in the chat is hidden
     * @param bool|null $can_manage_chat <em>True</em>, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
     * @param bool|null $can_delete_messages <em>True</em>, if the administrator can delete messages of other users
     * @param bool|null $can_manage_video_chats <em>True</em>, if the administrator can manage video chats
     * @param bool|null $can_restrict_members <em>True</em>, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
     * @param bool|null $can_promote_members <em>True</em>, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user)
     * @param bool|null $can_change_info <em>True</em>, if the user is allowed to change the chat title, photo and other settings
     * @param bool|null $can_invite_users <em>True</em>, if the user is allowed to invite new users to the chat
     * @param bool|null $can_post_stories <em>True</em>, if the administrator can post stories to the chat
     * @param bool|null $can_edit_stories <em>True</em>, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat&#39;s story archive
     * @param bool|null $can_delete_stories <em>True</em>, if the administrator can delete stories posted by other users
     * @param bool|null $can_post_messages <em>Optional</em>. <em>True</em>, if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
     * @param bool|null $can_edit_messages <em>Optional</em>. <em>True</em>, if the administrator can edit messages of other users and can pin messages; for channels only
     * @param bool|null $can_pin_messages <em>Optional</em>. <em>True</em>, if the user is allowed to pin messages; for groups and supergroups only
     * @param bool|null $can_manage_topics <em>Optional</em>. <em>True</em>, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
     * @param bool|null $can_manage_direct_messages <em>Optional</em>. <em>True</em>, if the administrator can manage direct messages of the channel and decline suggested posts; for channels only
     * @param bool|null $can_manage_tags <em>Optional</em>. <em>True</em>, if the administrator can edit the tags of regular members; for groups and supergroups only. If omitted defaults to the value of can_pin_messages.
     * @param string|null $custom_title <em>Optional</em>. Custom title for this user
     */
    public static function make(string $status = null, User $user = null, bool $can_be_edited = null, bool $is_anonymous = null, bool $can_manage_chat = null, bool $can_delete_messages = null, bool $can_manage_video_chats = null, bool $can_restrict_members = null, bool $can_promote_members = null, bool $can_change_info = null, bool $can_invite_users = null, bool $can_post_stories = null, bool $can_edit_stories = null, bool $can_delete_stories = null, bool $can_post_messages = null, bool $can_edit_messages = null, bool $can_pin_messages = null, bool $can_manage_topics = null, bool $can_manage_direct_messages = null, bool $can_manage_tags = null, string $custom_title = null): self
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