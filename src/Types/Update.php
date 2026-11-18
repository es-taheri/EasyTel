<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This <a href="https://core.telegram.org/bots/api#available-types">object</a> represents an incoming update.<br>At most <strong>one</strong> of the optional fields can be present in any given update.
 * @method self update_id(int $value) The update's unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you're using <a href="https://core.telegram.org/bots/api#setwebhook">webhooks</a>, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
 * @method self message(Message $value) <em>Optional</em>. New incoming message of any kind - text, photo, sticker, etc.
 * @method self edited_message(Message $value) <em>Optional</em>. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method self channel_post(Message $value) <em>Optional</em>. New incoming channel post of any kind - text, photo, sticker, etc.
 * @method self edited_channel_post(Message $value) <em>Optional</em>. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method self business_connection(BusinessConnection $value) <em>Optional</em>. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
 * @method self business_message(Message $value) <em>Optional</em>. New message from a connected business account
 * @method self edited_business_message(Message $value) <em>Optional</em>. New version of a message from a connected business account
 * @method self deleted_business_messages(BusinessMessagesDeleted $value) <em>Optional</em>. Messages were deleted from a connected business account
 * @method self guest_message(Message $value) <em>Optional</em>. New guest message. The bot can use the field <em>Message.guest_query_id</em> and the method <a href="https://core.telegram.org/bots/api#answerguestquery">answerGuestQuery</a> to send a message in response.
 * @method self message_reaction(MessageReactionUpdated $value) <em>Optional</em>. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify <code>"message_reaction"</code> in the list of <em>allowed_updates</em> to receive these updates. The update isn't received for reactions set by bots.
 * @method self message_reaction_count(MessageReactionCountUpdated $value) <em>Optional</em>. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify <code>"message_reaction_count"</code> in the list of <em>allowed_updates</em> to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
 * @method self inline_query(InlineQuery $value) <em>Optional</em>. New incoming <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query
 * @method self chosen_inline_result(ChosenInlineResult $value) <em>Optional</em>. The result of an <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query that was chosen by a user and sent to their chat partner. Please see our documentation on the <a href="/bots/inline#collecting-feedback">feedback collecting</a> for details on how to enable these updates for your bot.
 * @method self callback_query(CallbackQuery $value) <em>Optional</em>. New incoming callback query
 * @method self widget_query(WidgetQuery $value) <em>Optional</em>. New incoming query from a widget in a message sent by the bot or via the bot
 * @method self shared_widget_query(SharedWidgetQuery $value) <em>Optional</em>. New incoming query from a forwarded message with a shared widget
 * @method self shipping_query(ShippingQuery $value) <em>Optional</em>. New incoming shipping query. Only for invoices with flexible price.
 * @method self pre_checkout_query(PreCheckoutQuery $value) <em>Optional</em>. New incoming pre-checkout query. Contains full information about checkout.
 * @method self purchased_paid_media(PaidMediaPurchased $value) <em>Optional</em>. A user purchased paid media with a non-empty payload sent by the bot in a non-channel chat
 * @method self poll(Poll $value) <em>Optional</em>. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot.
 * @method self poll_answer(PollAnswer $value) <em>Optional</em>. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * @method self my_chat_member(ChatMemberUpdated $value) <em>Optional</em>. The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
 * @method self chat_member(ChatMemberUpdated $value) <em>Optional</em>. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify <code>"chat_member"</code> in the list of <em>allowed_updates</em> to receive these updates.
 * @method self chat_join_request(ChatJoinRequest $value) <em>Optional</em>. A request to join the chat has been sent. The bot must have the <em>can_invite_users</em> administrator right in the chat to receive these updates.
 * @method self chat_boost(ChatBoostUpdated $value) <em>Optional</em>. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
 * @method self removed_chat_boost(ChatBoostRemoved $value) <em>Optional</em>. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
 * @method self managed_bot(ManagedBotUpdated $value) <em>Optional</em>. A new bot was created to be managed by the bot, or token or owner of a managed bot was changed
 * @method self subscription(BotSubscriptionUpdated $value) <em>Optional</em>. User payment subscription has changed
 * @method self stopped_message_generation(MessageGenerationStopped $value) <em>Optional</em>. A user asked the bot to stop the generation of a message
 */
