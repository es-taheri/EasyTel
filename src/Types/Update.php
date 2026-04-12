<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This <a href="https://core.telegram.org/bots/api#available-types">object</a> represents an incoming update.<br>At most <strong>one</strong> of the optional parameters can be present in any given update.
 * @method self update_id(int $value) The update&#39;s unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you&#39;re using <a href="https://core.telegram.org/bots/api#setwebhook">webhooks</a>, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
 * @method self message(Message $value) <em>Optional</em>. New incoming message of any kind - text, photo, sticker, etc.
 * @method self edited_message(Message $value) <em>Optional</em>. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method self channel_post(Message $value) <em>Optional</em>. New incoming channel post of any kind - text, photo, sticker, etc.
 * @method self edited_channel_post(Message $value) <em>Optional</em>. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method self business_connection(BusinessConnection $value) <em>Optional</em>. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
 * @method self business_message(Message $value) <em>Optional</em>. New message from a connected business account
 * @method self edited_business_message(Message $value) <em>Optional</em>. New version of a message from a connected business account
 * @method self deleted_business_messages(BusinessMessagesDeleted $value) <em>Optional</em>. Messages were deleted from a connected business account
 * @method self message_reaction(MessageReactionUpdated $value) <em>Optional</em>. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify <code>&quot;message_reaction&quot;</code> in the list of <em>allowed_updates</em> to receive these updates. The update isn&#39;t received for reactions set by bots.
 * @method self message_reaction_count(MessageReactionCountUpdated $value) <em>Optional</em>. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify <code>&quot;message_reaction_count&quot;</code> in the list of <em>allowed_updates</em> to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
 * @method self inline_query(InlineQuery $value) <em>Optional</em>. New incoming <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query
 * @method self chosen_inline_result(ChosenInlineResult $value) <em>Optional</em>. The result of an <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query that was chosen by a user and sent to their chat partner. Please see our documentation on the <a href="/bots/inline#collecting-feedback">feedback collecting</a> for details on how to enable these updates for your bot.
 * @method self callback_query(CallbackQuery $value) <em>Optional</em>. New incoming callback query
 * @method self shipping_query(ShippingQuery $value) <em>Optional</em>. New incoming shipping query. Only for invoices with flexible price
 * @method self pre_checkout_query(PreCheckoutQuery $value) <em>Optional</em>. New incoming pre-checkout query. Contains full information about checkout
 * @method self purchased_paid_media(PaidMediaPurchased $value) <em>Optional</em>. A user purchased paid media with a non-empty payload sent by the bot in a non-channel chat
 * @method self poll(Poll $value) <em>Optional</em>. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot
 * @method self poll_answer(PollAnswer $value) <em>Optional</em>. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * @method self my_chat_member(ChatMemberUpdated $value) <em>Optional</em>. The bot&#39;s chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
 * @method self chat_member(ChatMemberUpdated $value) <em>Optional</em>. A chat member&#39;s status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify <code>&quot;chat_member&quot;</code> in the list of <em>allowed_updates</em> to receive these updates.
 * @method self chat_join_request(ChatJoinRequest $value) <em>Optional</em>. A request to join the chat has been sent. The bot must have the <em>can_invite_users</em> administrator right in the chat to receive these updates.
 * @method self chat_boost(ChatBoostUpdated $value) <em>Optional</em>. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
 * @method self removed_chat_boost(ChatBoostRemoved $value) <em>Optional</em>. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
 * @method self managed_bot(ManagedBotUpdated $value) <em>Optional</em>. A new bot was created to be managed by the bot, or token or owner of a managed bot was changed
 */
