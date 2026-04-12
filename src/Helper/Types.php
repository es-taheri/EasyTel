<?php

namespace EasyTel\Helper;

use JSON\json;
use EasyTel\Types\Update;
use EasyTel\Types\WebhookInfo;
use EasyTel\Types\User;
use EasyTel\Types\Chat;
use EasyTel\Types\ChatFullInfo;
use EasyTel\Types\Message;
use EasyTel\Types\MessageId;
use EasyTel\Types\InaccessibleMessage;
use EasyTel\Types\MaybeInaccessibleMessage;
use EasyTel\Types\MessageEntity;
use EasyTel\Types\TextQuote;
use EasyTel\Types\ExternalReplyInfo;
use EasyTel\Types\ReplyParameters;
use EasyTel\Types\MessageOrigin;
use EasyTel\Types\MessageOriginUser;
use EasyTel\Types\MessageOriginHiddenUser;
use EasyTel\Types\MessageOriginChat;
use EasyTel\Types\MessageOriginChannel;
use EasyTel\Types\PhotoSize;
use EasyTel\Types\Animation;
use EasyTel\Types\Audio;
use EasyTel\Types\Document;
use EasyTel\Types\Story;
use EasyTel\Types\VideoQuality;
use EasyTel\Types\Video;
use EasyTel\Types\VideoNote;
use EasyTel\Types\Voice;
use EasyTel\Types\PaidMediaInfo;
use EasyTel\Types\PaidMedia;
use EasyTel\Types\PaidMediaPreview;
use EasyTel\Types\PaidMediaPhoto;
use EasyTel\Types\PaidMediaVideo;
use EasyTel\Types\Contact;
use EasyTel\Types\Dice;
use EasyTel\Types\PollOption;
use EasyTel\Types\InputPollOption;
use EasyTel\Types\PollAnswer;
use EasyTel\Types\Poll;
use EasyTel\Types\ChecklistTask;
use EasyTel\Types\Checklist;
use EasyTel\Types\InputChecklistTask;
use EasyTel\Types\InputChecklist;
use EasyTel\Types\ChecklistTasksDone;
use EasyTel\Types\ChecklistTasksAdded;
use EasyTel\Types\Location;
use EasyTel\Types\Venue;
use EasyTel\Types\WebAppData;
use EasyTel\Types\ProximityAlertTriggered;
use EasyTel\Types\MessageAutoDeleteTimerChanged;
use EasyTel\Types\ManagedBotCreated;
use EasyTel\Types\ManagedBotUpdated;
use EasyTel\Types\PollOptionAdded;
use EasyTel\Types\PollOptionDeleted;
use EasyTel\Types\ChatBoostAdded;
use EasyTel\Types\BackgroundFill;
use EasyTel\Types\BackgroundFillSolid;
use EasyTel\Types\BackgroundFillGradient;
use EasyTel\Types\BackgroundFillFreeformGradient;
use EasyTel\Types\BackgroundType;
use EasyTel\Types\BackgroundTypeFill;
use EasyTel\Types\BackgroundTypeWallpaper;
use EasyTel\Types\BackgroundTypePattern;
use EasyTel\Types\BackgroundTypeChatTheme;
use EasyTel\Types\ChatBackground;
use EasyTel\Types\ForumTopicCreated;
use EasyTel\Types\ForumTopicClosed;
use EasyTel\Types\ForumTopicEdited;
use EasyTel\Types\ForumTopicReopened;
use EasyTel\Types\GeneralForumTopicHidden;
use EasyTel\Types\GeneralForumTopicUnhidden;
use EasyTel\Types\SharedUser;
use EasyTel\Types\UsersShared;
use EasyTel\Types\ChatShared;
use EasyTel\Types\WriteAccessAllowed;
use EasyTel\Types\VideoChatScheduled;
use EasyTel\Types\VideoChatStarted;
use EasyTel\Types\VideoChatEnded;
use EasyTel\Types\VideoChatParticipantsInvited;
use EasyTel\Types\PaidMessagePriceChanged;
use EasyTel\Types\DirectMessagePriceChanged;
use EasyTel\Types\SuggestedPostApproved;
use EasyTel\Types\SuggestedPostApprovalFailed;
use EasyTel\Types\SuggestedPostDeclined;
use EasyTel\Types\SuggestedPostPaid;
use EasyTel\Types\SuggestedPostRefunded;
use EasyTel\Types\GiveawayCreated;
use EasyTel\Types\Giveaway;
use EasyTel\Types\GiveawayWinners;
use EasyTel\Types\GiveawayCompleted;
use EasyTel\Types\LinkPreviewOptions;
use EasyTel\Types\SuggestedPostPrice;
use EasyTel\Types\SuggestedPostInfo;
use EasyTel\Types\SuggestedPostParameters;
use EasyTel\Types\DirectMessagesTopic;
use EasyTel\Types\UserProfilePhotos;
use EasyTel\Types\UserProfileAudios;
use EasyTel\Types\File;
use EasyTel\Types\WebAppInfo;
use EasyTel\Types\ReplyKeyboardMarkup;
use EasyTel\Types\KeyboardButton;
use EasyTel\Types\KeyboardButtonRequestUsers;
use EasyTel\Types\KeyboardButtonRequestChat;
use EasyTel\Types\KeyboardButtonRequestManagedBot;
use EasyTel\Types\KeyboardButtonPollType;
use EasyTel\Types\ReplyKeyboardRemove;
use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\InlineKeyboardButton;
use EasyTel\Types\LoginUrl;
use EasyTel\Types\SwitchInlineQueryChosenChat;
use EasyTel\Types\CopyTextButton;
use EasyTel\Types\CallbackQuery;
use EasyTel\Types\ForceReply;
use EasyTel\Types\ChatPhoto;
use EasyTel\Types\ChatInviteLink;
use EasyTel\Types\ChatAdministratorRights;
use EasyTel\Types\ChatMemberUpdated;
use EasyTel\Types\ChatMember;
use EasyTel\Types\ChatMemberOwner;
use EasyTel\Types\ChatMemberAdministrator;
use EasyTel\Types\ChatMemberMember;
use EasyTel\Types\ChatMemberRestricted;
use EasyTel\Types\ChatMemberLeft;
use EasyTel\Types\ChatMemberBanned;
use EasyTel\Types\ChatJoinRequest;
use EasyTel\Types\ChatPermissions;
use EasyTel\Types\Birthdate;
use EasyTel\Types\BusinessIntro;
use EasyTel\Types\BusinessLocation;
use EasyTel\Types\BusinessOpeningHoursInterval;
use EasyTel\Types\BusinessOpeningHours;
use EasyTel\Types\UserRating;
use EasyTel\Types\StoryAreaPosition;
use EasyTel\Types\LocationAddress;
use EasyTel\Types\StoryAreaType;
use EasyTel\Types\StoryAreaTypeLocation;
use EasyTel\Types\StoryAreaTypeSuggestedReaction;
use EasyTel\Types\StoryAreaTypeLink;
use EasyTel\Types\StoryAreaTypeWeather;
use EasyTel\Types\StoryAreaTypeUniqueGift;
use EasyTel\Types\StoryArea;
use EasyTel\Types\ChatLocation;
use EasyTel\Types\ReactionType;
use EasyTel\Types\ReactionTypeEmoji;
use EasyTel\Types\ReactionTypeCustomEmoji;
use EasyTel\Types\ReactionTypePaid;
use EasyTel\Types\ReactionCount;
use EasyTel\Types\MessageReactionUpdated;
use EasyTel\Types\MessageReactionCountUpdated;
use EasyTel\Types\ForumTopic;
use EasyTel\Types\GiftBackground;
use EasyTel\Types\Gift;
use EasyTel\Types\Gifts;
use EasyTel\Types\UniqueGiftModel;
use EasyTel\Types\UniqueGiftSymbol;
use EasyTel\Types\UniqueGiftBackdropColors;
use EasyTel\Types\UniqueGiftBackdrop;
use EasyTel\Types\UniqueGiftColors;
use EasyTel\Types\UniqueGift;
use EasyTel\Types\GiftInfo;
use EasyTel\Types\UniqueGiftInfo;
use EasyTel\Types\OwnedGift;
use EasyTel\Types\OwnedGiftRegular;
use EasyTel\Types\OwnedGiftUnique;
use EasyTel\Types\OwnedGifts;
use EasyTel\Types\AcceptedGiftTypes;
use EasyTel\Types\StarAmount;
use EasyTel\Types\BotCommand;
use EasyTel\Types\BotCommandScope;
use EasyTel\Types\BotCommandScopeDefault;
use EasyTel\Types\BotCommandScopeAllPrivateChats;
use EasyTel\Types\BotCommandScopeAllGroupChats;
use EasyTel\Types\BotCommandScopeAllChatAdministrators;
use EasyTel\Types\BotCommandScopeChat;
use EasyTel\Types\BotCommandScopeChatAdministrators;
use EasyTel\Types\BotCommandScopeChatMember;
use EasyTel\Types\BotName;
use EasyTel\Types\BotDescription;
use EasyTel\Types\BotShortDescription;
use EasyTel\Types\MenuButton;
use EasyTel\Types\MenuButtonCommands;
use EasyTel\Types\MenuButtonWebApp;
use EasyTel\Types\MenuButtonDefault;
use EasyTel\Types\ChatBoostSource;
use EasyTel\Types\ChatBoostSourcePremium;
use EasyTel\Types\ChatBoostSourceGiftCode;
use EasyTel\Types\ChatBoostSourceGiveaway;
use EasyTel\Types\ChatBoost;
use EasyTel\Types\ChatBoostUpdated;
use EasyTel\Types\ChatBoostRemoved;
use EasyTel\Types\ChatOwnerLeft;
use EasyTel\Types\ChatOwnerChanged;
use EasyTel\Types\UserChatBoosts;
use EasyTel\Types\BusinessBotRights;
use EasyTel\Types\BusinessConnection;
use EasyTel\Types\BusinessMessagesDeleted;
use EasyTel\Types\SentWebAppMessage;
use EasyTel\Types\PreparedInlineMessage;
use EasyTel\Types\PreparedKeyboardButton;
use EasyTel\Types\ResponseParameters;
use EasyTel\Types\InputMedia;
use EasyTel\Types\InputMediaPhoto;
use EasyTel\Types\InputMediaVideo;
use EasyTel\Types\InputMediaAnimation;
use EasyTel\Types\InputMediaAudio;
use EasyTel\Types\InputMediaDocument;
use EasyTel\Types\InputFile;
use EasyTel\Types\InputPaidMedia;
use EasyTel\Types\InputPaidMediaPhoto;
use EasyTel\Types\InputPaidMediaVideo;
use EasyTel\Types\InputProfilePhoto;
use EasyTel\Types\InputProfilePhotoStatic;
use EasyTel\Types\InputProfilePhotoAnimated;
use EasyTel\Types\InputStoryContent;
use EasyTel\Types\InputStoryContentPhoto;
use EasyTel\Types\InputStoryContentVideo;
use EasyTel\Types\Sticker;
use EasyTel\Types\StickerSet;
use EasyTel\Types\MaskPosition;
use EasyTel\Types\InputSticker;
use EasyTel\Types\InlineQuery;
use EasyTel\Types\InlineQueryResultsButton;
use EasyTel\Types\InlineQueryResult;
use EasyTel\Types\InlineQueryResultArticle;
use EasyTel\Types\InlineQueryResultPhoto;
use EasyTel\Types\InlineQueryResultGif;
use EasyTel\Types\InlineQueryResultMpeg4Gif;
use EasyTel\Types\InlineQueryResultVideo;
use EasyTel\Types\InlineQueryResultAudio;
use EasyTel\Types\InlineQueryResultVoice;
use EasyTel\Types\InlineQueryResultDocument;
use EasyTel\Types\InlineQueryResultLocation;
use EasyTel\Types\InlineQueryResultVenue;
use EasyTel\Types\InlineQueryResultContact;
use EasyTel\Types\InlineQueryResultGame;
use EasyTel\Types\InlineQueryResultCachedPhoto;
use EasyTel\Types\InlineQueryResultCachedGif;
use EasyTel\Types\InlineQueryResultCachedMpeg4Gif;
use EasyTel\Types\InlineQueryResultCachedSticker;
use EasyTel\Types\InlineQueryResultCachedDocument;
use EasyTel\Types\InlineQueryResultCachedVideo;
use EasyTel\Types\InlineQueryResultCachedVoice;
use EasyTel\Types\InlineQueryResultCachedAudio;
use EasyTel\Types\InputMessageContent;
use EasyTel\Types\InputTextMessageContent;
use EasyTel\Types\InputLocationMessageContent;
use EasyTel\Types\InputVenueMessageContent;
use EasyTel\Types\InputContactMessageContent;
use EasyTel\Types\InputInvoiceMessageContent;
use EasyTel\Types\ChosenInlineResult;
use EasyTel\Types\LabeledPrice;
use EasyTel\Types\Invoice;
use EasyTel\Types\ShippingAddress;
use EasyTel\Types\OrderInfo;
use EasyTel\Types\ShippingOption;
use EasyTel\Types\SuccessfulPayment;
use EasyTel\Types\RefundedPayment;
use EasyTel\Types\ShippingQuery;
use EasyTel\Types\PreCheckoutQuery;
use EasyTel\Types\PaidMediaPurchased;
use EasyTel\Types\RevenueWithdrawalState;
use EasyTel\Types\RevenueWithdrawalStatePending;
use EasyTel\Types\RevenueWithdrawalStateSucceeded;
use EasyTel\Types\RevenueWithdrawalStateFailed;
use EasyTel\Types\AffiliateInfo;
use EasyTel\Types\TransactionPartner;
use EasyTel\Types\TransactionPartnerUser;
use EasyTel\Types\TransactionPartnerChat;
use EasyTel\Types\TransactionPartnerAffiliateProgram;
use EasyTel\Types\TransactionPartnerFragment;
use EasyTel\Types\TransactionPartnerTelegramAds;
use EasyTel\Types\TransactionPartnerTelegramApi;
use EasyTel\Types\TransactionPartnerOther;
use EasyTel\Types\StarTransaction;
use EasyTel\Types\StarTransactions;
use EasyTel\Types\PassportData;
use EasyTel\Types\PassportFile;
use EasyTel\Types\EncryptedPassportElement;
use EasyTel\Types\EncryptedCredentials;
use EasyTel\Types\PassportElementError;
use EasyTel\Types\PassportElementErrorDataField;
use EasyTel\Types\PassportElementErrorFrontSide;
use EasyTel\Types\PassportElementErrorReverseSide;
use EasyTel\Types\PassportElementErrorSelfie;
use EasyTel\Types\PassportElementErrorFile;
use EasyTel\Types\PassportElementErrorFiles;
use EasyTel\Types\PassportElementErrorTranslationFile;
use EasyTel\Types\PassportElementErrorTranslationFiles;
use EasyTel\Types\PassportElementErrorUnspecified;
use EasyTel\Types\Game;
use EasyTel\Types\CallbackGame;
use EasyTel\Types\GameHighScore;