class Update
{
    /** @var int */
    public int $update_id;
    /** @var Message */
    public Message $message;
    /** @var Message */
    public Message $edited_message;
    /** @var Message */
    public Message $channel_post;
    /** @var Message */
    public Message $edited_channel_post;
    /** @var BusinessConnection */
    public BusinessConnection $business_connection;
    /** @var Message */
    public Message $business_message;
    /** @var Message */
    public Message $edited_business_message;
    /** @var BusinessMessagesDeleted */
    public BusinessMessagesDeleted $deleted_business_messages;
    /** @var Message */
    public Message $guest_message;
    /** @var MessageReactionUpdated */
    public MessageReactionUpdated $message_reaction;
    /** @var MessageReactionCountUpdated */
    public MessageReactionCountUpdated $message_reaction_count;
    /** @var InlineQuery */
    public InlineQuery $inline_query;
    /** @var ChosenInlineResult */
    public ChosenInlineResult $chosen_inline_result;
    /** @var CallbackQuery */
    public CallbackQuery $callback_query;
    /** @var WidgetQuery */
    public WidgetQuery $widget_query;
    /** @var SharedWidgetQuery */
    public SharedWidgetQuery $shared_widget_query;
    /** @var ShippingQuery */
    public ShippingQuery $shipping_query;
    /** @var PreCheckoutQuery */
    public PreCheckoutQuery $pre_checkout_query;
    /** @var PaidMediaPurchased */
    public PaidMediaPurchased $purchased_paid_media;
    /** @var Poll */
    public Poll $poll;
    /** @var PollAnswer */
    public PollAnswer $poll_answer;
    /** @var ChatMemberUpdated */
    public ChatMemberUpdated $my_chat_member;
    /** @var ChatMemberUpdated */
    public ChatMemberUpdated $chat_member;
    /** @var ChatJoinRequest */
    public ChatJoinRequest $chat_join_request;
    /** @var ChatBoostUpdated */
    public ChatBoostUpdated $chat_boost;
    /** @var ChatBoostRemoved */
    public ChatBoostRemoved $removed_chat_boost;
    /** @var ManagedBotUpdated */
    public ManagedBotUpdated $managed_bot;
    /** @var BotSubscriptionUpdated */
    public BotSubscriptionUpdated $subscription;
    /** @var MessageGenerationStopped */
    public MessageGenerationStopped $stopped_message_generation;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This <a href="https://core.telegram.org/bots/api#available-types">object</a> represents an incoming update.<br>At most <strong>one</strong> of the optional fields can be present in any given update.
     * @param int|null $update_id The update's unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you're using <a href="https://core.telegram.org/bots/api#setwebhook">webhooks</a>, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
     * @param Message|null $message <em>Optional</em>. New incoming message of any kind - text, photo, sticker, etc.
     * @param Message|null $edited_message <em>Optional</em>. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
     * @param Message|null $channel_post <em>Optional</em>. New incoming channel post of any kind - text, photo, sticker, etc.
     * @param Message|null $edited_channel_post <em>Optional</em>. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
     * @param BusinessConnection|null $business_connection <em>Optional</em>. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
     * @param Message|null $business_message <em>Optional</em>. New message from a connected business account
     * @param Message|null $edited_business_message <em>Optional</em>. New version of a message from a connected business account
     * @param BusinessMessagesDeleted|null $deleted_business_messages <em>Optional</em>. Messages were deleted from a connected business account
     * @param Message|null $guest_message <em>Optional</em>. New guest message. The bot can use the field <em>Message.guest_query_id</em> and the method <a href="https://core.telegram.org/bots/api#answerguestquery">answerGuestQuery</a> to send a message in response.
     * @param MessageReactionUpdated|null $message_reaction <em>Optional</em>. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify <code>"message_reaction"</code> in the list of <em>allowed_updates</em> to receive these updates. The update isn't received for reactions set by bots.
     * @param MessageReactionCountUpdated|null $message_reaction_count <em>Optional</em>. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify <code>"message_reaction_count"</code> in the list of <em>allowed_updates</em> to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
     * @param InlineQuery|null $inline_query <em>Optional</em>. New incoming <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query
     * @param ChosenInlineResult|null $chosen_inline_result <em>Optional</em>. The result of an <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query that was chosen by a user and sent to their chat partner. Please see our documentation on the <a href="/bots/inline#collecting-feedback">feedback collecting</a> for details on how to enable these updates for your bot.
     * @param CallbackQuery|null $callback_query <em>Optional</em>. New incoming callback query
     * @param WidgetQuery|null $widget_query <em>Optional</em>. New incoming query from a widget in a message sent by the bot or via the bot
     * @param SharedWidgetQuery|null $shared_widget_query <em>Optional</em>. New incoming query from a forwarded message with a shared widget
     * @param ShippingQuery|null $shipping_query <em>Optional</em>. New incoming shipping query. Only for invoices with flexible price.
     * @param PreCheckoutQuery|null $pre_checkout_query <em>Optional</em>. New incoming pre-checkout query. Contains full information about checkout.
     * @param PaidMediaPurchased|null $purchased_paid_media <em>Optional</em>. A user purchased paid media with a non-empty payload sent by the bot in a non-channel chat
     * @param Poll|null $poll <em>Optional</em>. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot.
     * @param PollAnswer|null $poll_answer <em>Optional</em>. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
     * @param ChatMemberUpdated|null $my_chat_member <em>Optional</em>. The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
     * @param ChatMemberUpdated|null $chat_member <em>Optional</em>. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify <code>"chat_member"</code> in the list of <em>allowed_updates</em> to receive these updates.
     * @param ChatJoinRequest|null $chat_join_request <em>Optional</em>. A request to join the chat has been sent. The bot must have the <em>can_invite_users</em> administrator right in the chat to receive these updates.
     * @param ChatBoostUpdated|null $chat_boost <em>Optional</em>. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
     * @param ChatBoostRemoved|null $removed_chat_boost <em>Optional</em>. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
     * @param ManagedBotUpdated|null $managed_bot <em>Optional</em>. A new bot was created to be managed by the bot, or token or owner of a managed bot was changed
     * @param BotSubscriptionUpdated|null $subscription <em>Optional</em>. User payment subscription has changed
     * @param MessageGenerationStopped|null $stopped_message_generation <em>Optional</em>. A user asked the bot to stop the generation of a message
     */
    public static function make(int $update_id = null, Message $message = null, Message $edited_message = null, Message $channel_post = null, Message $edited_channel_post = null, BusinessConnection $business_connection = null, Message $business_message = null, Message $edited_business_message = null, BusinessMessagesDeleted $deleted_business_messages = null, Message $guest_message = null, MessageReactionUpdated $message_reaction = null, MessageReactionCountUpdated $message_reaction_count = null, InlineQuery $inline_query = null, ChosenInlineResult $chosen_inline_result = null, CallbackQuery $callback_query = null, WidgetQuery $widget_query = null, SharedWidgetQuery $shared_widget_query = null, ShippingQuery $shipping_query = null, PreCheckoutQuery $pre_checkout_query = null, PaidMediaPurchased $purchased_paid_media = null, Poll $poll = null, PollAnswer $poll_answer = null, ChatMemberUpdated $my_chat_member = null, ChatMemberUpdated $chat_member = null, ChatJoinRequest $chat_join_request = null, ChatBoostUpdated $chat_boost = null, ChatBoostRemoved $removed_chat_boost = null, ManagedBotUpdated $managed_bot = null, BotSubscriptionUpdated $subscription = null, MessageGenerationStopped $stopped_message_generation = null): self
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