class Update
{
    public int $update_id;
    public Message $message;
    public Message $edited_message;
    public Message $channel_post;
    public Message $edited_channel_post;
    public BusinessConnection $business_connection;
    public Message $business_message;
    public Message $edited_business_message;
    public BusinessMessagesDeleted $deleted_business_messages;
    public MessageReactionUpdated $message_reaction;
    public MessageReactionCountUpdated $message_reaction_count;
    public InlineQuery $inline_query;
    public ChosenInlineResult $chosen_inline_result;
    public CallbackQuery $callback_query;
    public ShippingQuery $shipping_query;
    public PreCheckoutQuery $pre_checkout_query;
    public PaidMediaPurchased $purchased_paid_media;
    public Poll $poll;
    public PollAnswer $poll_answer;
    public ChatMemberUpdated $my_chat_member;
    public ChatMemberUpdated $chat_member;
    public ChatJoinRequest $chat_join_request;
    public ChatBoostUpdated $chat_boost;
    public ChatBoostRemoved $removed_chat_boost;
    public ManagedBotUpdated $managed_bot;

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
        if (isset($update['message'])) $this->message = new Message($update['message']);
        if (isset($update['edited_message'])) $this->edited_message = new Message($update['edited_message']);
        if (isset($update['channel_post'])) $this->channel_post = new Message($update['channel_post']);
        if (isset($update['edited_channel_post'])) $this->edited_channel_post = new Message($update['edited_channel_post']);
        if (isset($update['business_connection'])) $this->business_connection = new BusinessConnection($update['business_connection']);
        if (isset($update['business_message'])) $this->business_message = new Message($update['business_message']);
        if (isset($update['edited_business_message'])) $this->edited_business_message = new Message($update['edited_business_message']);
        if (isset($update['deleted_business_messages'])) $this->deleted_business_messages = new BusinessMessagesDeleted($update['deleted_business_messages']);
        if (isset($update['message_reaction'])) $this->message_reaction = new MessageReactionUpdated($update['message_reaction']);
        if (isset($update['message_reaction_count'])) $this->message_reaction_count = new MessageReactionCountUpdated($update['message_reaction_count']);
        if (isset($update['inline_query'])) $this->inline_query = new InlineQuery($update['inline_query']);
        if (isset($update['chosen_inline_result'])) $this->chosen_inline_result = new ChosenInlineResult($update['chosen_inline_result']);
        if (isset($update['callback_query'])) $this->callback_query = new CallbackQuery($update['callback_query']);
        if (isset($update['shipping_query'])) $this->shipping_query = new ShippingQuery($update['shipping_query']);
        if (isset($update['pre_checkout_query'])) $this->pre_checkout_query = new PreCheckoutQuery($update['pre_checkout_query']);
        if (isset($update['purchased_paid_media'])) $this->purchased_paid_media = new PaidMediaPurchased($update['purchased_paid_media']);
        if (isset($update['poll'])) $this->poll = new Poll($update['poll']);
        if (isset($update['poll_answer'])) $this->poll_answer = new PollAnswer($update['poll_answer']);
        if (isset($update['my_chat_member'])) $this->my_chat_member = new ChatMemberUpdated($update['my_chat_member']);
        if (isset($update['chat_member'])) $this->chat_member = new ChatMemberUpdated($update['chat_member']);
        if (isset($update['chat_join_request'])) $this->chat_join_request = new ChatJoinRequest($update['chat_join_request']);
        if (isset($update['chat_boost'])) $this->chat_boost = new ChatBoostUpdated($update['chat_boost']);
        if (isset($update['removed_chat_boost'])) $this->removed_chat_boost = new ChatBoostRemoved($update['removed_chat_boost']);
        if (isset($update['managed_bot'])) $this->managed_bot = new ManagedBotUpdated($update['managed_bot']);
    }

    /**
     * This <a href="https://core.telegram.org/bots/api#available-types">object</a> represents an incoming update.<br>At most <strong>one</strong> of the optional parameters can be present in any given update.
     * @param int|null $update_id The update&#39;s unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you&#39;re using <a href="https://core.telegram.org/bots/api#setwebhook">webhooks</a>, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
     * @param Message|null $message <em>Optional</em>. New incoming message of any kind - text, photo, sticker, etc.
     * @param Message|null $edited_message <em>Optional</em>. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
     * @param Message|null $channel_post <em>Optional</em>. New incoming channel post of any kind - text, photo, sticker, etc.
     * @param Message|null $edited_channel_post <em>Optional</em>. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
     * @param BusinessConnection|null $business_connection <em>Optional</em>. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
     * @param Message|null $business_message <em>Optional</em>. New message from a connected business account
     * @param Message|null $edited_business_message <em>Optional</em>. New version of a message from a connected business account
     * @param BusinessMessagesDeleted|null $deleted_business_messages <em>Optional</em>. Messages were deleted from a connected business account
     * @param MessageReactionUpdated|null $message_reaction <em>Optional</em>. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify <code>&quot;message_reaction&quot;</code> in the list of <em>allowed_updates</em> to receive these updates. The update isn&#39;t received for reactions set by bots.
     * @param MessageReactionCountUpdated|null $message_reaction_count <em>Optional</em>. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify <code>&quot;message_reaction_count&quot;</code> in the list of <em>allowed_updates</em> to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
     * @param InlineQuery|null $inline_query <em>Optional</em>. New incoming <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query
     * @param ChosenInlineResult|null $chosen_inline_result <em>Optional</em>. The result of an <a href="https://core.telegram.org/bots/api#inline-mode">inline</a> query that was chosen by a user and sent to their chat partner. Please see our documentation on the <a href="/bots/inline#collecting-feedback">feedback collecting</a> for details on how to enable these updates for your bot.
     * @param CallbackQuery|null $callback_query <em>Optional</em>. New incoming callback query
     * @param ShippingQuery|null $shipping_query <em>Optional</em>. New incoming shipping query. Only for invoices with flexible price
     * @param PreCheckoutQuery|null $pre_checkout_query <em>Optional</em>. New incoming pre-checkout query. Contains full information about checkout
     * @param PaidMediaPurchased|null $purchased_paid_media <em>Optional</em>. A user purchased paid media with a non-empty payload sent by the bot in a non-channel chat
     * @param Poll|null $poll <em>Optional</em>. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot
     * @param PollAnswer|null $poll_answer <em>Optional</em>. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
     * @param ChatMemberUpdated|null $my_chat_member <em>Optional</em>. The bot&#39;s chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
     * @param ChatMemberUpdated|null $chat_member <em>Optional</em>. A chat member&#39;s status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify <code>&quot;chat_member&quot;</code> in the list of <em>allowed_updates</em> to receive these updates.
     * @param ChatJoinRequest|null $chat_join_request <em>Optional</em>. A request to join the chat has been sent. The bot must have the <em>can_invite_users</em> administrator right in the chat to receive these updates.
     * @param ChatBoostUpdated|null $chat_boost <em>Optional</em>. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
     * @param ChatBoostRemoved|null $removed_chat_boost <em>Optional</em>. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
     * @param ManagedBotUpdated|null $managed_bot <em>Optional</em>. A new bot was created to be managed by the bot, or token or owner of a managed bot was changed
     */
    public static function make(int $update_id = null, Message $message = null, Message $edited_message = null, Message $channel_post = null, Message $edited_channel_post = null, BusinessConnection $business_connection = null, Message $business_message = null, Message $edited_business_message = null, BusinessMessagesDeleted $deleted_business_messages = null, MessageReactionUpdated $message_reaction = null, MessageReactionCountUpdated $message_reaction_count = null, InlineQuery $inline_query = null, ChosenInlineResult $chosen_inline_result = null, CallbackQuery $callback_query = null, ShippingQuery $shipping_query = null, PreCheckoutQuery $pre_checkout_query = null, PaidMediaPurchased $purchased_paid_media = null, Poll $poll = null, PollAnswer $poll_answer = null, ChatMemberUpdated $my_chat_member = null, ChatMemberUpdated $chat_member = null, ChatJoinRequest $chat_join_request = null, ChatBoostUpdated $chat_boost = null, ChatBoostRemoved $removed_chat_boost = null, ManagedBotUpdated $managed_bot = null): self
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