/**
 * @method Update Update() This <a href="https://core.telegram.org/bots/api#available-types">object</a> represents an incoming update.<br>At most <strong>one</strong> of the optional parameters can be present in any given update.
 * @method WebhookInfo WebhookInfo() Describes the current status of a webhook.
 * @method User User() This object represents a Telegram user or bot.
 * @method Chat Chat() This object represents a chat.
 * @method ChatFullInfo ChatFullInfo() This object contains full information about a chat.
 * @method Message Message() This object represents a message.
 * @method MessageId MessageId() This object represents a unique message identifier.
 * @method InaccessibleMessage InaccessibleMessage() This object describes a message that was deleted or is otherwise inaccessible to the bot.
 * @method MaybeInaccessibleMessage MaybeInaccessibleMessage() This object describes a message that can be inaccessible to the bot. It can be one of
 * @method MessageEntity MessageEntity() This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 * @method TextQuote TextQuote() This object contains information about the quoted part of a message that is replied to by the given message.
 * @method ExternalReplyInfo ExternalReplyInfo() This object contains information about a message that is being replied to, which may come from another chat or forum topic.
 * @method ReplyParameters ReplyParameters() Describes reply parameters for the message that is being sent.
 * @method MessageOrigin MessageOrigin() This object describes the origin of a message. It can be one of
 * @method MessageOriginUser MessageOriginUser() The message was originally sent by a known user.
 * @method MessageOriginHiddenUser MessageOriginHiddenUser() The message was originally sent by an unknown user.
 * @method MessageOriginChat MessageOriginChat() The message was originally sent on behalf of a chat to a group chat.
 * @method MessageOriginChannel MessageOriginChannel() The message was originally sent to a channel chat.
 * @method PhotoSize PhotoSize() This object represents one size of a photo or a <a href="https://core.telegram.org/bots/api#document">file</a> / <a href="https://core.telegram.org/bots/api#sticker">sticker</a> thumbnail.
 * @method Animation Animation() This object represents an animation file (GIF or H.264/MPEG-4 AVC video without sound).
 * @method Audio Audio() This object represents an audio file to be treated as music by the Telegram clients.
 * @method Document Document() This object represents a general file (as opposed to <a href="https://core.telegram.org/bots/api#photosize">photos</a>, <a href="https://core.telegram.org/bots/api#voice">voice messages</a> and <a href="https://core.telegram.org/bots/api#audio">audio files</a>).
 * @method Story Story() This object represents a story.
 * @method VideoQuality VideoQuality() This object represents a video file of a specific quality.
 * @method Video Video() This object represents a video file.
 * @method VideoNote VideoNote() This object represents a <a href="https://telegram.org/blog/video-messages-and-telescope">video message</a> (available in Telegram apps as of <a href="https://telegram.org/blog/video-messages-and-telescope">v.4.0</a>).
 * @method Voice Voice() This object represents a voice note.
 * @method PaidMediaInfo PaidMediaInfo() Describes the paid media added to a message.
 * @method PaidMedia PaidMedia() This object describes paid media. Currently, it can be one of
 * @method PaidMediaPreview PaidMediaPreview() The paid media isn&#39;t available before the payment.
 * @method PaidMediaPhoto PaidMediaPhoto() The paid media is a photo.
 * @method PaidMediaVideo PaidMediaVideo() The paid media is a video.
 * @method Contact Contact() This object represents a phone contact.
 * @method Dice Dice() This object represents an animated emoji that displays a random value.
 * @method PollOption PollOption() This object contains information about one answer option in a poll.
 * @method InputPollOption InputPollOption() This object contains information about one answer option in a poll to be sent.
 * @method PollAnswer PollAnswer() This object represents an answer of a user in a non-anonymous poll.
 * @method Poll Poll() This object contains information about a poll.
 * @method ChecklistTask ChecklistTask() Describes a task in a checklist.
 * @method Checklist Checklist() Describes a checklist.
 * @method InputChecklistTask InputChecklistTask() Describes a task to add to a checklist.
 * @method InputChecklist InputChecklist() Describes a checklist to create.
 * @method ChecklistTasksDone ChecklistTasksDone() Describes a service message about checklist tasks marked as done or not done.
 * @method ChecklistTasksAdded ChecklistTasksAdded() Describes a service message about tasks added to a checklist.
 * @method Location Location() This object represents a point on the map.
 * @method Venue Venue() This object represents a venue.
 * @method WebAppData WebAppData() Describes data sent from a <a href="/bots/webapps">Web App</a> to the bot.
 * @method ProximityAlertTriggered ProximityAlertTriggered() This object represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
 * @method MessageAutoDeleteTimerChanged MessageAutoDeleteTimerChanged() This object represents a service message about a change in auto-delete timer settings.
 * @method ManagedBotCreated ManagedBotCreated() This object contains information about the bot that was created to be managed by the current bot.
 * @method ManagedBotUpdated ManagedBotUpdated() This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
 * @method PollOptionAdded PollOptionAdded() Describes a service message about an option added to a poll.
 * @method PollOptionDeleted PollOptionDeleted() Describes a service message about an option deleted from a poll.
 * @method ChatBoostAdded ChatBoostAdded() This object represents a service message about a user boosting a chat.
 * @method BackgroundFill BackgroundFill() This object describes the way a background is filled based on the selected colors. Currently, it can be one of
 * @method BackgroundFillSolid BackgroundFillSolid() The background is filled using the selected color.
 * @method BackgroundFillGradient BackgroundFillGradient() The background is a gradient fill.
 * @method BackgroundFillFreeformGradient BackgroundFillFreeformGradient() The background is a freeform gradient that rotates after every message in the chat.
 * @method BackgroundType BackgroundType() This object describes the type of a background. Currently, it can be one of
 * @method BackgroundTypeFill BackgroundTypeFill() The background is automatically filled based on the selected colors.
 * @method BackgroundTypeWallpaper BackgroundTypeWallpaper() The background is a wallpaper in the JPEG format.
 * @method BackgroundTypePattern BackgroundTypePattern() The background is a .PNG or .TGV (gzipped subset of SVG with MIME type “application/x-tgwallpattern”) pattern to be combined with the background fill chosen by the user.
 * @method BackgroundTypeChatTheme BackgroundTypeChatTheme() The background is taken directly from a built-in chat theme.
 * @method ChatBackground ChatBackground() This object represents a chat background.
 * @method ForumTopicCreated ForumTopicCreated() This object represents a service message about a new forum topic created in the chat.
 * @method ForumTopicClosed ForumTopicClosed() This object represents a service message about a forum topic closed in the chat. Currently holds no information.
 * @method ForumTopicEdited ForumTopicEdited() This object represents a service message about an edited forum topic.
 * @method ForumTopicReopened ForumTopicReopened() This object represents a service message about a forum topic reopened in the chat. Currently holds no information.
 * @method GeneralForumTopicHidden GeneralForumTopicHidden() This object represents a service message about General forum topic hidden in the chat. Currently holds no information.
 * @method GeneralForumTopicUnhidden GeneralForumTopicUnhidden() This object represents a service message about General forum topic unhidden in the chat. Currently holds no information.
 * @method SharedUser SharedUser() This object contains information about a user that was shared with the bot using a <a href="https://core.telegram.org/bots/api#keyboardbuttonrequestusers">KeyboardButtonRequestUsers</a> button.
 * @method UsersShared UsersShared() This object contains information about the users whose identifiers were shared with the bot using a <a href="https://core.telegram.org/bots/api#keyboardbuttonrequestusers">KeyboardButtonRequestUsers</a> button.
 * @method ChatShared ChatShared() This object contains information about a chat that was shared with the bot using a <a href="https://core.telegram.org/bots/api#keyboardbuttonrequestchat">KeyboardButtonRequestChat</a> button.
 * @method WriteAccessAllowed WriteAccessAllowed() This object represents a service message about a user allowing a bot to write messages after adding it to the attachment menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method <a href="/bots/webapps#initializing-mini-apps">requestWriteAccess</a>.
 * @method VideoChatScheduled VideoChatScheduled() This object represents a service message about a video chat scheduled in the chat.
 * @method VideoChatStarted VideoChatStarted() This object represents a service message about a video chat started in the chat. Currently holds no information.
 * @method VideoChatEnded VideoChatEnded() This object represents a service message about a video chat ended in the chat.
 * @method VideoChatParticipantsInvited VideoChatParticipantsInvited() This object represents a service message about new members invited to a video chat.
 * @method PaidMessagePriceChanged PaidMessagePriceChanged() Describes a service message about a change in the price of paid messages within a chat.
 * @method DirectMessagePriceChanged DirectMessagePriceChanged() Describes a service message about a change in the price of direct messages sent to a channel chat.
 * @method SuggestedPostApproved SuggestedPostApproved() Describes a service message about the approval of a suggested post.
 * @method SuggestedPostApprovalFailed SuggestedPostApprovalFailed() Describes a service message about the failed approval of a suggested post. Currently, only caused by insufficient user funds at the time of approval.
 * @method SuggestedPostDeclined SuggestedPostDeclined() Describes a service message about the rejection of a suggested post.
 * @method SuggestedPostPaid SuggestedPostPaid() Describes a service message about a successful payment for a suggested post.
 * @method SuggestedPostRefunded SuggestedPostRefunded() Describes a service message about a payment refund for a suggested post.
 * @method GiveawayCreated GiveawayCreated() This object represents a service message about the creation of a scheduled giveaway.
 * @method Giveaway Giveaway() This object represents a message about a scheduled giveaway.
 * @method GiveawayWinners GiveawayWinners() This object represents a message about the completion of a giveaway with public winners.
 * @method GiveawayCompleted GiveawayCompleted() This object represents a service message about the completion of a giveaway without public winners.
 * @method LinkPreviewOptions LinkPreviewOptions() Describes the options used for link preview generation.
 * @method SuggestedPostPrice SuggestedPostPrice() Describes the price of a suggested post.
 * @method SuggestedPostInfo SuggestedPostInfo() Contains information about a suggested post.
 * @method SuggestedPostParameters SuggestedPostParameters() Contains parameters of a post that is being suggested by the bot.
 * @method DirectMessagesTopic DirectMessagesTopic() Describes a topic of a direct messages chat.
 * @method UserProfilePhotos UserProfilePhotos() This object represent a user&#39;s profile pictures.
 * @method UserProfileAudios UserProfileAudios() This object represents the audios displayed on a user&#39;s profile.
 * @method File File() This object represents a file ready to be downloaded. The file can be downloaded via the link <code>https://api.telegram.org/file/bot&lt;token&gt;/&lt;file_path&gt;</code>. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling <a href="https://core.telegram.org/bots/api#getfile">getFile</a>.
 * @method WebAppInfo WebAppInfo() Describes a <a href="/bots/webapps">Web App</a>.
 * @method ReplyKeyboardMarkup ReplyKeyboardMarkup() This object represents a <a href="/bots/features#keyboards">custom keyboard</a> with reply options (see <a href="/bots/features#keyboards">Introduction to bots</a> for details and examples). Not supported in channels and for messages sent on behalf of a Telegram Business account.
 * @method KeyboardButton KeyboardButton() This object represents one button of the reply keyboard. At most one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button. For simple text buttons, <em>String</em> can be used instead of this object to specify the button text.
 * @method KeyboardButtonRequestUsers KeyboardButtonRequestUsers() This object defines the criteria used to request suitable users. Information about the selected users will be shared with the bot when the corresponding button is pressed. <a href="/bots/features#chat-and-user-selection">More about requesting users »</a>
 * @method KeyboardButtonRequestChat KeyboardButtonRequestChat() This object defines the criteria used to request a suitable chat. Information about the selected chat will be shared with the bot when the corresponding button is pressed. The bot will be granted requested rights in the chat if appropriate. <a href="/bots/features#chat-and-user-selection">More about requesting chats »</a>.
 * @method KeyboardButtonRequestManagedBot KeyboardButtonRequestManagedBot() This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update <em>managed_bot</em> and a <a href="https://core.telegram.org/bots/api#message">Message</a> with the field <em>managed_bot_created</em>.
 * @method KeyboardButtonPollType KeyboardButtonPollType() This object represents type of a poll, which is allowed to be created and sent when the corresponding button is pressed.
 * @method ReplyKeyboardRemove ReplyKeyboardRemove() Upon receiving a message with this object, Telegram clients will remove the current custom keyboard and display the default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot. An exception is made for one-time keyboards that are hidden immediately after the user presses a button (see <a href="https://core.telegram.org/bots/api#replykeyboardmarkup">ReplyKeyboardMarkup</a>). Not supported in channels and for messages sent on behalf of a Telegram Business account.
 * @method InlineKeyboardMarkup InlineKeyboardMarkup() This object represents an <a href="/bots/features#inline-keyboards">inline keyboard</a> that appears right next to the message it belongs to.
 * @method InlineKeyboardButton InlineKeyboardButton() This object represents one button of an inline keyboard. Exactly one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button.
 * @method LoginUrl LoginUrl() This object represents a parameter of the inline keyboard button used to automatically authorize a user. Serves as a great replacement for the <a href="/widgets/login">Telegram Login Widget</a> when the user is coming from Telegram. All the user needs to do is tap/click a button and confirm that they want to log in:
 * @method SwitchInlineQueryChosenChat SwitchInlineQueryChosenChat() This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
 * @method CopyTextButton CopyTextButton() This object represents an inline keyboard button that copies specified text to the clipboard.
 * @method CallbackQuery CallbackQuery() This object represents an incoming callback query from a callback button in an <a href="/bots/features#inline-keyboards">inline keyboard</a>. If the button that originated the query was attached to a message sent by the bot, the field <em>message</em> will be present. If the button was attached to a message sent via the bot (in <a href="https://core.telegram.org/bots/api#inline-mode">inline mode</a>), the field <em>inline_message_id</em> will be present. Exactly one of the fields <em>data</em> or <em>game_short_name</em> will be present.
 * @method ForceReply ForceReply() Upon receiving a message with this object, Telegram clients will display a reply interface to the user (act as if the user has selected the bot&#39;s message and tapped &#39;Reply&#39;). This can be extremely useful if you want to create user-friendly step-by-step interfaces without having to sacrifice <a href="/bots/features#privacy-mode">privacy mode</a>. Not supported in channels and for messages sent on behalf of a Telegram Business account.
 * @method ChatPhoto ChatPhoto() This object represents a chat photo.
 * @method ChatInviteLink ChatInviteLink() Represents an invite link for a chat.
 * @method ChatAdministratorRights ChatAdministratorRights() Represents the rights of an administrator in a chat.
 * @method ChatMemberUpdated ChatMemberUpdated() This object represents changes in the status of a chat member.
 * @method ChatMember ChatMember() This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:
 * @method ChatMemberOwner ChatMemberOwner() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that owns the chat and has all administrator privileges.
 * @method ChatMemberAdministrator ChatMemberAdministrator() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has some additional privileges.
 * @method ChatMemberMember ChatMemberMember() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has no additional privileges or restrictions.
 * @method ChatMemberRestricted ChatMemberRestricted() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that is under certain restrictions in the chat. Supergroups only.
 * @method ChatMemberLeft ChatMemberLeft() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that isn&#39;t currently a member of the chat, but may join it themselves.
 * @method ChatMemberBanned ChatMemberBanned() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that was banned in the chat and can&#39;t return to the chat or view chat messages.
 * @method ChatJoinRequest ChatJoinRequest() Represents a join request sent to a chat.
 * @method ChatPermissions ChatPermissions() Describes actions that a non-administrator user is allowed to take in a chat.
 * @method Birthdate Birthdate() Describes the birthdate of a user.
 * @method BusinessIntro BusinessIntro() Contains information about the start page settings of a Telegram Business account.
 * @method BusinessLocation BusinessLocation() Contains information about the location of a Telegram Business account.
 * @method BusinessOpeningHoursInterval BusinessOpeningHoursInterval() Describes an interval of time during which a business is open.
 * @method BusinessOpeningHours BusinessOpeningHours() Describes the opening hours of a business.
 * @method UserRating UserRating() This object describes the rating of a user based on their Telegram Star spendings.
 * @method StoryAreaPosition StoryAreaPosition() Describes the position of a clickable area within a story.
 * @method LocationAddress LocationAddress() Describes the physical address of a location.
 * @method StoryAreaType StoryAreaType() Describes the type of a clickable area on a story. Currently, it can be one of
 * @method StoryAreaTypeLocation StoryAreaTypeLocation() Describes a story area pointing to a location. Currently, a story can have up to 10 location areas.
 * @method StoryAreaTypeSuggestedReaction StoryAreaTypeSuggestedReaction() Describes a story area pointing to a suggested reaction. Currently, a story can have up to 5 suggested reaction areas.
 * @method StoryAreaTypeLink StoryAreaTypeLink() Describes a story area pointing to an HTTP or tg:// link. Currently, a story can have up to 3 link areas.
 * @method StoryAreaTypeWeather StoryAreaTypeWeather() Describes a story area containing weather information. Currently, a story can have up to 3 weather areas.
 * @method StoryAreaTypeUniqueGift StoryAreaTypeUniqueGift() Describes a story area pointing to a unique gift. Currently, a story can have at most 1 unique gift area.
 * @method StoryArea StoryArea() Describes a clickable area on a story media.
 * @method ChatLocation ChatLocation() Represents a location to which a chat is connected.
 * @method ReactionType ReactionType() This object describes the type of a reaction. Currently, it can be one of
 * @method ReactionTypeEmoji ReactionTypeEmoji() The reaction is based on an emoji.
 * @method ReactionTypeCustomEmoji ReactionTypeCustomEmoji() The reaction is based on a custom emoji.
 * @method ReactionTypePaid ReactionTypePaid() The reaction is paid.
 * @method ReactionCount ReactionCount() Represents a reaction added to a message along with the number of times it was added.
 * @method MessageReactionUpdated MessageReactionUpdated() This object represents a change of a reaction on a message performed by a user.
 * @method MessageReactionCountUpdated MessageReactionCountUpdated() This object represents reaction changes on a message with anonymous reactions.
 * @method ForumTopic ForumTopic() This object represents a forum topic.
 * @method GiftBackground GiftBackground() This object describes the background of a gift.
 * @method Gift Gift() This object represents a gift that can be sent by the bot.
 * @method Gifts Gifts() This object represent a list of gifts.
 * @method UniqueGiftModel UniqueGiftModel() This object describes the model of a unique gift.
 * @method UniqueGiftSymbol UniqueGiftSymbol() This object describes the symbol shown on the pattern of a unique gift.
 * @method UniqueGiftBackdropColors UniqueGiftBackdropColors() This object describes the colors of the backdrop of a unique gift.
 * @method UniqueGiftBackdrop UniqueGiftBackdrop() This object describes the backdrop of a unique gift.
 * @method UniqueGiftColors UniqueGiftColors() This object contains information about the color scheme for a user&#39;s name, message replies and link previews based on a unique gift.
 * @method UniqueGift UniqueGift() This object describes a unique gift that was upgraded from a regular gift.
 * @method GiftInfo GiftInfo() Describes a service message about a regular gift that was sent or received.
 * @method UniqueGiftInfo UniqueGiftInfo() Describes a service message about a unique gift that was sent or received.
 * @method OwnedGift OwnedGift() This object describes a gift received and owned by a user or a chat. Currently, it can be one of
 * @method OwnedGiftRegular OwnedGiftRegular() Describes a regular gift owned by a user or a chat.
 * @method OwnedGiftUnique OwnedGiftUnique() Describes a unique gift received and owned by a user or a chat.
 * @method OwnedGifts OwnedGifts() Contains the list of gifts received and owned by a user or a chat.
 * @method AcceptedGiftTypes AcceptedGiftTypes() This object describes the types of gifts that can be gifted to a user or a chat.
 * @method StarAmount StarAmount() Describes an amount of Telegram Stars.
 * @method BotCommand BotCommand() This object represents a bot command.
 * @method BotCommandScope BotCommandScope() This object represents the scope to which bot commands are applied. Currently, the following 7 scopes are supported:
 * @method BotCommandScopeDefault BotCommandScopeDefault() Represents the default <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands. Default commands are used if no commands with a <a href="https://core.telegram.org/bots/api#determining-list-of-commands">narrower scope</a> are specified for the user.
 * @method BotCommandScopeAllPrivateChats BotCommandScopeAllPrivateChats() Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering all private chats.
 * @method BotCommandScopeAllGroupChats BotCommandScopeAllGroupChats() Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering all group and supergroup chats.
 * @method BotCommandScopeAllChatAdministrators BotCommandScopeAllChatAdministrators() Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering all group and supergroup chat administrators.
 * @method BotCommandScopeChat BotCommandScopeChat() Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering a specific chat.
 * @method BotCommandScopeChatAdministrators BotCommandScopeChatAdministrators() Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering all administrators of a specific group or supergroup chat.
 * @method BotCommandScopeChatMember BotCommandScopeChatMember() Represents the <a href="https://core.telegram.org/bots/api#botcommandscope">scope</a> of bot commands, covering a specific member of a group or supergroup chat.
 * @method BotName BotName() This object represents the bot&#39;s name.
 * @method BotDescription BotDescription() This object represents the bot&#39;s description.
 * @method BotShortDescription BotShortDescription() This object represents the bot&#39;s short description.
 * @method MenuButton MenuButton() This object describes the bot&#39;s menu button in a private chat. It should be one of
 * @method MenuButtonCommands MenuButtonCommands() Represents a menu button, which opens the bot&#39;s list of commands.
 * @method MenuButtonWebApp MenuButtonWebApp() Represents a menu button, which launches a <a href="/bots/webapps">Web App</a>.
 * @method MenuButtonDefault MenuButtonDefault() Describes that no specific value for the menu button was set.
 * @method ChatBoostSource ChatBoostSource() This object describes the source of a chat boost. It can be one of
 * @method ChatBoostSourcePremium ChatBoostSourcePremium() The boost was obtained by subscribing to Telegram Premium or by gifting a Telegram Premium subscription to another user.
 * @method ChatBoostSourceGiftCode ChatBoostSourceGiftCode() The boost was obtained by the creation of Telegram Premium gift codes to boost a chat. Each such code boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription.
 * @method ChatBoostSourceGiveaway ChatBoostSourceGiveaway() The boost was obtained by the creation of a Telegram Premium or a Telegram Star giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription for Telegram Premium giveaways and <em>prize_star_count</em> / 500 times for one year for Telegram Star giveaways.
 * @method ChatBoost ChatBoost() This object contains information about a chat boost.
 * @method ChatBoostUpdated ChatBoostUpdated() This object represents a boost added to a chat or changed.
 * @method ChatBoostRemoved ChatBoostRemoved() This object represents a boost removed from a chat.
 * @method ChatOwnerLeft ChatOwnerLeft() Describes a service message about the chat owner leaving the chat.
 * @method ChatOwnerChanged ChatOwnerChanged() Describes a service message about an ownership change in the chat.
 * @method UserChatBoosts UserChatBoosts() This object represents a list of boosts added to a chat by a user.
 * @method BusinessBotRights BusinessBotRights() Represents the rights of a business bot.
 * @method BusinessConnection BusinessConnection() Describes the connection of the bot with a business account.
 * @method BusinessMessagesDeleted BusinessMessagesDeleted() This object is received when messages are deleted from a connected business account.
 * @method SentWebAppMessage SentWebAppMessage() Describes an inline message sent by a <a href="/bots/webapps">Web App</a> on behalf of a user.
 * @method PreparedInlineMessage PreparedInlineMessage() Describes an inline message to be sent by a user of a Mini App.
 * @method PreparedKeyboardButton PreparedKeyboardButton() Describes a keyboard button to be used by a user of a Mini App.
 * @method ResponseParameters ResponseParameters() Describes why a request was unsuccessful.
 * @method InputMedia InputMedia() This object represents the content of a media message to be sent. It should be one of
 * @method InputMediaPhoto InputMediaPhoto() Represents a photo to be sent.
 * @method InputMediaVideo InputMediaVideo() Represents a video to be sent.
 * @method InputMediaAnimation InputMediaAnimation() Represents an animation file (GIF or H.264/MPEG-4 AVC video without sound) to be sent.
 * @method InputMediaAudio InputMediaAudio() Represents an audio file to be treated as music to be sent.
 * @method InputMediaDocument InputMediaDocument() Represents a general file to be sent.
 * @method InputFile InputFile() This object represents the contents of a file to be uploaded. Must be posted using multipart/form-data in the usual way that files are uploaded via the browser.
 * @method InputPaidMedia InputPaidMedia() This object describes the paid media to be sent. Currently, it can be one of
 * @method InputPaidMediaPhoto InputPaidMediaPhoto() The paid media to send is a photo.
 * @method InputPaidMediaVideo InputPaidMediaVideo() The paid media to send is a video.
 * @method InputProfilePhoto InputProfilePhoto() This object describes a profile photo to set. Currently, it can be one of
 * @method InputProfilePhotoStatic InputProfilePhotoStatic() A static profile photo in the .JPG format.
 * @method InputProfilePhotoAnimated InputProfilePhotoAnimated() An animated profile photo in the MPEG4 format.
 * @method InputStoryContent InputStoryContent() This object describes the content of a story to post. Currently, it can be one of
 * @method InputStoryContentPhoto InputStoryContentPhoto() Describes a photo to post as a story.
 * @method InputStoryContentVideo InputStoryContentVideo() Describes a video to post as a story.
 * @method Sticker Sticker() This object represents a sticker.
 * @method StickerSet StickerSet() This object represents a sticker set.
 * @method MaskPosition MaskPosition() This object describes the position on faces where a mask should be placed by default.
 * @method InputSticker InputSticker() This object describes a sticker to be added to a sticker set.
 * @method InlineQuery InlineQuery() This object represents an incoming inline query. When the user sends an empty query, your bot could return some default or trending results.
 * @method InlineQueryResultsButton InlineQueryResultsButton() This object represents a button to be shown above inline query results. You <strong>must</strong> use exactly one of the optional fields.
 * @method InlineQueryResult InlineQueryResult() This object represents one result of an inline query. Telegram clients currently support results of the following 20 types:
 * @method InlineQueryResultArticle InlineQueryResultArticle() Represents a link to an article or web page.
 * @method InlineQueryResultPhoto InlineQueryResultPhoto() Represents a link to a photo. By default, this photo will be sent by the user with optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the photo.
 * @method InlineQueryResultGif InlineQueryResultGif() Represents a link to an animated GIF file. By default, this animated GIF file will be sent by the user with optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the animation.
 * @method InlineQueryResultMpeg4Gif InlineQueryResultMpeg4Gif() Represents a link to a video animation (H.264/MPEG-4 AVC video without sound). By default, this animated MPEG-4 file will be sent by the user with optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the animation.
 * @method InlineQueryResultVideo InlineQueryResultVideo() Represents a link to a page containing an embedded video player or a video file. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the video.
 * @method InlineQueryResultAudio InlineQueryResultAudio() Represents a link to an MP3 audio file. By default, this audio file will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the audio.
 * @method InlineQueryResultVoice InlineQueryResultVoice() Represents a link to a voice recording in an .OGG container encoded with OPUS. By default, this voice recording will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the the voice message.
 * @method InlineQueryResultDocument InlineQueryResultDocument() Represents a link to a file. By default, this file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the file. Currently, only <strong>.PDF</strong> and <strong>.ZIP</strong> files can be sent using this method.
 * @method InlineQueryResultLocation InlineQueryResultLocation() Represents a location on a map. By default, the location will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the location.
 * @method InlineQueryResultVenue InlineQueryResultVenue() Represents a venue. By default, the venue will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the venue.
 * @method InlineQueryResultContact InlineQueryResultContact() Represents a contact with a phone number. By default, this contact will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the contact.
 * @method InlineQueryResultGame InlineQueryResultGame() Represents a <a href="https://core.telegram.org/bots/api#games">Game</a>.
 * @method InlineQueryResultCachedPhoto InlineQueryResultCachedPhoto() Represents a link to a photo stored on the Telegram servers. By default, this photo will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the photo.
 * @method InlineQueryResultCachedGif InlineQueryResultCachedGif() Represents a link to an animated GIF file stored on the Telegram servers. By default, this animated GIF file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with specified content instead of the animation.
 * @method InlineQueryResultCachedMpeg4Gif InlineQueryResultCachedMpeg4Gif() Represents a link to a video animation (H.264/MPEG-4 AVC video without sound) stored on the Telegram servers. By default, this animated MPEG-4 file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the animation.
 * @method InlineQueryResultCachedSticker InlineQueryResultCachedSticker() Represents a link to a sticker stored on the Telegram servers. By default, this sticker will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the sticker.
 * @method InlineQueryResultCachedDocument InlineQueryResultCachedDocument() Represents a link to a file stored on the Telegram servers. By default, this file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the file.
 * @method InlineQueryResultCachedVideo InlineQueryResultCachedVideo() Represents a link to a video file stored on the Telegram servers. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the video.
 * @method InlineQueryResultCachedVoice InlineQueryResultCachedVoice() Represents a link to a voice message stored on the Telegram servers. By default, this voice message will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the voice message.
 * @method InlineQueryResultCachedAudio InlineQueryResultCachedAudio() Represents a link to an MP3 audio file stored on the Telegram servers. By default, this audio file will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the audio.
 * @method InputMessageContent InputMessageContent() This object represents the content of a message to be sent as a result of an inline query. Telegram clients currently support the following 5 types:
 * @method InputTextMessageContent InputTextMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a text message to be sent as the result of an inline query.
 * @method InputLocationMessageContent InputLocationMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a location message to be sent as the result of an inline query.
 * @method InputVenueMessageContent InputVenueMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a venue message to be sent as the result of an inline query.
 * @method InputContactMessageContent InputContactMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a contact message to be sent as the result of an inline query.
 * @method InputInvoiceMessageContent InputInvoiceMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of an invoice message to be sent as the result of an inline query.
 * @method ChosenInlineResult ChosenInlineResult() Represents a <a href="https://core.telegram.org/bots/api#inlinequeryresult">result</a> of an inline query that was chosen by the user and sent to their chat partner.
 * @method LabeledPrice LabeledPrice() This object represents a portion of the price for goods or services.
 * @method Invoice Invoice() This object contains basic information about an invoice.
 * @method ShippingAddress ShippingAddress() This object represents a shipping address.
 * @method OrderInfo OrderInfo() This object represents information about an order.
 * @method ShippingOption ShippingOption() This object represents one shipping option.
 * @method SuccessfulPayment SuccessfulPayment() This object contains basic information about a successful payment. Note that if the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram&#39;s control.
 * @method RefundedPayment RefundedPayment() This object contains basic information about a refunded payment.
 * @method ShippingQuery ShippingQuery() This object contains information about an incoming shipping query.
 * @method PreCheckoutQuery PreCheckoutQuery() This object contains information about an incoming pre-checkout query.
 * @method PaidMediaPurchased PaidMediaPurchased() This object contains information about a paid media purchase.
 * @method RevenueWithdrawalState RevenueWithdrawalState() This object describes the state of a revenue withdrawal operation. Currently, it can be one of
 * @method RevenueWithdrawalStatePending RevenueWithdrawalStatePending() The withdrawal is in progress.
 * @method RevenueWithdrawalStateSucceeded RevenueWithdrawalStateSucceeded() The withdrawal succeeded.
 * @method RevenueWithdrawalStateFailed RevenueWithdrawalStateFailed() The withdrawal failed and the transaction was refunded.
 * @method AffiliateInfo AffiliateInfo() Contains information about the affiliate that received a commission via this transaction.
 * @method TransactionPartner TransactionPartner() This object describes the source of a transaction, or its recipient for outgoing transactions. Currently, it can be one of
 * @method TransactionPartnerUser TransactionPartnerUser() Describes a transaction with a user.
 * @method TransactionPartnerChat TransactionPartnerChat() Describes a transaction with a chat.
 * @method TransactionPartnerAffiliateProgram TransactionPartnerAffiliateProgram() Describes the affiliate program that issued the affiliate commission received via this transaction.
 * @method TransactionPartnerFragment TransactionPartnerFragment() Describes a withdrawal transaction with Fragment.
 * @method TransactionPartnerTelegramAds TransactionPartnerTelegramAds() Describes a withdrawal transaction to the Telegram Ads platform.
 * @method TransactionPartnerTelegramApi TransactionPartnerTelegramApi() Describes a transaction with payment for <a href="https://core.telegram.org/bots/api#paid-broadcasts">paid broadcasting</a>.
 * @method TransactionPartnerOther TransactionPartnerOther() Describes a transaction with an unknown source or recipient.
 * @method StarTransaction StarTransaction() Describes a Telegram Star transaction. Note that if the buyer initiates a chargeback with the payment provider from whom they acquired Stars (e.g., Apple, Google) following this transaction, the refunded Stars will be deducted from the bot&#39;s balance. This is outside of Telegram&#39;s control.
 * @method StarTransactions StarTransactions() Contains a list of Telegram Star transactions.
 * @method PassportData PassportData() Describes Telegram Passport data shared with the bot by the user.
 * @method PassportFile PassportFile() This object represents a file uploaded to Telegram Passport. Currently all Telegram Passport files are in JPEG format when decrypted and don&#39;t exceed 10MB.
 * @method EncryptedPassportElement EncryptedPassportElement() Describes documents or other Telegram Passport elements shared with the bot by the user.
 * @method EncryptedCredentials EncryptedCredentials() Describes data required for decrypting and authenticating <a href="https://core.telegram.org/bots/api#encryptedpassportelement">EncryptedPassportElement</a>. See the <a href="/passport#receiving-information">Telegram Passport Documentation</a> for a complete description of the data decryption and authentication processes.
 * @method PassportElementError PassportElementError() This object represents an error in the Telegram Passport element which was submitted that should be resolved by the user. It should be one of:
 * @method PassportElementErrorDataField PassportElementErrorDataField() Represents an issue in one of the data fields that was provided by the user. The error is considered resolved when the field&#39;s value changes.
 * @method PassportElementErrorFrontSide PassportElementErrorFrontSide() Represents an issue with the front side of a document. The error is considered resolved when the file with the front side of the document changes.
 * @method PassportElementErrorReverseSide PassportElementErrorReverseSide() Represents an issue with the reverse side of a document. The error is considered resolved when the file with reverse side of the document changes.
 * @method PassportElementErrorSelfie PassportElementErrorSelfie() Represents an issue with the selfie with a document. The error is considered resolved when the file with the selfie changes.
 * @method PassportElementErrorFile PassportElementErrorFile() Represents an issue with a document scan. The error is considered resolved when the file with the document scan changes.
 * @method PassportElementErrorFiles PassportElementErrorFiles() Represents an issue with a list of scans. The error is considered resolved when the list of files containing the scans changes.
 * @method PassportElementErrorTranslationFile PassportElementErrorTranslationFile() Represents an issue with one of the files that constitute the translation of a document. The error is considered resolved when the file changes.
 * @method PassportElementErrorTranslationFiles PassportElementErrorTranslationFiles() Represents an issue with the translated version of a document. The error is considered resolved when a file with the document translation change.
 * @method PassportElementErrorUnspecified PassportElementErrorUnspecified() Represents an issue in an unspecified place. The error is considered resolved when new data is added.
 * @method Game Game() This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
 * @method CallbackGame CallbackGame() A placeholder, currently holds no information. Use <a href="https://t.me/botfather">BotFather</a> to set up your game.
 * @method GameHighScore GameHighScore() This object represents one row of the high scores table for a game.
 */
class Types
{
    public int $output;
    public object|string|array $update;

    public function __construct(object|string|array $update)
    {
        $update = json::to_array($update);
        $this->update = (isset($update['ok']) && isset($update['result'])) ? $update['result'] : $update;
    }

    public static function make(object|string|array $response): Types
    {
        return new self($response);
    }

    public function __call(string $name, array $arguments)
    {
        $name = "\EasyTel\Types\\$name";
        return new $name($this->update);
    }
}