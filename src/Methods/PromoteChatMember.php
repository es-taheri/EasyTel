<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass <em>False</em> for all boolean parameters to demote a user. Returns <em>True</em> on success.
 *
 * @method PromoteChatMember is_anonymous(bool $value) Pass <em>True</em> if the administrator's presence in the chat is hidden
 * @method PromoteChatMember can_manage_chat(bool $value) Pass <em>True</em> if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
 * @method PromoteChatMember can_delete_messages(bool $value) Pass <em>True</em> if the administrator can delete messages of other users
 * @method PromoteChatMember can_manage_video_chats(bool $value) Pass <em>True</em> if the administrator can manage video chats
 * @method PromoteChatMember can_restrict_members(bool $value) Pass <em>True</em> if the administrator can restrict, ban or unban chat members, or access supergroup statistics. For backward compatibility, defaults to <em>True</em> for promotions of channel administrators.
 * @method PromoteChatMember can_promote_members(bool $value) Pass <em>True</em> if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by him)
 * @method PromoteChatMember can_change_info(bool $value) Pass <em>True</em> if the administrator can change chat title, photo and other settings
 * @method PromoteChatMember can_invite_users(bool $value) Pass <em>True</em> if the administrator can invite new users to the chat
 * @method PromoteChatMember can_post_stories(bool $value) Pass <em>True</em> if the administrator can post stories to the chat
 * @method PromoteChatMember can_edit_stories(bool $value) Pass <em>True</em> if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
 * @method PromoteChatMember can_delete_stories(bool $value) Pass <em>True</em> if the administrator can delete stories posted by other users
 * @method PromoteChatMember can_post_messages(bool $value) Pass <em>True</em> if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
 * @method PromoteChatMember can_edit_messages(bool $value) Pass <em>True</em> if the administrator can edit messages of other users and can pin messages; for channels only
 * @method PromoteChatMember can_pin_messages(bool $value) Pass <em>True</em> if the administrator can pin messages; for supergroups only
 * @method PromoteChatMember can_manage_topics(bool $value) Pass <em>True</em> if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method PromoteChatMember can_manage_direct_messages(bool $value) Pass <em>True</em> if the administrator can manage direct messages within the channel and decline suggested posts; for channels only
 * @method PromoteChatMember can_manage_tags(bool $value) Pass <em>True</em> if the administrator can edit the tags of regular members; for groups and supergroups only
 * @method PromoteChatMember can_send_welcome_messages(bool $value) Pass <em>True</em> if the administrator can manage chat welcome messages or directly send them in the case of bots
 *
 * @link https://core.telegram.org/bots/api#promotechatmember
 */
class PromoteChatMember
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var int Unique identifier of the target user */
    private int $user_id;
    /** @var bool Pass <em>True</em> if the administrator's presence in the chat is hidden */
    private bool $is_anonymous;
    /** @var bool Pass <em>True</em> if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege. */
    private bool $can_manage_chat;
    /** @var bool Pass <em>True</em> if the administrator can delete messages of other users */
    private bool $can_delete_messages;
    /** @var bool Pass <em>True</em> if the administrator can manage video chats */
    private bool $can_manage_video_chats;
    /** @var bool Pass <em>True</em> if the administrator can restrict, ban or unban chat members, or access supergroup statistics. For backward compatibility, defaults to <em>True</em> for promotions of channel administrators. */
    private bool $can_restrict_members;
    /** @var bool Pass <em>True</em> if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by him) */
    private bool $can_promote_members;
    /** @var bool Pass <em>True</em> if the administrator can change chat title, photo and other settings */
    private bool $can_change_info;
    /** @var bool Pass <em>True</em> if the administrator can invite new users to the chat */
    private bool $can_invite_users;
    /** @var bool Pass <em>True</em> if the administrator can post stories to the chat */
    private bool $can_post_stories;
    /** @var bool Pass <em>True</em> if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive */
    private bool $can_edit_stories;
    /** @var bool Pass <em>True</em> if the administrator can delete stories posted by other users */
    private bool $can_delete_stories;
    /** @var bool Pass <em>True</em> if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only */
    private bool $can_post_messages;
    /** @var bool Pass <em>True</em> if the administrator can edit messages of other users and can pin messages; for channels only */
    private bool $can_edit_messages;
    /** @var bool Pass <em>True</em> if the administrator can pin messages; for supergroups only */
    private bool $can_pin_messages;
    /** @var bool Pass <em>True</em> if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only */
    private bool $can_manage_topics;
    /** @var bool Pass <em>True</em> if the administrator can manage direct messages within the channel and decline suggested posts; for channels only */
    private bool $can_manage_direct_messages;
    /** @var bool Pass <em>True</em> if the administrator can edit the tags of regular members; for groups and supergroups only */
    private bool $can_manage_tags;
    /** @var bool Pass <em>True</em> if the administrator can manage chat welcome messages or directly send them in the case of bots */
    private bool $can_send_welcome_messages;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format <code>@username</code>
     * @param int $user_id Unique identifier of the target user
     */
    public function __construct(Request $request, int|string $chat_id, int $user_id)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
    }

    public function __call(string $name, array $arguments)
    {
        if (!property_exists($this, $name)) {
            $this->_sent = true; // never send a half built request from __destruct()
            throw new \BadMethodCallException(
                sprintf('Unknown parameter "%s" for %s.', $name, static::class)
            );
        }

        if ($arguments === []) {
            $this->_sent = true;
            throw new \InvalidArgumentException(
                sprintf('Parameter "%s" of %s requires a value.', $name, static::class)
            );
        }

        try {
            $this->{$name} = array_shift($arguments);
        } catch (\Throwable $e) {
            $this->_sent = true; // the value did not fit: the request is invalid
            throw $e;
        }

        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];

        foreach ($this as $key => $value) {
            if (str_starts_with($key, '_') || !isset($this->{$key})) {
                continue;
            }

            if (is_object($value)) {
                $parameters[$key] = method_exists($value, '_output')
                    ? (fn() => $this->_output())->bindTo($value, $value)()
                    : $value;
            } else {
                $parameters[$key] = $value;
            }
        }

        $reflection = new \ReflectionClass($this);
        $this->_sent = true;

        return $this->_request->send(lcfirst($reflection->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if ($this->_sent)
            return;

        // Fire-and-forget call from the destructor must never raise inside
        // shutdown; the explicit _result() call is the error-reporting path.
        try {
            $this->_result();
        } catch (\Throwable) {
            // Ignored on purpose.
        }
    }
}
