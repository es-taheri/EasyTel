<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a message.
 * @method self message_id(int $value) Unique message identifier inside this chat; 0 for ephemeral messages. In specific instances (e.g., a message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent.
 * @method self message_thread_id(int $value) <em>Optional</em>. Unique identifier of a message thread or forum topic to which the message belongs; for supergroups and private chats only
 * @method self direct_messages_topic(DirectMessagesTopic $value) <em>Optional</em>. Information about the direct messages chat topic that contains the message
 * @method self from(User $value) <em>Optional</em>. Sender of the message; may be empty for messages sent to channels. For backward compatibility, if the message was sent on behalf of a chat, the field contains a fake sender user in non-channel chats.
 * @method self sender_chat(Chat $value) <em>Optional</em>. Sender of the message when sent on behalf of a chat. For example, the supergroup itself for messages sent by its anonymous administrators or a linked channel for messages automatically forwarded to the channel's discussion group. For backward compatibility, if the message was sent on behalf of a chat, the field <em>from</em> contains a fake sender user in non-channel chats.
 * @method self sender_boost_count(int $value) <em>Optional</em>. If the sender of the message boosted the chat, the number of boosts added by the user
 * @method self sender_business_bot(User $value) <em>Optional</em>. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
 * @method self sender_tag(string $value) <em>Optional</em>. Tag or custom title of the sender of the message; for supergroups only
 * @method self receiver_user(User $value) <em>Optional</em>. For ephemeral messages, the user who received the message
 * @method self ephemeral_message_id(int $value) <em>Optional</em>. For ephemeral messages, identifier of the ephemeral message inside this chat. The identifier may be reused for another ephemeral message after the message is deleted or expires.
 * @method self date(int $value) Date the message was sent in Unix time. It is always a positive number, representing a valid date.
 * @method self guest_query_id(string $value) <em>Optional</em>. The unique identifier for the guest query. Use this identifier with the method <a href="https://core.telegram.org/bots/api#answerguestquery">answerGuestQuery</a> to send a response message. If non-empty, the message belongs to the chat where the guest bot was summoned, which may not coincide with other existing bot chats sharing the same identifier.
 * @method self business_connection_id(string $value) <em>Optional</em>. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
 * @method self chat(Chat $value) Chat the message belongs to
 * @method self forward_origin(MessageOrigin $value) <em>Optional</em>. Information about the original message for forwarded messages
 * @method self is_topic_message(bool $value) <em>Optional</em>. <em>True</em>, if the message is sent to a topic in a forum supergroup or a private chat with the bot
 * @method self is_automatic_forward(bool $value) <em>Optional</em>. <em>True</em>, if the message is a channel post that was automatically forwarded to the connected discussion group
 * @method self reply_to_message(Message $value) <em>Optional</em>. For replies in the same chat and message thread, the original message. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain further <em>reply_to_message</em> fields even if it itself is a reply. If the message is a reply to an ephemeral message, then this field may be omitted.
 * @method self external_reply(ExternalReplyInfo $value) <em>Optional</em>. Information about the message that is being replied to, which may come from another chat or forum topic
 * @method self quote(TextQuote $value) <em>Optional</em>. For replies that quote part of the original message, the quoted part of the message
 * @method self reply_to_story(Story $value) <em>Optional</em>. For replies to a story, the original story
 * @method self reply_to_checklist_task_id(int $value) <em>Optional</em>. Identifier of the specific checklist task that is being replied to
 * @method self reply_to_poll_option_id(string $value) <em>Optional</em>. Persistent identifier of the specific poll option that is being replied to
 * @method self via_bot(User $value) <em>Optional</em>. Bot through which the message was sent
 * @method self guest_bot_caller_user(User $value) <em>Optional</em>. For a message sent by a guest bot, this is the user whose original message triggered the bot's response
 * @method self guest_bot_caller_chat(Chat $value) <em>Optional</em>. For a message sent by a guest bot, this is the chat whose original message triggered the bot's response
 * @method self edit_date(int $value) <em>Optional</em>. Date the message was last edited in Unix time
 * @method self has_protected_content(bool $value) <em>Optional</em>. <em>True</em>, if the message can't be forwarded
 * @method self is_from_offline(bool $value) <em>Optional</em>. <em>True</em>, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
 * @method self is_paid_post(bool $value) <em>Optional</em>. <em>True</em>, if the message is a paid post. Note that such posts must not be deleted for 24 hours to receive the payment and can't be edited.
 * @method self media_group_id(string $value) <em>Optional</em>. The unique identifier inside this chat of a media message group this message belongs to
 * @method self author_signature(string $value) <em>Optional</em>. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
 * @method self paid_star_count(int $value) <em>Optional</em>. The number of Telegram Stars that were paid by the sender of the message to send it
 * @method self text(string $value) <em>Optional</em>. For text messages, the actual UTF-8 text of the message
 * @method self entities(MessageEntity[] $value) <em>Optional</em>. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method self link_preview_options(LinkPreviewOptions $value) <em>Optional</em>. Options used for link preview generation for the message, if it is a text message and link preview options were changed
 * @method self suggested_post_info(SuggestedPostInfo $value) <em>Optional</em>. Information about suggested post parameters if the message is a suggested post in a channel direct messages chat. If the message is an approved or declined suggested post, then it can't be edited.
 * @method self effect_id(string $value) <em>Optional</em>. Unique identifier of the message effect added to the message
 * @method self rich_message(RichMessage $value) <em>Optional</em>. Message is a rich formatted message
 * @method self animation(Animation $value) <em>Optional</em>. Message is an animation, information about the animation. For backward compatibility, when this field is set, the <em>document</em> field will also be set.
 * @method self audio(Audio $value) <em>Optional</em>. Message is an audio file, information about the file
 * @method self document(Document $value) <em>Optional</em>. Message is a general file, information about the file
 * @method self live_photo(LivePhoto $value) <em>Optional</em>. Message is a live photo, information about the live photo. For backward compatibility, when this field is set, the <em>photo</em> field will also be set.
 * @method self paid_media(PaidMediaInfo $value) <em>Optional</em>. Message contains paid media; information about the paid media
 * @method self photo(PhotoSize[] $value) <em>Optional</em>. Message is a photo, available sizes of the photo
 * @method self sticker(Sticker $value) <em>Optional</em>. Message is a sticker, information about the sticker
 * @method self story(Story $value) <em>Optional</em>. Message is a forwarded story
 * @method self video(Video $value) <em>Optional</em>. Message is a video, information about the video
 * @method self video_note(VideoNote $value) <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
 * @method self voice(Voice $value) <em>Optional</em>. Message is a voice message, information about the file
 * @method self widget(Widget $value) <em>Optional</em>. Message is an HTML widget, information about the widget
 * @method self caption(string $value) <em>Optional</em>. Caption for the animation, audio, document, live photo, paid media, photo, video, voice, or widget
 * @method self caption_entities(MessageEntity[] $value) <em>Optional</em>. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @method self show_caption_above_media(bool $value) <em>Optional</em>. <em>True</em>, if the caption must be shown above the message media or the widget
 * @method self has_media_spoiler(bool $value) <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
 * @method self checklist(Checklist $value) <em>Optional</em>. Message is a checklist
 * @method self contact(Contact $value) <em>Optional</em>. Message is a shared contact, information about the contact
 * @method self dice(Dice $value) <em>Optional</em>. Message is a dice with random value
 * @method self game(Game $value) <em>Optional</em>. Message is a game, information about the game. <a href="https://core.telegram.org/bots/api#games">More about games »</a>
 * @method self poll(Poll $value) <em>Optional</em>. Message is a native poll, information about the poll
 * @method self venue(Venue $value) <em>Optional</em>. Message is a venue, information about the venue. For backward compatibility, when this field is set, the <em>location</em> field will also be set.
 * @method self location(Location $value) <em>Optional</em>. Message is a shared location, information about the location
 * @method self new_chat_members(User[] $value) <em>Optional</em>. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
 * @method self left_chat_member(User $value) <em>Optional</em>. A member was removed from the group, information about them (this member may be the bot itself)
 * @method self chat_owner_left(ChatOwnerLeft $value) <em>Optional</em>. Service message: chat owner has left
 * @method self chat_owner_changed(ChatOwnerChanged $value) <em>Optional</em>. Service message: chat owner has changed
 * @method self new_chat_title(string $value) <em>Optional</em>. A chat title was changed to this value
 * @method self new_chat_photo(PhotoSize[] $value) <em>Optional</em>. A chat photo was change to this value
 * @method self delete_chat_photo(bool $value) <em>Optional</em>. Service message: the chat photo was deleted
 * @method self group_chat_created(bool $value) <em>Optional</em>. Service message: the group has been created
 * @method self supergroup_chat_created(bool $value) <em>Optional</em>. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can't be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method self channel_chat_created(bool $value) <em>Optional</em>. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can't be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
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
 * @method self proximity_alert_triggered(ProximityAlertTriggered $value) <em>Optional</em>. Service message: a user in the chat triggered another user's proximity alert while sharing Live Location
 * @method self boost_added(ChatBoostAdded $value) <em>Optional</em>. Service message: user boosted the chat
 * @method self chat_background_set(ChatBackground $value) <em>Optional</em>. Service message: chat background set
 * @method self checklist_tasks_done(ChecklistTasksDone $value) <em>Optional</em>. Service message: some tasks in a checklist were marked as done or not done
 * @method self checklist_tasks_added(ChecklistTasksAdded $value) <em>Optional</em>. Service message: tasks were added to a checklist
 * @method self community_chat_added(CommunityChatAdded $value) <em>Optional</em>. Service message: chat or bot added to a <a href="https://core.telegram.org/bots/api#community">Community</a>
 * @method self community_chat_joined(CommunityChatJoined $value) <em>Optional</em>. Service message: chat was joined by a user from a <a href="https://core.telegram.org/bots/api#community">Community</a>
 * @method self community_chat_removed(CommunityChatRemoved $value) <em>Optional</em>. Service message: chat or bot removed from a <a href="https://core.telegram.org/bots/api#community">Community</a>
 * @method self direct_message_price_changed(DirectMessagePriceChanged $value) <em>Optional</em>. Service message: the price for paid messages in the corresponding direct messages chat of a channel has changed
 * @method self forum_topic_created(ForumTopicCreated $value) <em>Optional</em>. Service message: forum topic created
 * @method self forum_topic_edited(ForumTopicEdited $value) <em>Optional</em>. Service message: forum topic edited
 * @method self forum_topic_closed(ForumTopicClosed $value) <em>Optional</em>. Service message: forum topic closed
 * @method self forum_topic_reopened(ForumTopicReopened $value) <em>Optional</em>. Service message: forum topic reopened
 * @method self general_forum_topic_hidden(GeneralForumTopicHidden $value) <em>Optional</em>. Service message: the 'General' forum topic hidden
 * @method self general_forum_topic_unhidden(GeneralForumTopicUnhidden $value) <em>Optional</em>. Service message: the 'General' forum topic unhidden
 * @method self giveaway_created(GiveawayCreated $value) <em>Optional</em>. Service message: a scheduled giveaway was created
 * @method self giveaway(Giveaway $value) <em>Optional</em>. The message is a scheduled giveaway message
 * @method self giveaway_winners(GiveawayWinners $value) <em>Optional</em>. A giveaway with public winners was completed
 * @method self giveaway_completed(GiveawayCompleted $value) <em>Optional</em>. Service message: a giveaway without public winners was completed
 * @method self managed_bot_created(ManagedBotCreated $value) <em>Optional</em>. Service message: user created a bot that will be managed by the current bot
 * @method self paid_message_price_changed(PaidMessagePriceChanged $value) <em>Optional</em>. Service message: the price for paid messages has changed in the chat
 * @method self paid_messages_waived(PaidMessagesWaived $value) <em>Optional</em>. Service message: the sender of the message used a link of the other user, which allows sending messages to the user without paying Telegram Stars
 * @method self poll_option_added(PollOptionAdded $value) <em>Optional</em>. Service message: answer option was added to a poll
 * @method self poll_option_deleted(PollOptionDeleted $value) <em>Optional</em>. Service message: answer option was deleted from a poll
 * @method self suggested_post_approved(SuggestedPostApproved $value) <em>Optional</em>. Service message: a suggested post was approved
 * @method self suggested_post_approval_failed(SuggestedPostApprovalFailed $value) <em>Optional</em>. Service message: approval of a suggested post has failed
 * @method self suggested_post_declined(SuggestedPostDeclined $value) <em>Optional</em>. Service message: a suggested post was declined
 * @method self suggested_post_paid(SuggestedPostPaid $value) <em>Optional</em>. Service message: payment for a suggested post was received
 * @method self suggested_post_refunded(SuggestedPostRefunded $value) <em>Optional</em>. Service message: payment for a suggested post was refunded
 * @method self ton_wallet_transfer(TonWalletTransfer $value) <em>Optional</em>. Service message: a transfer with a TON wallet
 * @method self video_chat_scheduled(VideoChatScheduled $value) <em>Optional</em>. Service message: video chat scheduled
 * @method self video_chat_started(VideoChatStarted $value) <em>Optional</em>. Service message: video chat started
 * @method self video_chat_ended(VideoChatEnded $value) <em>Optional</em>. Service message: video chat ended
 * @method self video_chat_participants_invited(VideoChatParticipantsInvited $value) <em>Optional</em>. Service message: new participants invited to a video chat
 * @method self web_app_data(WebAppData $value) <em>Optional</em>. Service message: data sent by a Web App
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message. <code>login_url</code> buttons are represented as ordinary <code>url</code> buttons.
 */
class Message
{
    /** @var int */
    public int $message_id;
    /** @var int */
    public int $message_thread_id;
    /** @var DirectMessagesTopic */
    public DirectMessagesTopic $direct_messages_topic;
    /** @var User */
    public User $from;
    /** @var Chat */
    public Chat $sender_chat;
    /** @var int */
    public int $sender_boost_count;
    /** @var User */
    public User $sender_business_bot;
    /** @var string */
    public string $sender_tag;
    /** @var User */
    public User $receiver_user;
    /** @var int */
    public int $ephemeral_message_id;
    /** @var int */
    public int $date;
    /** @var string */
    public string $guest_query_id;
    /** @var string */
    public string $business_connection_id;
    /** @var Chat */
    public Chat $chat;
    /** @var MessageOrigin */
    public MessageOrigin $forward_origin;
    /** @var bool */
    public bool $is_topic_message;
    /** @var bool */
    public bool $is_automatic_forward;
    /** @var Message */
    public Message $reply_to_message;
    /** @var ExternalReplyInfo */
    public ExternalReplyInfo $external_reply;
    /** @var TextQuote */
    public TextQuote $quote;
    /** @var Story */
    public Story $reply_to_story;
    /** @var int */
    public int $reply_to_checklist_task_id;
    /** @var string */
    public string $reply_to_poll_option_id;
    /** @var User */
    public User $via_bot;
    /** @var User */
    public User $guest_bot_caller_user;
    /** @var Chat */
    public Chat $guest_bot_caller_chat;
    /** @var int */
    public int $edit_date;
    /** @var bool */
    public bool $has_protected_content;
    /** @var bool */
    public bool $is_from_offline;
    /** @var bool */
    public bool $is_paid_post;
    /** @var string */
    public string $media_group_id;
    /** @var string */
    public string $author_signature;
    /** @var int */
    public int $paid_star_count;
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $entities;
    /** @var LinkPreviewOptions */
    public LinkPreviewOptions $link_preview_options;
    /** @var SuggestedPostInfo */
    public SuggestedPostInfo $suggested_post_info;
    /** @var string */
    public string $effect_id;
    /** @var RichMessage */
    public RichMessage $rich_message;
    /** @var Animation */
    public Animation $animation;
    /** @var Audio */
    public Audio $audio;
    /** @var Document */
    public Document $document;
    /** @var LivePhoto */
    public LivePhoto $live_photo;
    /** @var PaidMediaInfo */
    public PaidMediaInfo $paid_media;
    /** @var PhotoSize[] */
    public array $photo;
    /** @var Sticker */
    public Sticker $sticker;
    /** @var Story */
    public Story $story;
    /** @var Video */
    public Video $video;
    /** @var VideoNote */
    public VideoNote $video_note;
    /** @var Voice */
    public Voice $voice;
    /** @var Widget */
    public Widget $widget;
    /** @var string */
    public string $caption;
    /** @var MessageEntity[] */
    public array $caption_entities;
    /** @var bool */
    public bool $show_caption_above_media;
    /** @var bool */
    public bool $has_media_spoiler;
    /** @var Checklist */
    public Checklist $checklist;
    /** @var Contact */
    public Contact $contact;
    /** @var Dice */
    public Dice $dice;
    /** @var Game */
    public Game $game;
    /** @var Poll */
    public Poll $poll;
    /** @var Venue */
    public Venue $venue;
    /** @var Location */
    public Location $location;
    /** @var User[] */
    public array $new_chat_members;
    /** @var User */
    public User $left_chat_member;
    /** @var ChatOwnerLeft */
    public ChatOwnerLeft $chat_owner_left;
    /** @var ChatOwnerChanged */
    public ChatOwnerChanged $chat_owner_changed;
    /** @var string */
    public string $new_chat_title;
    /** @var PhotoSize[] */
    public array $new_chat_photo;
    /** @var bool */
    public bool $delete_chat_photo;
    /** @var bool */
    public bool $group_chat_created;
    /** @var bool */
    public bool $supergroup_chat_created;
    /** @var bool */
    public bool $channel_chat_created;
    /** @var MessageAutoDeleteTimerChanged */
    public MessageAutoDeleteTimerChanged $message_auto_delete_timer_changed;
    /** @var int */
    public int $migrate_to_chat_id;
    /** @var int */
    public int $migrate_from_chat_id;
    /** @var MaybeInaccessibleMessage */
    public MaybeInaccessibleMessage $pinned_message;
    /** @var Invoice */
    public Invoice $invoice;
    /** @var SuccessfulPayment */
    public SuccessfulPayment $successful_payment;
    /** @var RefundedPayment */
    public RefundedPayment $refunded_payment;
    /** @var UsersShared */
    public UsersShared $users_shared;
    /** @var ChatShared */
    public ChatShared $chat_shared;
    /** @var GiftInfo */
    public GiftInfo $gift;
    /** @var UniqueGiftInfo */
    public UniqueGiftInfo $unique_gift;
    /** @var GiftInfo */
    public GiftInfo $gift_upgrade_sent;
    /** @var string */
    public string $connected_website;
    /** @var WriteAccessAllowed */
    public WriteAccessAllowed $write_access_allowed;
    /** @var PassportData */
    public PassportData $passport_data;
    /** @var ProximityAlertTriggered */
    public ProximityAlertTriggered $proximity_alert_triggered;
    /** @var ChatBoostAdded */
    public ChatBoostAdded $boost_added;
    /** @var ChatBackground */
    public ChatBackground $chat_background_set;
    /** @var ChecklistTasksDone */
    public ChecklistTasksDone $checklist_tasks_done;
    /** @var ChecklistTasksAdded */
    public ChecklistTasksAdded $checklist_tasks_added;
    /** @var CommunityChatAdded */
    public CommunityChatAdded $community_chat_added;
    /** @var CommunityChatJoined */
    public CommunityChatJoined $community_chat_joined;
    /** @var CommunityChatRemoved */
    public CommunityChatRemoved $community_chat_removed;
    /** @var DirectMessagePriceChanged */
    public DirectMessagePriceChanged $direct_message_price_changed;
    /** @var ForumTopicCreated */
    public ForumTopicCreated $forum_topic_created;
    /** @var ForumTopicEdited */
    public ForumTopicEdited $forum_topic_edited;
    /** @var ForumTopicClosed */
    public ForumTopicClosed $forum_topic_closed;
    /** @var ForumTopicReopened */
    public ForumTopicReopened $forum_topic_reopened;
    /** @var GeneralForumTopicHidden */
    public GeneralForumTopicHidden $general_forum_topic_hidden;
    /** @var GeneralForumTopicUnhidden */
    public GeneralForumTopicUnhidden $general_forum_topic_unhidden;
    /** @var GiveawayCreated */
    public GiveawayCreated $giveaway_created;
    /** @var Giveaway */
    public Giveaway $giveaway;
    /** @var GiveawayWinners */
    public GiveawayWinners $giveaway_winners;
    /** @var GiveawayCompleted */
    public GiveawayCompleted $giveaway_completed;
    /** @var ManagedBotCreated */
    public ManagedBotCreated $managed_bot_created;
    /** @var PaidMessagePriceChanged */
    public PaidMessagePriceChanged $paid_message_price_changed;
    /** @var PaidMessagesWaived */
    public PaidMessagesWaived $paid_messages_waived;
    /** @var PollOptionAdded */
    public PollOptionAdded $poll_option_added;
    /** @var PollOptionDeleted */
    public PollOptionDeleted $poll_option_deleted;
    /** @var SuggestedPostApproved */
    public SuggestedPostApproved $suggested_post_approved;
    /** @var SuggestedPostApprovalFailed */
    public SuggestedPostApprovalFailed $suggested_post_approval_failed;
    /** @var SuggestedPostDeclined */
    public SuggestedPostDeclined $suggested_post_declined;
    /** @var SuggestedPostPaid */
    public SuggestedPostPaid $suggested_post_paid;
    /** @var SuggestedPostRefunded */
    public SuggestedPostRefunded $suggested_post_refunded;
    /** @var TonWalletTransfer */
    public TonWalletTransfer $ton_wallet_transfer;
    /** @var VideoChatScheduled */
    public VideoChatScheduled $video_chat_scheduled;
    /** @var VideoChatStarted */
    public VideoChatStarted $video_chat_started;
    /** @var VideoChatEnded */
    public VideoChatEnded $video_chat_ended;
    /** @var VideoChatParticipantsInvited */
    public VideoChatParticipantsInvited $video_chat_participants_invited;
    /** @var WebAppData */
    public WebAppData $web_app_data;
    /** @var InlineKeyboardMarkup */
    public InlineKeyboardMarkup $reply_markup;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a message.
     * @param int|null $message_id Unique message identifier inside this chat; 0 for ephemeral messages. In specific instances (e.g., a message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent.
     * @param int|null $message_thread_id <em>Optional</em>. Unique identifier of a message thread or forum topic to which the message belongs; for supergroups and private chats only
     * @param DirectMessagesTopic|null $direct_messages_topic <em>Optional</em>. Information about the direct messages chat topic that contains the message
     * @param User|null $from <em>Optional</em>. Sender of the message; may be empty for messages sent to channels. For backward compatibility, if the message was sent on behalf of a chat, the field contains a fake sender user in non-channel chats.
     * @param Chat|null $sender_chat <em>Optional</em>. Sender of the message when sent on behalf of a chat. For example, the supergroup itself for messages sent by its anonymous administrators or a linked channel for messages automatically forwarded to the channel's discussion group. For backward compatibility, if the message was sent on behalf of a chat, the field <em>from</em> contains a fake sender user in non-channel chats.
     * @param int|null $sender_boost_count <em>Optional</em>. If the sender of the message boosted the chat, the number of boosts added by the user
     * @param User|null $sender_business_bot <em>Optional</em>. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
     * @param string|null $sender_tag <em>Optional</em>. Tag or custom title of the sender of the message; for supergroups only
     * @param User|null $receiver_user <em>Optional</em>. For ephemeral messages, the user who received the message
     * @param int|null $ephemeral_message_id <em>Optional</em>. For ephemeral messages, identifier of the ephemeral message inside this chat. The identifier may be reused for another ephemeral message after the message is deleted or expires.
     * @param int|null $date Date the message was sent in Unix time. It is always a positive number, representing a valid date.
     * @param string|null $guest_query_id <em>Optional</em>. The unique identifier for the guest query. Use this identifier with the method <a href="https://core.telegram.org/bots/api#answerguestquery">answerGuestQuery</a> to send a response message. If non-empty, the message belongs to the chat where the guest bot was summoned, which may not coincide with other existing bot chats sharing the same identifier.
     * @param string|null $business_connection_id <em>Optional</em>. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
     * @param Chat|null $chat Chat the message belongs to
     * @param MessageOrigin|null $forward_origin <em>Optional</em>. Information about the original message for forwarded messages
     * @param bool|null $is_topic_message <em>Optional</em>. <em>True</em>, if the message is sent to a topic in a forum supergroup or a private chat with the bot
     * @param bool|null $is_automatic_forward <em>Optional</em>. <em>True</em>, if the message is a channel post that was automatically forwarded to the connected discussion group
     * @param Message|null $reply_to_message <em>Optional</em>. For replies in the same chat and message thread, the original message. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain further <em>reply_to_message</em> fields even if it itself is a reply. If the message is a reply to an ephemeral message, then this field may be omitted.
     * @param ExternalReplyInfo|null $external_reply <em>Optional</em>. Information about the message that is being replied to, which may come from another chat or forum topic
     * @param TextQuote|null $quote <em>Optional</em>. For replies that quote part of the original message, the quoted part of the message
     * @param Story|null $reply_to_story <em>Optional</em>. For replies to a story, the original story
     * @param int|null $reply_to_checklist_task_id <em>Optional</em>. Identifier of the specific checklist task that is being replied to
     * @param string|null $reply_to_poll_option_id <em>Optional</em>. Persistent identifier of the specific poll option that is being replied to
     * @param User|null $via_bot <em>Optional</em>. Bot through which the message was sent
     * @param User|null $guest_bot_caller_user <em>Optional</em>. For a message sent by a guest bot, this is the user whose original message triggered the bot's response
     * @param Chat|null $guest_bot_caller_chat <em>Optional</em>. For a message sent by a guest bot, this is the chat whose original message triggered the bot's response
     * @param int|null $edit_date <em>Optional</em>. Date the message was last edited in Unix time
     * @param bool|null $has_protected_content <em>Optional</em>. <em>True</em>, if the message can't be forwarded
     * @param bool|null $is_from_offline <em>Optional</em>. <em>True</em>, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
     * @param bool|null $is_paid_post <em>Optional</em>. <em>True</em>, if the message is a paid post. Note that such posts must not be deleted for 24 hours to receive the payment and can't be edited.
     * @param string|null $media_group_id <em>Optional</em>. The unique identifier inside this chat of a media message group this message belongs to
     * @param string|null $author_signature <em>Optional</em>. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
     * @param int|null $paid_star_count <em>Optional</em>. The number of Telegram Stars that were paid by the sender of the message to send it
     * @param string|null $text <em>Optional</em>. For text messages, the actual UTF-8 text of the message
     * @param MessageEntity[]|null $entities <em>Optional</em>. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     * @param LinkPreviewOptions|null $link_preview_options <em>Optional</em>. Options used for link preview generation for the message, if it is a text message and link preview options were changed
     * @param SuggestedPostInfo|null $suggested_post_info <em>Optional</em>. Information about suggested post parameters if the message is a suggested post in a channel direct messages chat. If the message is an approved or declined suggested post, then it can't be edited.
     * @param string|null $effect_id <em>Optional</em>. Unique identifier of the message effect added to the message
     * @param RichMessage|null $rich_message <em>Optional</em>. Message is a rich formatted message
     * @param Animation|null $animation <em>Optional</em>. Message is an animation, information about the animation. For backward compatibility, when this field is set, the <em>document</em> field will also be set.
     * @param Audio|null $audio <em>Optional</em>. Message is an audio file, information about the file
     * @param Document|null $document <em>Optional</em>. Message is a general file, information about the file
     * @param LivePhoto|null $live_photo <em>Optional</em>. Message is a live photo, information about the live photo. For backward compatibility, when this field is set, the <em>photo</em> field will also be set.
     * @param PaidMediaInfo|null $paid_media <em>Optional</em>. Message contains paid media; information about the paid media
     * @param PhotoSize[]|null $photo <em>Optional</em>. Message is a photo, available sizes of the photo
     * @param Sticker|null $sticker <em>Optional</em>. Message is a sticker, information about the sticker
     * @param Story|null $story <em>Optional</em>. Message is a forwarded story
     * @param Video|null $video <em>Optional</em>. Message is a video, information about the video
     * @param VideoNote|null $video_note <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
     * @param Voice|null $voice <em>Optional</em>. Message is a voice message, information about the file
     * @param Widget|null $widget <em>Optional</em>. Message is an HTML widget, information about the widget
     * @param string|null $caption <em>Optional</em>. Caption for the animation, audio, document, live photo, paid media, photo, video, voice, or widget
     * @param MessageEntity[]|null $caption_entities <em>Optional</em>. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
     * @param bool|null $show_caption_above_media <em>Optional</em>. <em>True</em>, if the caption must be shown above the message media or the widget
     * @param bool|null $has_media_spoiler <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
     * @param Checklist|null $checklist <em>Optional</em>. Message is a checklist
     * @param Contact|null $contact <em>Optional</em>. Message is a shared contact, information about the contact
     * @param Dice|null $dice <em>Optional</em>. Message is a dice with random value
     * @param Game|null $game <em>Optional</em>. Message is a game, information about the game. <a href="https://core.telegram.org/bots/api#games">More about games »</a>
     * @param Poll|null $poll <em>Optional</em>. Message is a native poll, information about the poll
     * @param Venue|null $venue <em>Optional</em>. Message is a venue, information about the venue. For backward compatibility, when this field is set, the <em>location</em> field will also be set.
     * @param Location|null $location <em>Optional</em>. Message is a shared location, information about the location
     * @param User[]|null $new_chat_members <em>Optional</em>. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
     * @param User|null $left_chat_member <em>Optional</em>. A member was removed from the group, information about them (this member may be the bot itself)
     * @param ChatOwnerLeft|null $chat_owner_left <em>Optional</em>. Service message: chat owner has left
     * @param ChatOwnerChanged|null $chat_owner_changed <em>Optional</em>. Service message: chat owner has changed
     * @param string|null $new_chat_title <em>Optional</em>. A chat title was changed to this value
     * @param PhotoSize[]|null $new_chat_photo <em>Optional</em>. A chat photo was change to this value
     * @param bool|null $delete_chat_photo <em>Optional</em>. Service message: the chat photo was deleted
     * @param bool|null $group_chat_created <em>Optional</em>. Service message: the group has been created
     * @param bool|null $supergroup_chat_created <em>Optional</em>. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can't be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
     * @param bool|null $channel_chat_created <em>Optional</em>. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can't be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
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
     * @param ProximityAlertTriggered|null $proximity_alert_triggered <em>Optional</em>. Service message: a user in the chat triggered another user's proximity alert while sharing Live Location
     * @param ChatBoostAdded|null $boost_added <em>Optional</em>. Service message: user boosted the chat
     * @param ChatBackground|null $chat_background_set <em>Optional</em>. Service message: chat background set
     * @param ChecklistTasksDone|null $checklist_tasks_done <em>Optional</em>. Service message: some tasks in a checklist were marked as done or not done
     * @param ChecklistTasksAdded|null $checklist_tasks_added <em>Optional</em>. Service message: tasks were added to a checklist
     * @param CommunityChatAdded|null $community_chat_added <em>Optional</em>. Service message: chat or bot added to a <a href="https://core.telegram.org/bots/api#community">Community</a>
     * @param CommunityChatJoined|null $community_chat_joined <em>Optional</em>. Service message: chat was joined by a user from a <a href="https://core.telegram.org/bots/api#community">Community</a>
     * @param CommunityChatRemoved|null $community_chat_removed <em>Optional</em>. Service message: chat or bot removed from a <a href="https://core.telegram.org/bots/api#community">Community</a>
     * @param DirectMessagePriceChanged|null $direct_message_price_changed <em>Optional</em>. Service message: the price for paid messages in the corresponding direct messages chat of a channel has changed
     * @param ForumTopicCreated|null $forum_topic_created <em>Optional</em>. Service message: forum topic created
     * @param ForumTopicEdited|null $forum_topic_edited <em>Optional</em>. Service message: forum topic edited
     * @param ForumTopicClosed|null $forum_topic_closed <em>Optional</em>. Service message: forum topic closed
     * @param ForumTopicReopened|null $forum_topic_reopened <em>Optional</em>. Service message: forum topic reopened
     * @param GeneralForumTopicHidden|null $general_forum_topic_hidden <em>Optional</em>. Service message: the 'General' forum topic hidden
     * @param GeneralForumTopicUnhidden|null $general_forum_topic_unhidden <em>Optional</em>. Service message: the 'General' forum topic unhidden
     * @param GiveawayCreated|null $giveaway_created <em>Optional</em>. Service message: a scheduled giveaway was created
     * @param Giveaway|null $giveaway <em>Optional</em>. The message is a scheduled giveaway message
     * @param GiveawayWinners|null $giveaway_winners <em>Optional</em>. A giveaway with public winners was completed
     * @param GiveawayCompleted|null $giveaway_completed <em>Optional</em>. Service message: a giveaway without public winners was completed
     * @param ManagedBotCreated|null $managed_bot_created <em>Optional</em>. Service message: user created a bot that will be managed by the current bot
     * @param PaidMessagePriceChanged|null $paid_message_price_changed <em>Optional</em>. Service message: the price for paid messages has changed in the chat
     * @param PaidMessagesWaived|null $paid_messages_waived <em>Optional</em>. Service message: the sender of the message used a link of the other user, which allows sending messages to the user without paying Telegram Stars
     * @param PollOptionAdded|null $poll_option_added <em>Optional</em>. Service message: answer option was added to a poll
     * @param PollOptionDeleted|null $poll_option_deleted <em>Optional</em>. Service message: answer option was deleted from a poll
     * @param SuggestedPostApproved|null $suggested_post_approved <em>Optional</em>. Service message: a suggested post was approved
     * @param SuggestedPostApprovalFailed|null $suggested_post_approval_failed <em>Optional</em>. Service message: approval of a suggested post has failed
     * @param SuggestedPostDeclined|null $suggested_post_declined <em>Optional</em>. Service message: a suggested post was declined
     * @param SuggestedPostPaid|null $suggested_post_paid <em>Optional</em>. Service message: payment for a suggested post was received
     * @param SuggestedPostRefunded|null $suggested_post_refunded <em>Optional</em>. Service message: payment for a suggested post was refunded
     * @param TonWalletTransfer|null $ton_wallet_transfer <em>Optional</em>. Service message: a transfer with a TON wallet
     * @param VideoChatScheduled|null $video_chat_scheduled <em>Optional</em>. Service message: video chat scheduled
     * @param VideoChatStarted|null $video_chat_started <em>Optional</em>. Service message: video chat started
     * @param VideoChatEnded|null $video_chat_ended <em>Optional</em>. Service message: video chat ended
     * @param VideoChatParticipantsInvited|null $video_chat_participants_invited <em>Optional</em>. Service message: new participants invited to a video chat
     * @param WebAppData|null $web_app_data <em>Optional</em>. Service message: data sent by a Web App
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message. <code>login_url</code> buttons are represented as ordinary <code>url</code> buttons.
     */
    public static function make(int $message_id = null, int $message_thread_id = null, DirectMessagesTopic $direct_messages_topic = null, User $from = null, Chat $sender_chat = null, int $sender_boost_count = null, User $sender_business_bot = null, string $sender_tag = null, User $receiver_user = null, int $ephemeral_message_id = null, int $date = null, string $guest_query_id = null, string $business_connection_id = null, Chat $chat = null, MessageOrigin $forward_origin = null, bool $is_topic_message = null, bool $is_automatic_forward = null, Message $reply_to_message = null, ExternalReplyInfo $external_reply = null, TextQuote $quote = null, Story $reply_to_story = null, int $reply_to_checklist_task_id = null, string $reply_to_poll_option_id = null, User $via_bot = null, User $guest_bot_caller_user = null, Chat $guest_bot_caller_chat = null, int $edit_date = null, bool $has_protected_content = null, bool $is_from_offline = null, bool $is_paid_post = null, string $media_group_id = null, string $author_signature = null, int $paid_star_count = null, string $text = null, array $entities = null, LinkPreviewOptions $link_preview_options = null, SuggestedPostInfo $suggested_post_info = null, string $effect_id = null, RichMessage $rich_message = null, Animation $animation = null, Audio $audio = null, Document $document = null, LivePhoto $live_photo = null, PaidMediaInfo $paid_media = null, array $photo = null, Sticker $sticker = null, Story $story = null, Video $video = null, VideoNote $video_note = null, Voice $voice = null, Widget $widget = null, string $caption = null, array $caption_entities = null, bool $show_caption_above_media = null, bool $has_media_spoiler = null, Checklist $checklist = null, Contact $contact = null, Dice $dice = null, Game $game = null, Poll $poll = null, Venue $venue = null, Location $location = null, array $new_chat_members = null, User $left_chat_member = null, ChatOwnerLeft $chat_owner_left = null, ChatOwnerChanged $chat_owner_changed = null, string $new_chat_title = null, array $new_chat_photo = null, bool $delete_chat_photo = null, bool $group_chat_created = null, bool $supergroup_chat_created = null, bool $channel_chat_created = null, MessageAutoDeleteTimerChanged $message_auto_delete_timer_changed = null, int $migrate_to_chat_id = null, int $migrate_from_chat_id = null, MaybeInaccessibleMessage $pinned_message = null, Invoice $invoice = null, SuccessfulPayment $successful_payment = null, RefundedPayment $refunded_payment = null, UsersShared $users_shared = null, ChatShared $chat_shared = null, GiftInfo $gift = null, UniqueGiftInfo $unique_gift = null, GiftInfo $gift_upgrade_sent = null, string $connected_website = null, WriteAccessAllowed $write_access_allowed = null, PassportData $passport_data = null, ProximityAlertTriggered $proximity_alert_triggered = null, ChatBoostAdded $boost_added = null, ChatBackground $chat_background_set = null, ChecklistTasksDone $checklist_tasks_done = null, ChecklistTasksAdded $checklist_tasks_added = null, CommunityChatAdded $community_chat_added = null, CommunityChatJoined $community_chat_joined = null, CommunityChatRemoved $community_chat_removed = null, DirectMessagePriceChanged $direct_message_price_changed = null, ForumTopicCreated $forum_topic_created = null, ForumTopicEdited $forum_topic_edited = null, ForumTopicClosed $forum_topic_closed = null, ForumTopicReopened $forum_topic_reopened = null, GeneralForumTopicHidden $general_forum_topic_hidden = null, GeneralForumTopicUnhidden $general_forum_topic_unhidden = null, GiveawayCreated $giveaway_created = null, Giveaway $giveaway = null, GiveawayWinners $giveaway_winners = null, GiveawayCompleted $giveaway_completed = null, ManagedBotCreated $managed_bot_created = null, PaidMessagePriceChanged $paid_message_price_changed = null, PaidMessagesWaived $paid_messages_waived = null, PollOptionAdded $poll_option_added = null, PollOptionDeleted $poll_option_deleted = null, SuggestedPostApproved $suggested_post_approved = null, SuggestedPostApprovalFailed $suggested_post_approval_failed = null, SuggestedPostDeclined $suggested_post_declined = null, SuggestedPostPaid $suggested_post_paid = null, SuggestedPostRefunded $suggested_post_refunded = null, TonWalletTransfer $ton_wallet_transfer = null, VideoChatScheduled $video_chat_scheduled = null, VideoChatStarted $video_chat_started = null, VideoChatEnded $video_chat_ended = null, VideoChatParticipantsInvited $video_chat_participants_invited = null, WebAppData $web_app_data = null, InlineKeyboardMarkup $reply_markup = null): self
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