<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a message.
 * @method self message_id(int $value) Unique message identifier inside this chat. In specific instances (e.g., message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent
 * @method self message_thread_id(int $value) <em>Optional</em>. Unique identifier of a message thread or forum topic to which the message belongs; for supergroups and private chats only
 * @method self direct_messages_topic(DirectMessagesTopic $value) <em>Optional</em>. Information about the direct messages chat topic that contains the message
 * @method self from(User $value) <em>Optional</em>. Sender of the message; may be empty for messages sent to channels. For backward compatibility, if the message was sent on behalf of a chat, the field contains a fake sender user in non-channel chats
 * @method self sender_chat(Chat $value) <em>Optional</em>. Sender of the message when sent on behalf of a chat. For example, the supergroup itself for messages sent by its anonymous administrators or a linked channel for messages automatically forwarded to the channel&#39;s discussion group. For backward compatibility, if the message was sent on behalf of a chat, the field <em>from</em> contains a fake sender user in non-channel chats.
 * @method self sender_boost_count(int $value) <em>Optional</em>. If the sender of the message boosted the chat, the number of boosts added by the user
 * @method self sender_business_bot(User $value) <em>Optional</em>. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
 * @method self sender_tag(string $value) <em>Optional</em>. Tag or custom title of the sender of the message; for supergroups only
 * @method self date(int $value) Date the message was sent in Unix time. It is always a positive number, representing a valid date.
 * @method self business_connection_id(string $value) <em>Optional</em>. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
 * @method self chat(Chat $value) Chat the message belongs to
 * @method self forward_origin(MessageOrigin $value) <em>Optional</em>. Information about the original message for forwarded messages
 * @method self is_topic_message(True $value) <em>Optional</em>. <em>True</em>, if the message is sent to a topic in a forum supergroup or a private chat with the bot
 * @method self is_automatic_forward(True $value) <em>Optional</em>. <em>True</em>, if the message is a channel post that was automatically forwarded to the connected discussion group
 * @method self reply_to_message(Message $value) <em>Optional</em>. For replies in the same chat and message thread, the original message. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain further <em>reply_to_message</em> fields even if it itself is a reply.
 * @method self external_reply(ExternalReplyInfo $value) <em>Optional</em>. Information about the message that is being replied to, which may come from another chat or forum topic
 * @method self quote(TextQuote $value) <em>Optional</em>. For replies that quote part of the original message, the quoted part of the message
 * @method self reply_to_story(Story $value) <em>Optional</em>. For replies to a story, the original story
 * @method self reply_to_checklist_task_id(int $value) <em>Optional</em>. Identifier of the specific checklist task that is being replied to
 * @method self reply_to_poll_option_id(string $value) <em>Optional</em>. Persistent identifier of the specific poll option that is being replied to
 * @method self via_bot(User $value) <em>Optional</em>. Bot through which the message was sent
 * @method self edit_date(int $value) <em>Optional</em>. Date the message was last edited in Unix time
 * @method self has_protected_content(True $value) <em>Optional</em>. <em>True</em>, if the message can&#39;t be forwarded
 * @method self is_from_offline(True $value) <em>Optional</em>. <em>True</em>, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
 * @method self is_paid_post(True $value) <em>Optional</em>. <em>True</em>, if the message is a paid post. Note that such posts must not be deleted for 24 hours to receive the payment and can&#39;t be edited.
 * @method self media_group_id(string $value) <em>Optional</em>. The unique identifier inside this chat of a media message group this message belongs to
 * @method self author_signature(string $value) <em>Optional</em>. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
 * @method self paid_star_count(int $value) <em>Optional</em>. The number of Telegram Stars that were paid by the sender of the message to send it
 * @method self text(string $value) <em>Optional</em>. For text messages, the actual UTF-8 text of the message
 * @method self entities(array $value) <em>Optional</em>. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method self link_preview_options(LinkPreviewOptions $value) <em>Optional</em>. Options used for link preview generation for the message, if it is a text message and link preview options were changed
 * @method self suggested_post_info(SuggestedPostInfo $value) <em>Optional</em>. Information about suggested post parameters if the message is a suggested post in a channel direct messages chat. If the message is an approved or declined suggested post, then it can&#39;t be edited.
 * @method self effect_id(string $value) <em>Optional</em>. Unique identifier of the message effect added to the message
 * @method self animation(Animation $value) <em>Optional</em>. Message is an animation, information about the animation. For backward compatibility, when this field is set, the <em>document</em> field will also be set
 * @method self audio(Audio $value) <em>Optional</em>. Message is an audio file, information about the file
 * @method self document(Document $value) <em>Optional</em>. Message is a general file, information about the file
 * @method self paid_media(PaidMediaInfo $value) <em>Optional</em>. Message contains paid media; information about the paid media
 * @method self photo(array $value) <em>Optional</em>. Message is a photo, available sizes of the photo
 * @method self sticker(Sticker $value) <em>Optional</em>. Message is a sticker, information about the sticker
 * @method self story(Story $value) <em>Optional</em>. Message is a forwarded story
 * @method self video(Video $value) <em>Optional</em>. Message is a video, information about the video
 * @method self video_note(VideoNote $value) <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
 * @method self voice(Voice $value) <em>Optional</em>. Message is a voice message, information about the file
 * @method self caption(string $value) <em>Optional</em>. Caption for the animation, audio, document, paid media, photo, video or voice
 * @method self caption_entities(array $value) <em>Optional</em>. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @method self show_caption_above_media(True $value) <em>Optional</em>. <em>True</em>, if the caption must be shown above the message media
 * @method self has_media_spoiler(True $value) <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
 * @method self checklist(Checklist $value) <em>Optional</em>. Message is a checklist
 * @method self contact(Contact $value) <em>Optional</em>. Message is a shared contact, information about the contact
 * @method self dice(Dice $value) <em>Optional</em>. Message is a dice with random value
 * @method self game(Game $value) <em>Optional</em>. Message is a game, information about the game. <a href="https://core.telegram.org/bots/api#games">More about games »</a>
 * @method self poll(Poll $value) <em>Optional</em>. Message is a native poll, information about the poll
 * @method self venue(Venue $value) <em>Optional</em>. Message is a venue, information about the venue. For backward compatibility, when this field is set, the <em>location</em> field will also be set
 * @method self location(Location $value) <em>Optional</em>. Message is a shared location, information about the location
 * @method self new_chat_members(array $value) <em>Optional</em>. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
 * @method self left_chat_member(User $value) <em>Optional</em>. A member was removed from the group, information about them (this member may be the bot itself)
 * @method self chat_owner_left(ChatOwnerLeft $value) <em>Optional</em>. Service message: chat owner has left
 * @method self chat_owner_changed(ChatOwnerChanged $value) <em>Optional</em>. Service message: chat owner has changed
 * @method self new_chat_title(string $value) <em>Optional</em>. A chat title was changed to this value
 * @method self new_chat_photo(array $value) <em>Optional</em>. A chat photo was change to this value
 * @method self delete_chat_photo(True $value) <em>Optional</em>. Service message: the chat photo was deleted
 * @method self group_chat_created(True $value) <em>Optional</em>. Service message: the group has been created
 * @method self supergroup_chat_created(True $value) <em>Optional</em>. Service message: the supergroup has been created. This field can&#39;t be received in a message coming through updates, because bot can&#39;t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method self channel_chat_created(True $value) <em>Optional</em>. Service message: the channel has been created. This field can&#39;t be received in a message coming through updates, because bot can&#39;t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @method self message_auto_delete_timer_changed(MessageAutoDeleteTimerChanged $value) <em>Optional</em>. Service message: auto-delete timer settings changed in the chat
 * @method self migrate_to_chat_id(int $value) <em>Optional</em>. The group has been migrated to a supergroup with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self migrate_from_chat_id(int $value) <em>Optional</em>. The supergroup has been migrated from a group with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self pinned_message(MaybeInaccessibleMessage $value) <em>Optional</em>. Specified message was pinned. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain further <em>reply_to_message</em> fields even if it itself is a reply.
 * @method self invoice(Invoice $value) <em>Optional</em>. Message is an invoice for a <a href="https://core.telegram.org/bots/api#payments">payment</a>, information about the invoice. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
 * @method self successful_payment(SuccessfulPayment $value) <em>Optional</em>. Message is a service message about a successful payment, information about the payment. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
 * @method self refunded_payment(RefundedPayment $value) <em>Optional</em>. Message is a service message about a refunded payment, information about the payment. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
 * @method self users_shared(UsersShared $value) <em>Optional</em>. Service message: users were shared with the bot
 * @method self chat_shared(ChatShared $value) <em>Optional</em>. Service message: a chat was shared with the bot
 * @method self gift(GiftInfo $value) <em>Optional</em>. Service message: a regular gift was sent or received
 * @method self unique_gift(UniqueGiftInfo $value) <em>Optional</em>. Service message: a unique gift was sent or received
 * @method self gift_upgrade_sent(GiftInfo $value) <em>Optional</em>. Service message: upgrade of a gift was purchased after the gift was sent
 * @method self connected_website(string $value) <em>Optional</em>. The domain name of the website on which the user has logged in. <a href="/widgets/login">More about Telegram Login »</a>
 * @method self write_access_allowed(WriteAccessAllowed $value) <em>Optional</em>. Service message: the user allowed the bot to write messages after adding it to the attachment or side menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method <a href="/bots/webapps#initializing-mini-apps">requestWriteAccess</a>
 * @method self passport_data(PassportData $value) <em>Optional</em>. Telegram Passport data
 * @method self proximity_alert_triggered(ProximityAlertTriggered $value) <em>Optional</em>. Service message. A user in the chat triggered another user&#39;s proximity alert while sharing Live Location.
 * @method self boost_added(ChatBoostAdded $value) <em>Optional</em>. Service message: user boosted the chat
 * @method self chat_background_set(ChatBackground $value) <em>Optional</em>. Service message: chat background set
 * @method self checklist_tasks_done(ChecklistTasksDone $value) <em>Optional</em>. Service message: some tasks in a checklist were marked as done or not done
 * @method self checklist_tasks_added(ChecklistTasksAdded $value) <em>Optional</em>. Service message: tasks were added to a checklist
 * @method self direct_message_price_changed(DirectMessagePriceChanged $value) <em>Optional</em>. Service message: the price for paid messages in the corresponding direct messages chat of a channel has changed
 * @method self forum_topic_created(ForumTopicCreated $value) <em>Optional</em>. Service message: forum topic created
 * @method self forum_topic_edited(ForumTopicEdited $value) <em>Optional</em>. Service message: forum topic edited
 * @method self forum_topic_closed(ForumTopicClosed $value) <em>Optional</em>. Service message: forum topic closed
 * @method self forum_topic_reopened(ForumTopicReopened $value) <em>Optional</em>. Service message: forum topic reopened
 * @method self general_forum_topic_hidden(GeneralForumTopicHidden $value) <em>Optional</em>. Service message: the &#39;General&#39; forum topic hidden
 * @method self general_forum_topic_unhidden(GeneralForumTopicUnhidden $value) <em>Optional</em>. Service message: the &#39;General&#39; forum topic unhidden
 * @method self giveaway_created(GiveawayCreated $value) <em>Optional</em>. Service message: a scheduled giveaway was created
 * @method self giveaway(Giveaway $value) <em>Optional</em>. The message is a scheduled giveaway message
 * @method self giveaway_winners(GiveawayWinners $value) <em>Optional</em>. A giveaway with public winners was completed
 * @method self giveaway_completed(GiveawayCompleted $value) <em>Optional</em>. Service message: a giveaway without public winners was completed
 * @method self managed_bot_created(ManagedBotCreated $value) <em>Optional</em>. Service message: user created a bot that will be managed by the current bot
 * @method self paid_message_price_changed(PaidMessagePriceChanged $value) <em>Optional</em>. Service message: the price for paid messages has changed in the chat
 * @method self poll_option_added(PollOptionAdded $value) <em>Optional</em>. Service message: answer option was added to a poll
 * @method self poll_option_deleted(PollOptionDeleted $value) <em>Optional</em>. Service message: answer option was deleted from a poll
 * @method self suggested_post_approved(SuggestedPostApproved $value) <em>Optional</em>. Service message: a suggested post was approved
 * @method self suggested_post_approval_failed(SuggestedPostApprovalFailed $value) <em>Optional</em>. Service message: approval of a suggested post has failed
 * @method self suggested_post_declined(SuggestedPostDeclined $value) <em>Optional</em>. Service message: a suggested post was declined
 * @method self suggested_post_paid(SuggestedPostPaid $value) <em>Optional</em>. Service message: payment for a suggested post was received
 * @method self suggested_post_refunded(SuggestedPostRefunded $value) <em>Optional</em>. Service message: payment for a suggested post was refunded
 * @method self video_chat_scheduled(VideoChatScheduled $value) <em>Optional</em>. Service message: video chat scheduled
 * @method self video_chat_started(VideoChatStarted $value) <em>Optional</em>. Service message: video chat started
 * @method self video_chat_ended(VideoChatEnded $value) <em>Optional</em>. Service message: video chat ended
 * @method self video_chat_participants_invited(VideoChatParticipantsInvited $value) <em>Optional</em>. Service message: new participants invited to a video chat
 * @method self web_app_data(WebAppData $value) <em>Optional</em>. Service message: data sent by a Web App
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message. <code>login_url</code> buttons are represented as ordinary <code>url</code> buttons.
 */
class Message
{
    public int $message_id;
    public int $message_thread_id;
    public DirectMessagesTopic $direct_messages_topic;
    public User $from;
    public Chat $sender_chat;
    public int $sender_boost_count;
    public User $sender_business_bot;
    public string $sender_tag;
    public int $date;
    public string $business_connection_id;
    public Chat $chat;
    public MessageOrigin $forward_origin;
    public True $is_topic_message;
    public True $is_automatic_forward;
    public Message $reply_to_message;
    public ExternalReplyInfo $external_reply;
    public TextQuote $quote;
    public Story $reply_to_story;
    public int $reply_to_checklist_task_id;
    public string $reply_to_poll_option_id;
    public User $via_bot;
    public int $edit_date;
    public True $has_protected_content;
    public True $is_from_offline;
    public True $is_paid_post;
    public string $media_group_id;
    public string $author_signature;
    public int $paid_star_count;
    public string $text;
    public array $entities;
    public LinkPreviewOptions $link_preview_options;
    public SuggestedPostInfo $suggested_post_info;
    public string $effect_id;
    public Animation $animation;
    public Audio $audio;
    public Document $document;
    public PaidMediaInfo $paid_media;
    public array $photo;
    public Sticker $sticker;
    public Story $story;
    public Video $video;
    public VideoNote $video_note;
    public Voice $voice;
    public string $caption;
    public array $caption_entities;
    public True $show_caption_above_media;
    public True $has_media_spoiler;
    public Checklist $checklist;
    public Contact $contact;
    public Dice $dice;
    public Game $game;
    public Poll $poll;
    public Venue $venue;
    public Location $location;
    public array $new_chat_members;
    public User $left_chat_member;
    public ChatOwnerLeft $chat_owner_left;
    public ChatOwnerChanged $chat_owner_changed;
    public string $new_chat_title;
    public array $new_chat_photo;
    public True $delete_chat_photo;
    public True $group_chat_created;
    public True $supergroup_chat_created;
    public True $channel_chat_created;
    public MessageAutoDeleteTimerChanged $message_auto_delete_timer_changed;
    public int $migrate_to_chat_id;
    public int $migrate_from_chat_id;
    public MaybeInaccessibleMessage $pinned_message;
    public Invoice $invoice;
    public SuccessfulPayment $successful_payment;
    public RefundedPayment $refunded_payment;
    public UsersShared $users_shared;
    public ChatShared $chat_shared;
    public GiftInfo $gift;
    public UniqueGiftInfo $unique_gift;
    public GiftInfo $gift_upgrade_sent;
    public string $connected_website;
    public WriteAccessAllowed $write_access_allowed;
    public PassportData $passport_data;
    public ProximityAlertTriggered $proximity_alert_triggered;
    public ChatBoostAdded $boost_added;
    public ChatBackground $chat_background_set;
    public ChecklistTasksDone $checklist_tasks_done;
    public ChecklistTasksAdded $checklist_tasks_added;
    public DirectMessagePriceChanged $direct_message_price_changed;
    public ForumTopicCreated $forum_topic_created;
    public ForumTopicEdited $forum_topic_edited;
    public ForumTopicClosed $forum_topic_closed;
    public ForumTopicReopened $forum_topic_reopened;
    public GeneralForumTopicHidden $general_forum_topic_hidden;
    public GeneralForumTopicUnhidden $general_forum_topic_unhidden;
    public GiveawayCreated $giveaway_created;
    public Giveaway $giveaway;
    public GiveawayWinners $giveaway_winners;
    public GiveawayCompleted $giveaway_completed;
    public ManagedBotCreated $managed_bot_created;
    public PaidMessagePriceChanged $paid_message_price_changed;
    public PollOptionAdded $poll_option_added;
    public PollOptionDeleted $poll_option_deleted;
    public SuggestedPostApproved $suggested_post_approved;
    public SuggestedPostApprovalFailed $suggested_post_approval_failed;
    public SuggestedPostDeclined $suggested_post_declined;
    public SuggestedPostPaid $suggested_post_paid;
    public SuggestedPostRefunded $suggested_post_refunded;
    public VideoChatScheduled $video_chat_scheduled;
    public VideoChatStarted $video_chat_started;
    public VideoChatEnded $video_chat_ended;
    public VideoChatParticipantsInvited $video_chat_participants_invited;
    public WebAppData $web_app_data;
    public InlineKeyboardMarkup $reply_markup;

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
        if (isset($update['direct_messages_topic'])) $this->direct_messages_topic = new DirectMessagesTopic($update['direct_messages_topic']);
        if (isset($update['from'])) $this->from = new User($update['from']);
        if (isset($update['sender_chat'])) $this->sender_chat = new Chat($update['sender_chat']);
        if (isset($update['sender_business_bot'])) $this->sender_business_bot = new User($update['sender_business_bot']);
        if (isset($update['chat'])) $this->chat = new Chat($update['chat']);
        if (isset($update['forward_origin'])) $this->forward_origin = new MessageOrigin($update['forward_origin']);
        if (isset($update['reply_to_message'])) $this->reply_to_message = new Message($update['reply_to_message']);
        if (isset($update['external_reply'])) $this->external_reply = new ExternalReplyInfo($update['external_reply']);
        if (isset($update['quote'])) $this->quote = new TextQuote($update['quote']);
        if (isset($update['reply_to_story'])) $this->reply_to_story = new Story($update['reply_to_story']);
        if (isset($update['via_bot'])) $this->via_bot = new User($update['via_bot']);
        if (isset($update['link_preview_options'])) $this->link_preview_options = new LinkPreviewOptions($update['link_preview_options']);
        if (isset($update['suggested_post_info'])) $this->suggested_post_info = new SuggestedPostInfo($update['suggested_post_info']);
        if (isset($update['animation'])) $this->animation = new Animation($update['animation']);
        if (isset($update['audio'])) $this->audio = new Audio($update['audio']);
        if (isset($update['document'])) $this->document = new Document($update['document']);
        if (isset($update['paid_media'])) $this->paid_media = new PaidMediaInfo($update['paid_media']);
        if (isset($update['sticker'])) $this->sticker = new Sticker($update['sticker']);
        if (isset($update['story'])) $this->story = new Story($update['story']);
        if (isset($update['video'])) $this->video = new Video($update['video']);
        if (isset($update['video_note'])) $this->video_note = new VideoNote($update['video_note']);
        if (isset($update['voice'])) $this->voice = new Voice($update['voice']);
        if (isset($update['checklist'])) $this->checklist = new Checklist($update['checklist']);
        if (isset($update['contact'])) $this->contact = new Contact($update['contact']);
        if (isset($update['dice'])) $this->dice = new Dice($update['dice']);
        if (isset($update['game'])) $this->game = new Game($update['game']);
        if (isset($update['poll'])) $this->poll = new Poll($update['poll']);
        if (isset($update['venue'])) $this->venue = new Venue($update['venue']);
        if (isset($update['location'])) $this->location = new Location($update['location']);
        if (isset($update['left_chat_member'])) $this->left_chat_member = new User($update['left_chat_member']);
        if (isset($update['chat_owner_left'])) $this->chat_owner_left = new ChatOwnerLeft($update['chat_owner_left']);
        if (isset($update['chat_owner_changed'])) $this->chat_owner_changed = new ChatOwnerChanged($update['chat_owner_changed']);
        if (isset($update['message_auto_delete_timer_changed'])) $this->message_auto_delete_timer_changed = new MessageAutoDeleteTimerChanged($update['message_auto_delete_timer_changed']);
        if (isset($update['pinned_message'])) $this->pinned_message = new MaybeInaccessibleMessage($update['pinned_message']);
        if (isset($update['invoice'])) $this->invoice = new Invoice($update['invoice']);
        if (isset($update['successful_payment'])) $this->successful_payment = new SuccessfulPayment($update['successful_payment']);
        if (isset($update['refunded_payment'])) $this->refunded_payment = new RefundedPayment($update['refunded_payment']);
        if (isset($update['users_shared'])) $this->users_shared = new UsersShared($update['users_shared']);
        if (isset($update['chat_shared'])) $this->chat_shared = new ChatShared($update['chat_shared']);
        if (isset($update['gift'])) $this->gift = new GiftInfo($update['gift']);
        if (isset($update['unique_gift'])) $this->unique_gift = new UniqueGiftInfo($update['unique_gift']);
        if (isset($update['gift_upgrade_sent'])) $this->gift_upgrade_sent = new GiftInfo($update['gift_upgrade_sent']);
        if (isset($update['write_access_allowed'])) $this->write_access_allowed = new WriteAccessAllowed($update['write_access_allowed']);
        if (isset($update['passport_data'])) $this->passport_data = new PassportData($update['passport_data']);
        if (isset($update['proximity_alert_triggered'])) $this->proximity_alert_triggered = new ProximityAlertTriggered($update['proximity_alert_triggered']);
        if (isset($update['boost_added'])) $this->boost_added = new ChatBoostAdded($update['boost_added']);
        if (isset($update['chat_background_set'])) $this->chat_background_set = new ChatBackground($update['chat_background_set']);
        if (isset($update['checklist_tasks_done'])) $this->checklist_tasks_done = new ChecklistTasksDone($update['checklist_tasks_done']);
        if (isset($update['checklist_tasks_added'])) $this->checklist_tasks_added = new ChecklistTasksAdded($update['checklist_tasks_added']);
        if (isset($update['direct_message_price_changed'])) $this->direct_message_price_changed = new DirectMessagePriceChanged($update['direct_message_price_changed']);
        if (isset($update['forum_topic_created'])) $this->forum_topic_created = new ForumTopicCreated($update['forum_topic_created']);
        if (isset($update['forum_topic_edited'])) $this->forum_topic_edited = new ForumTopicEdited($update['forum_topic_edited']);
        if (isset($update['forum_topic_closed'])) $this->forum_topic_closed = new ForumTopicClosed($update['forum_topic_closed']);
        if (isset($update['forum_topic_reopened'])) $this->forum_topic_reopened = new ForumTopicReopened($update['forum_topic_reopened']);
        if (isset($update['general_forum_topic_hidden'])) $this->general_forum_topic_hidden = new GeneralForumTopicHidden($update['general_forum_topic_hidden']);
        if (isset($update['general_forum_topic_unhidden'])) $this->general_forum_topic_unhidden = new GeneralForumTopicUnhidden($update['general_forum_topic_unhidden']);
        if (isset($update['giveaway_created'])) $this->giveaway_created = new GiveawayCreated($update['giveaway_created']);
        if (isset($update['giveaway'])) $this->giveaway = new Giveaway($update['giveaway']);
        if (isset($update['giveaway_winners'])) $this->giveaway_winners = new GiveawayWinners($update['giveaway_winners']);
        if (isset($update['giveaway_completed'])) $this->giveaway_completed = new GiveawayCompleted($update['giveaway_completed']);
        if (isset($update['managed_bot_created'])) $this->managed_bot_created = new ManagedBotCreated($update['managed_bot_created']);
        if (isset($update['paid_message_price_changed'])) $this->paid_message_price_changed = new PaidMessagePriceChanged($update['paid_message_price_changed']);
        if (isset($update['poll_option_added'])) $this->poll_option_added = new PollOptionAdded($update['poll_option_added']);
        if (isset($update['poll_option_deleted'])) $this->poll_option_deleted = new PollOptionDeleted($update['poll_option_deleted']);
        if (isset($update['suggested_post_approved'])) $this->suggested_post_approved = new SuggestedPostApproved($update['suggested_post_approved']);
        if (isset($update['suggested_post_approval_failed'])) $this->suggested_post_approval_failed = new SuggestedPostApprovalFailed($update['suggested_post_approval_failed']);
        if (isset($update['suggested_post_declined'])) $this->suggested_post_declined = new SuggestedPostDeclined($update['suggested_post_declined']);
        if (isset($update['suggested_post_paid'])) $this->suggested_post_paid = new SuggestedPostPaid($update['suggested_post_paid']);
        if (isset($update['suggested_post_refunded'])) $this->suggested_post_refunded = new SuggestedPostRefunded($update['suggested_post_refunded']);
        if (isset($update['video_chat_scheduled'])) $this->video_chat_scheduled = new VideoChatScheduled($update['video_chat_scheduled']);
        if (isset($update['video_chat_started'])) $this->video_chat_started = new VideoChatStarted($update['video_chat_started']);
        if (isset($update['video_chat_ended'])) $this->video_chat_ended = new VideoChatEnded($update['video_chat_ended']);
        if (isset($update['video_chat_participants_invited'])) $this->video_chat_participants_invited = new VideoChatParticipantsInvited($update['video_chat_participants_invited']);
        if (isset($update['web_app_data'])) $this->web_app_data = new WebAppData($update['web_app_data']);
        if (isset($update['reply_markup'])) $this->reply_markup = new InlineKeyboardMarkup($update['reply_markup']);
    }

    /**
     * This object represents a message.
     * @param int|null $message_id Unique message identifier inside this chat. In specific instances (e.g., message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent
     * @param int|null $message_thread_id <em>Optional</em>. Unique identifier of a message thread or forum topic to which the message belongs; for supergroups and private chats only
     * @param DirectMessagesTopic|null $direct_messages_topic <em>Optional</em>. Information about the direct messages chat topic that contains the message
     * @param User|null $from <em>Optional</em>. Sender of the message; may be empty for messages sent to channels. For backward compatibility, if the message was sent on behalf of a chat, the field contains a fake sender user in non-channel chats
     * @param Chat|null $sender_chat <em>Optional</em>. Sender of the message when sent on behalf of a chat. For example, the supergroup itself for messages sent by its anonymous administrators or a linked channel for messages automatically forwarded to the channel&#39;s discussion group. For backward compatibility, if the message was sent on behalf of a chat, the field <em>from</em> contains a fake sender user in non-channel chats.
     * @param int|null $sender_boost_count <em>Optional</em>. If the sender of the message boosted the chat, the number of boosts added by the user
     * @param User|null $sender_business_bot <em>Optional</em>. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
     * @param string|null $sender_tag <em>Optional</em>. Tag or custom title of the sender of the message; for supergroups only
     * @param int|null $date Date the message was sent in Unix time. It is always a positive number, representing a valid date.
     * @param string|null $business_connection_id <em>Optional</em>. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
     * @param Chat|null $chat Chat the message belongs to
     * @param MessageOrigin|null $forward_origin <em>Optional</em>. Information about the original message for forwarded messages
     * @param True|null $is_topic_message <em>Optional</em>. <em>True</em>, if the message is sent to a topic in a forum supergroup or a private chat with the bot
     * @param True|null $is_automatic_forward <em>Optional</em>. <em>True</em>, if the message is a channel post that was automatically forwarded to the connected discussion group
     * @param Message|null $reply_to_message <em>Optional</em>. For replies in the same chat and message thread, the original message. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain further <em>reply_to_message</em> fields even if it itself is a reply.
     * @param ExternalReplyInfo|null $external_reply <em>Optional</em>. Information about the message that is being replied to, which may come from another chat or forum topic
     * @param TextQuote|null $quote <em>Optional</em>. For replies that quote part of the original message, the quoted part of the message
     * @param Story|null $reply_to_story <em>Optional</em>. For replies to a story, the original story
     * @param int|null $reply_to_checklist_task_id <em>Optional</em>. Identifier of the specific checklist task that is being replied to
     * @param string|null $reply_to_poll_option_id <em>Optional</em>. Persistent identifier of the specific poll option that is being replied to
     * @param User|null $via_bot <em>Optional</em>. Bot through which the message was sent
     * @param int|null $edit_date <em>Optional</em>. Date the message was last edited in Unix time
     * @param True|null $has_protected_content <em>Optional</em>. <em>True</em>, if the message can&#39;t be forwarded
     * @param True|null $is_from_offline <em>Optional</em>. <em>True</em>, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
     * @param True|null $is_paid_post <em>Optional</em>. <em>True</em>, if the message is a paid post. Note that such posts must not be deleted for 24 hours to receive the payment and can&#39;t be edited.
     * @param string|null $media_group_id <em>Optional</em>. The unique identifier inside this chat of a media message group this message belongs to
     * @param string|null $author_signature <em>Optional</em>. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
     * @param int|null $paid_star_count <em>Optional</em>. The number of Telegram Stars that were paid by the sender of the message to send it
     * @param string|null $text <em>Optional</em>. For text messages, the actual UTF-8 text of the message
     * @param array|null $entities <em>Optional</em>. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     * @param LinkPreviewOptions|null $link_preview_options <em>Optional</em>. Options used for link preview generation for the message, if it is a text message and link preview options were changed
     * @param SuggestedPostInfo|null $suggested_post_info <em>Optional</em>. Information about suggested post parameters if the message is a suggested post in a channel direct messages chat. If the message is an approved or declined suggested post, then it can&#39;t be edited.
     * @param string|null $effect_id <em>Optional</em>. Unique identifier of the message effect added to the message
     * @param Animation|null $animation <em>Optional</em>. Message is an animation, information about the animation. For backward compatibility, when this field is set, the <em>document</em> field will also be set
     * @param Audio|null $audio <em>Optional</em>. Message is an audio file, information about the file
     * @param Document|null $document <em>Optional</em>. Message is a general file, information about the file
     * @param PaidMediaInfo|null $paid_media <em>Optional</em>. Message contains paid media; information about the paid media
     * @param array|null $photo <em>Optional</em>. Message is a photo, available sizes of the photo
     * @param Sticker|null $sticker <em>Optional</em>. Message is a sticker, information about the sticker
     * @param Story|null $story <em>Optional</em>. Message is a forwarded story
     * @param Video|null $video <em>Optional</em>. Message is a video, information about the video
     * @param VideoNote|null $video_note <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
     * @param Voice|null $voice <em>Optional</em>. Message is a voice message, information about the file
     * @param string|null $caption <em>Optional</em>. Caption for the animation, audio, document, paid media, photo, video or voice
     * @param array|null $caption_entities <em>Optional</em>. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
     * @param True|null $show_caption_above_media <em>Optional</em>. <em>True</em>, if the caption must be shown above the message media
     * @param True|null $has_media_spoiler <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
     * @param Checklist|null $checklist <em>Optional</em>. Message is a checklist
     * @param Contact|null $contact <em>Optional</em>. Message is a shared contact, information about the contact
     * @param Dice|null $dice <em>Optional</em>. Message is a dice with random value
     * @param Game|null $game <em>Optional</em>. Message is a game, information about the game. <a href="https://core.telegram.org/bots/api#games">More about games »</a>
     * @param Poll|null $poll <em>Optional</em>. Message is a native poll, information about the poll
     * @param Venue|null $venue <em>Optional</em>. Message is a venue, information about the venue. For backward compatibility, when this field is set, the <em>location</em> field will also be set
     * @param Location|null $location <em>Optional</em>. Message is a shared location, information about the location
     * @param array|null $new_chat_members <em>Optional</em>. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
     * @param User|null $left_chat_member <em>Optional</em>. A member was removed from the group, information about them (this member may be the bot itself)
     * @param ChatOwnerLeft|null $chat_owner_left <em>Optional</em>. Service message: chat owner has left
     * @param ChatOwnerChanged|null $chat_owner_changed <em>Optional</em>. Service message: chat owner has changed
     * @param string|null $new_chat_title <em>Optional</em>. A chat title was changed to this value
     * @param array|null $new_chat_photo <em>Optional</em>. A chat photo was change to this value
     * @param True|null $delete_chat_photo <em>Optional</em>. Service message: the chat photo was deleted
     * @param True|null $group_chat_created <em>Optional</em>. Service message: the group has been created
     * @param True|null $supergroup_chat_created <em>Optional</em>. Service message: the supergroup has been created. This field can&#39;t be received in a message coming through updates, because bot can&#39;t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
     * @param True|null $channel_chat_created <em>Optional</em>. Service message: the channel has been created. This field can&#39;t be received in a message coming through updates, because bot can&#39;t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
     * @param MessageAutoDeleteTimerChanged|null $message_auto_delete_timer_changed <em>Optional</em>. Service message: auto-delete timer settings changed in the chat
     * @param int|null $migrate_to_chat_id <em>Optional</em>. The group has been migrated to a supergroup with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param int|null $migrate_from_chat_id <em>Optional</em>. The supergroup has been migrated from a group with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param MaybeInaccessibleMessage|null $pinned_message <em>Optional</em>. Specified message was pinned. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain further <em>reply_to_message</em> fields even if it itself is a reply.
     * @param Invoice|null $invoice <em>Optional</em>. Message is an invoice for a <a href="https://core.telegram.org/bots/api#payments">payment</a>, information about the invoice. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
     * @param SuccessfulPayment|null $successful_payment <em>Optional</em>. Message is a service message about a successful payment, information about the payment. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
     * @param RefundedPayment|null $refunded_payment <em>Optional</em>. Message is a service message about a refunded payment, information about the payment. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
     * @param UsersShared|null $users_shared <em>Optional</em>. Service message: users were shared with the bot
     * @param ChatShared|null $chat_shared <em>Optional</em>. Service message: a chat was shared with the bot
     * @param GiftInfo|null $gift <em>Optional</em>. Service message: a regular gift was sent or received
     * @param UniqueGiftInfo|null $unique_gift <em>Optional</em>. Service message: a unique gift was sent or received
     * @param GiftInfo|null $gift_upgrade_sent <em>Optional</em>. Service message: upgrade of a gift was purchased after the gift was sent
     * @param string|null $connected_website <em>Optional</em>. The domain name of the website on which the user has logged in. <a href="/widgets/login">More about Telegram Login »</a>
     * @param WriteAccessAllowed|null $write_access_allowed <em>Optional</em>. Service message: the user allowed the bot to write messages after adding it to the attachment or side menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method <a href="/bots/webapps#initializing-mini-apps">requestWriteAccess</a>
     * @param PassportData|null $passport_data <em>Optional</em>. Telegram Passport data
     * @param ProximityAlertTriggered|null $proximity_alert_triggered <em>Optional</em>. Service message. A user in the chat triggered another user&#39;s proximity alert while sharing Live Location.
     * @param ChatBoostAdded|null $boost_added <em>Optional</em>. Service message: user boosted the chat
     * @param ChatBackground|null $chat_background_set <em>Optional</em>. Service message: chat background set
     * @param ChecklistTasksDone|null $checklist_tasks_done <em>Optional</em>. Service message: some tasks in a checklist were marked as done or not done
     * @param ChecklistTasksAdded|null $checklist_tasks_added <em>Optional</em>. Service message: tasks were added to a checklist
     * @param DirectMessagePriceChanged|null $direct_message_price_changed <em>Optional</em>. Service message: the price for paid messages in the corresponding direct messages chat of a channel has changed
     * @param ForumTopicCreated|null $forum_topic_created <em>Optional</em>. Service message: forum topic created
     * @param ForumTopicEdited|null $forum_topic_edited <em>Optional</em>. Service message: forum topic edited
     * @param ForumTopicClosed|null $forum_topic_closed <em>Optional</em>. Service message: forum topic closed
     * @param ForumTopicReopened|null $forum_topic_reopened <em>Optional</em>. Service message: forum topic reopened
     * @param GeneralForumTopicHidden|null $general_forum_topic_hidden <em>Optional</em>. Service message: the &#39;General&#39; forum topic hidden
     * @param GeneralForumTopicUnhidden|null $general_forum_topic_unhidden <em>Optional</em>. Service message: the &#39;General&#39; forum topic unhidden
     * @param GiveawayCreated|null $giveaway_created <em>Optional</em>. Service message: a scheduled giveaway was created
     * @param Giveaway|null $giveaway <em>Optional</em>. The message is a scheduled giveaway message
     * @param GiveawayWinners|null $giveaway_winners <em>Optional</em>. A giveaway with public winners was completed
     * @param GiveawayCompleted|null $giveaway_completed <em>Optional</em>. Service message: a giveaway without public winners was completed
     * @param ManagedBotCreated|null $managed_bot_created <em>Optional</em>. Service message: user created a bot that will be managed by the current bot
     * @param PaidMessagePriceChanged|null $paid_message_price_changed <em>Optional</em>. Service message: the price for paid messages has changed in the chat
     * @param PollOptionAdded|null $poll_option_added <em>Optional</em>. Service message: answer option was added to a poll
     * @param PollOptionDeleted|null $poll_option_deleted <em>Optional</em>. Service message: answer option was deleted from a poll
     * @param SuggestedPostApproved|null $suggested_post_approved <em>Optional</em>. Service message: a suggested post was approved
     * @param SuggestedPostApprovalFailed|null $suggested_post_approval_failed <em>Optional</em>. Service message: approval of a suggested post has failed
     * @param SuggestedPostDeclined|null $suggested_post_declined <em>Optional</em>. Service message: a suggested post was declined
     * @param SuggestedPostPaid|null $suggested_post_paid <em>Optional</em>. Service message: payment for a suggested post was received
     * @param SuggestedPostRefunded|null $suggested_post_refunded <em>Optional</em>. Service message: payment for a suggested post was refunded
     * @param VideoChatScheduled|null $video_chat_scheduled <em>Optional</em>. Service message: video chat scheduled
     * @param VideoChatStarted|null $video_chat_started <em>Optional</em>. Service message: video chat started
     * @param VideoChatEnded|null $video_chat_ended <em>Optional</em>. Service message: video chat ended
     * @param VideoChatParticipantsInvited|null $video_chat_participants_invited <em>Optional</em>. Service message: new participants invited to a video chat
     * @param WebAppData|null $web_app_data <em>Optional</em>. Service message: data sent by a Web App
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message. <code>login_url</code> buttons are represented as ordinary <code>url</code> buttons.
     */
    public static function make(int $message_id = null, int $message_thread_id = null, DirectMessagesTopic $direct_messages_topic = null, User $from = null, Chat $sender_chat = null, int $sender_boost_count = null, User $sender_business_bot = null, string $sender_tag = null, int $date = null, string $business_connection_id = null, Chat $chat = null, MessageOrigin $forward_origin = null, True $is_topic_message = null, True $is_automatic_forward = null, Message $reply_to_message = null, ExternalReplyInfo $external_reply = null, TextQuote $quote = null, Story $reply_to_story = null, int $reply_to_checklist_task_id = null, string $reply_to_poll_option_id = null, User $via_bot = null, int $edit_date = null, True $has_protected_content = null, True $is_from_offline = null, True $is_paid_post = null, string $media_group_id = null, string $author_signature = null, int $paid_star_count = null, string $text = null, array $entities = null, LinkPreviewOptions $link_preview_options = null, SuggestedPostInfo $suggested_post_info = null, string $effect_id = null, Animation $animation = null, Audio $audio = null, Document $document = null, PaidMediaInfo $paid_media = null, array $photo = null, Sticker $sticker = null, Story $story = null, Video $video = null, VideoNote $video_note = null, Voice $voice = null, string $caption = null, array $caption_entities = null, True $show_caption_above_media = null, True $has_media_spoiler = null, Checklist $checklist = null, Contact $contact = null, Dice $dice = null, Game $game = null, Poll $poll = null, Venue $venue = null, Location $location = null, array $new_chat_members = null, User $left_chat_member = null, ChatOwnerLeft $chat_owner_left = null, ChatOwnerChanged $chat_owner_changed = null, string $new_chat_title = null, array $new_chat_photo = null, True $delete_chat_photo = null, True $group_chat_created = null, True $supergroup_chat_created = null, True $channel_chat_created = null, MessageAutoDeleteTimerChanged $message_auto_delete_timer_changed = null, int $migrate_to_chat_id = null, int $migrate_from_chat_id = null, MaybeInaccessibleMessage $pinned_message = null, Invoice $invoice = null, SuccessfulPayment $successful_payment = null, RefundedPayment $refunded_payment = null, UsersShared $users_shared = null, ChatShared $chat_shared = null, GiftInfo $gift = null, UniqueGiftInfo $unique_gift = null, GiftInfo $gift_upgrade_sent = null, string $connected_website = null, WriteAccessAllowed $write_access_allowed = null, PassportData $passport_data = null, ProximityAlertTriggered $proximity_alert_triggered = null, ChatBoostAdded $boost_added = null, ChatBackground $chat_background_set = null, ChecklistTasksDone $checklist_tasks_done = null, ChecklistTasksAdded $checklist_tasks_added = null, DirectMessagePriceChanged $direct_message_price_changed = null, ForumTopicCreated $forum_topic_created = null, ForumTopicEdited $forum_topic_edited = null, ForumTopicClosed $forum_topic_closed = null, ForumTopicReopened $forum_topic_reopened = null, GeneralForumTopicHidden $general_forum_topic_hidden = null, GeneralForumTopicUnhidden $general_forum_topic_unhidden = null, GiveawayCreated $giveaway_created = null, Giveaway $giveaway = null, GiveawayWinners $giveaway_winners = null, GiveawayCompleted $giveaway_completed = null, ManagedBotCreated $managed_bot_created = null, PaidMessagePriceChanged $paid_message_price_changed = null, PollOptionAdded $poll_option_added = null, PollOptionDeleted $poll_option_deleted = null, SuggestedPostApproved $suggested_post_approved = null, SuggestedPostApprovalFailed $suggested_post_approval_failed = null, SuggestedPostDeclined $suggested_post_declined = null, SuggestedPostPaid $suggested_post_paid = null, SuggestedPostRefunded $suggested_post_refunded = null, VideoChatScheduled $video_chat_scheduled = null, VideoChatStarted $video_chat_started = null, VideoChatEnded $video_chat_ended = null, VideoChatParticipantsInvited $video_chat_participants_invited = null, WebAppData $web_app_data = null, InlineKeyboardMarkup $reply_markup = null): self
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