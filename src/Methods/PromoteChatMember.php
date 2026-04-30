<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method PromoteChatMember is_anonymous(bool $value) Pass <em>True</em> if the administrator&#39;s presence in the chat is hidden
 * @method PromoteChatMember can_manage_chat(bool $value) Pass <em>True</em> if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
 * @method PromoteChatMember can_delete_messages(bool $value) Pass <em>True</em> if the administrator can delete messages of other users
 * @method PromoteChatMember can_manage_video_chats(bool $value) Pass <em>True</em> if the administrator can manage video chats
 * @method PromoteChatMember can_restrict_members(bool $value) Pass <em>True</em> if the administrator can restrict, ban or unban chat members, or access supergroup statistics. For backward compatibility, defaults to <em>True</em> for promotions of channel administrators
 * @method PromoteChatMember can_promote_members(bool $value) Pass <em>True</em> if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by him)
 * @method PromoteChatMember can_change_info(bool $value) Pass <em>True</em> if the administrator can change chat title, photo and other settings
 * @method PromoteChatMember can_invite_users(bool $value) Pass <em>True</em> if the administrator can invite new users to the chat
 * @method PromoteChatMember can_post_stories(bool $value) Pass <em>True</em> if the administrator can post stories to the chat
 * @method PromoteChatMember can_edit_stories(bool $value) Pass <em>True</em> if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat&#39;s story archive
 * @method PromoteChatMember can_delete_stories(bool $value) Pass <em>True</em> if the administrator can delete stories posted by other users
 * @method PromoteChatMember can_post_messages(bool $value) Pass <em>True</em> if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
 * @method PromoteChatMember can_edit_messages(bool $value) Pass <em>True</em> if the administrator can edit messages of other users and can pin messages; for channels only
 * @method PromoteChatMember can_pin_messages(bool $value) Pass <em>True</em> if the administrator can pin messages; for supergroups only
 * @method PromoteChatMember can_manage_topics(bool $value) Pass <em>True</em> if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method PromoteChatMember can_manage_direct_messages(bool $value) Pass <em>True</em> if the administrator can manage direct messages within the channel and decline suggested posts; for channels only
 * @method PromoteChatMember can_manage_tags(bool $value) Pass <em>True</em> if the administrator can edit the tags of regular members; for groups and supergroups only
 */
class PromoteChatMember
{
    private Request $_request;
    private bool $_sent = false;
    private int|string $chat_id;
    private int $user_id;
    private bool $is_anonymous;
    private bool $can_manage_chat;
    private bool $can_delete_messages;
    private bool $can_manage_video_chats;
    private bool $can_restrict_members;
    private bool $can_promote_members;
    private bool $can_change_info;
    private bool $can_invite_users;
    private bool $can_post_stories;
    private bool $can_edit_stories;
    private bool $can_delete_stories;
    private bool $can_post_messages;
    private bool $can_edit_messages;
    private bool $can_pin_messages;
    private bool $can_manage_topics;
    private bool $can_manage_direct_messages;
    private bool $can_manage_tags;

    public function __construct(Request $request, int|string $chat_id, int $user_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];
        foreach ($this as $key => $value):
            if (isset($this->{$key}) && !in_array($key, ['_request', '_result'])):
                if (gettype($value) == 'object')
                    $parameters[$key] = (fn() => ($this->_output()))->bindTo($value, $value)();
                else
                    $parameters[$key] = $value;
            endif;
        endforeach;
        $r = new \ReflectionClass($this);
        $this->_sent = true;
        return $this->_request->send(lcfirst($r->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if (!$this->_sent) $this->_result();
    }
}