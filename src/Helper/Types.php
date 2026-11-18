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
use EasyTel\Types\EphemeralMessageParameters;
use EasyTel\Types\MessageOrigin;
use EasyTel\Types\MessageOriginUser;
use EasyTel\Types\MessageOriginHiddenUser;
use EasyTel\Types\MessageOriginChat;
use EasyTel\Types\MessageOriginChannel;
use EasyTel\Types\PhotoSize;
use EasyTel\Types\Animation;
use EasyTel\Types\Audio;
use EasyTel\Types\Document;
use EasyTel\Types\LivePhoto;
use EasyTel\Types\Story;
use EasyTel\Types\VideoQuality;
use EasyTel\Types\Video;
use EasyTel\Types\VideoNote;
use EasyTel\Types\Voice;
use EasyTel\Types\Widget;
use EasyTel\Types\PaidMediaInfo;
use EasyTel\Types\PaidMedia;
use EasyTel\Types\PaidMediaLivePhoto;
use EasyTel\Types\PaidMediaPhoto;
use EasyTel\Types\PaidMediaPreview;
use EasyTel\Types\PaidMediaVideo;
use EasyTel\Types\Contact;
use EasyTel\Types\Dice;
use EasyTel\Types\Link;
use EasyTel\Types\PollMedia;
use EasyTel\Types\InputPollMedia;
use EasyTel\Types\InputPollOptionMedia;
use EasyTel\Types\PollOption;
use EasyTel\Types\InputPollOption;
use EasyTel\Types\PollAnswer;
use EasyTel\Types\Poll;
use EasyTel\Types\ChecklistTask;
use EasyTel\Types\Checklist;
use EasyTel\Types\InputChecklistTask;
use EasyTel\Types\InputChecklist;
use EasyTel\Types\Location;
use EasyTel\Types\Venue;
use EasyTel\Types\WebAppData;
use EasyTel\Types\ProximityAlertTriggered;
use EasyTel\Types\MessageAutoDeleteTimerChanged;
use EasyTel\Types\ManagedBotCreated;
use EasyTel\Types\ManagedBotUpdated;
use EasyTel\Types\BotSubscriptionUpdated;
use EasyTel\Types\MessageGenerationStopped;
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
use EasyTel\Types\ChecklistTasksDone;
use EasyTel\Types\ChecklistTasksAdded;
use EasyTel\Types\CommunityChatAdded;
use EasyTel\Types\CommunityChatJoined;
use EasyTel\Types\CommunityChatRemoved;
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
use EasyTel\Types\PaidMessagesWaived;
use EasyTel\Types\TonWalletTransfer;
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
use EasyTel\Types\DisabledButton;
use EasyTel\Types\CallbackQuery;
use EasyTel\Types\WidgetQueryType;
use EasyTel\Types\WidgetQueryTypeData;
use EasyTel\Types\WidgetQueryTypeSignData;
use EasyTel\Types\WidgetQueryTypeSentTransaction;
use EasyTel\Types\WidgetQueryWallet;
use EasyTel\Types\WidgetQuery;
use EasyTel\Types\SharedWidgetQuery;
use EasyTel\Types\ForceReply;
use EasyTel\Types\Community;
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
use EasyTel\Types\AreaPosition;
use EasyTel\Types\TonNft;
use EasyTel\Types\TonNftAttribute;
use EasyTel\Types\TonWalletCardSticker;
use EasyTel\Types\TonWalletCard;
use EasyTel\Types\EmojiType;
use EasyTel\Types\EmojiTypeEmoji;
use EasyTel\Types\EmojiTypeCustomEmoji;
use EasyTel\Types\ChatShareTheme;
use EasyTel\Types\ChatShareThemeEmoji;
use EasyTel\Types\ChatShareThemeUniqueGift;
use EasyTel\Types\ChatShareThemeBackdrops;
use EasyTel\Types\ChatShareThemeBackdrop;
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
use EasyTel\Types\BotAccessSettings;
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
use EasyTel\Types\SentGuestMessage;
use EasyTel\Types\PreparedInlineMessage;
use EasyTel\Types\PreparedKeyboardButton;
use EasyTel\Types\ResponseParameters;
use EasyTel\Types\InputMedia;
use EasyTel\Types\InputMediaAnimation;
use EasyTel\Types\InputMediaAudio;
use EasyTel\Types\InputMediaDocument;
use EasyTel\Types\InputMediaLink;
use EasyTel\Types\InputMediaLivePhoto;
use EasyTel\Types\InputMediaLocation;
use EasyTel\Types\InputMediaPhoto;
use EasyTel\Types\InputMediaSticker;
use EasyTel\Types\InputMediaVenue;
use EasyTel\Types\InputMediaVideo;
use EasyTel\Types\InputMediaVoiceNote;
use EasyTel\Types\InputFile;
use EasyTel\Types\InputPaidMedia;
use EasyTel\Types\InputPaidMediaLivePhoto;
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
use EasyTel\Types\RichMessage;
use EasyTel\Types\InputRichMessage;
use EasyTel\Types\InputRichMessageMedia;
use EasyTel\Types\RichMessageButton;
use EasyTel\Types\RichText;
use EasyTel\Types\RichTextBold;
use EasyTel\Types\RichTextItalic;
use EasyTel\Types\RichTextUnderline;
use EasyTel\Types\RichTextStrikethrough;
use EasyTel\Types\RichTextSpoiler;
use EasyTel\Types\RichTextDateTime;
use EasyTel\Types\RichTextTextMention;
use EasyTel\Types\RichTextSubscript;
use EasyTel\Types\RichTextSuperscript;
use EasyTel\Types\RichTextMarked;
use EasyTel\Types\RichTextCode;
use EasyTel\Types\RichTextCustomEmoji;
use EasyTel\Types\RichTextMathematicalExpression;
use EasyTel\Types\RichTextUrl;
use EasyTel\Types\RichTextEmailAddress;
use EasyTel\Types\RichTextPhoneNumber;
use EasyTel\Types\RichTextBankCardNumber;
use EasyTel\Types\RichTextTonAddress;
use EasyTel\Types\RichTextMention;
use EasyTel\Types\RichTextHashtag;
use EasyTel\Types\RichTextCashtag;
use EasyTel\Types\RichTextBotCommand;
use EasyTel\Types\RichTextButton;
use EasyTel\Types\RichTextAnchor;
use EasyTel\Types\RichTextAnchorLink;
use EasyTel\Types\RichTextReference;
use EasyTel\Types\RichTextReferenceLink;
use EasyTel\Types\RichBlockCaption;
use EasyTel\Types\RichBlockTableCell;
use EasyTel\Types\RichBlockListItem;
use EasyTel\Types\RichBlock;
use EasyTel\Types\RichBlockParagraph;
use EasyTel\Types\RichBlockSectionHeading;
use EasyTel\Types\RichBlockPreformatted;
use EasyTel\Types\RichBlockFooter;
use EasyTel\Types\RichBlockDivider;
use EasyTel\Types\RichBlockMathematicalExpression;
use EasyTel\Types\RichBlockAnchor;
use EasyTel\Types\RichBlockList;
use EasyTel\Types\RichBlockBlockQuotation;
use EasyTel\Types\RichBlockExpandableBlockQuotation;
use EasyTel\Types\RichBlockPullQuotation;
use EasyTel\Types\RichBlockCollage;
use EasyTel\Types\RichBlockSlideshow;
use EasyTel\Types\RichBlockTable;
use EasyTel\Types\RichBlockDetails;
use EasyTel\Types\RichBlockMap;
use EasyTel\Types\RichBlockButtons;
use EasyTel\Types\RichBlockAnimation;
use EasyTel\Types\RichBlockAudio;
use EasyTel\Types\RichBlockDocument;
use EasyTel\Types\RichBlockPhoto;
use EasyTel\Types\RichBlockVideo;
use EasyTel\Types\RichBlockVoiceNote;
use EasyTel\Types\RichBlockThinking;
use EasyTel\Types\InputRichBlockListItem;
use EasyTel\Types\InputRichBlock;
use EasyTel\Types\InputRichBlockParagraph;
use EasyTel\Types\InputRichBlockSectionHeading;
use EasyTel\Types\InputRichBlockPreformatted;
use EasyTel\Types\InputRichBlockFooter;
use EasyTel\Types\InputRichBlockDivider;
use EasyTel\Types\InputRichBlockMathematicalExpression;
use EasyTel\Types\InputRichBlockAnchor;
use EasyTel\Types\InputRichBlockList;
use EasyTel\Types\InputRichBlockBlockQuotation;
use EasyTel\Types\InputRichBlockExpandableBlockQuotation;
use EasyTel\Types\InputRichBlockPullQuotation;
use EasyTel\Types\InputRichBlockCollage;
use EasyTel\Types\InputRichBlockSlideshow;
use EasyTel\Types\InputRichBlockTable;
use EasyTel\Types\InputRichBlockDetails;
use EasyTel\Types\InputRichBlockMap;
use EasyTel\Types\InputRichBlockButtons;
use EasyTel\Types\InputRichBlockAnimation;
use EasyTel\Types\InputRichBlockAudio;
use EasyTel\Types\InputRichBlockDocument;
use EasyTel\Types\InputRichBlockPhoto;
use EasyTel\Types\InputRichBlockVideo;
use EasyTel\Types\InputRichBlockVoiceNote;
use EasyTel\Types\InputRichBlockThinking;
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
use EasyTel\Types\InputRichMessageContent;
use EasyTel\Types\InputLocationMessageContent;
use EasyTel\Types\InputVenueMessageContent;
use EasyTel\Types\InputContactMessageContent;
use EasyTel\Types\InputInvoiceMessageContent;
use EasyTel\Types\InputWidgetMessageContent;
use EasyTel\Types\InputSharedWidgetMessageContent;
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
 * @method Update Update() This <a href="https://core.telegram.org/bots/api#available-types">object</a> represents an incoming update.<br>At most <strong>one</strong> of the optional fields can be present in any given update.
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
 * @method EphemeralMessageParameters EphemeralMessageParameters() 
 * @method MessageOrigin MessageOrigin() This object describes the origin of a message. It can be one of
 * @method MessageOriginUser MessageOriginUser() The message was originally sent by a known user.
 * @method MessageOriginHiddenUser MessageOriginHiddenUser() The message was originally sent by an unknown user.
 * @method MessageOriginChat MessageOriginChat() The message was originally sent on behalf of a chat to a group chat.
 * @method MessageOriginChannel MessageOriginChannel() The message was originally sent to a channel chat.
 * @method PhotoSize PhotoSize() This object represents one size of a photo or a <a href="https://core.telegram.org/bots/api#document">file</a> / <a href="https://core.telegram.org/bots/api#sticker">sticker</a> thumbnail.
 * @method Animation Animation() This object represents an animation file (GIF or H.264/MPEG-4 AVC video without sound).
 * @method Audio Audio() This object represents an audio file to be treated as music by the Telegram clients.
 * @method Document Document() This object represents a general file (as opposed to <a href="https://core.telegram.org/bots/api#photosize">photos</a>, <a href="https://core.telegram.org/bots/api#voice">voice messages</a> and <a href="https://core.telegram.org/bots/api#audio">audio files</a>).
 * @method LivePhoto LivePhoto() This object represents a live photo.
 * @method Story Story() This object represents a story.
 * @method VideoQuality VideoQuality() This object represents a video file of a specific quality.
 * @method Video Video() This object represents a video file.
 * @method VideoNote VideoNote() This object represents a <a href="https://telegram.org/blog/video-messages-and-telescope">video message</a>.
 * @method Voice Voice() This object represents a voice note.
 * @method Widget Widget() This object describes an <a href="/bots/widgets">HTML widget</a>.
 * @method PaidMediaInfo PaidMediaInfo() Describes the paid media added to a message.
 * @method PaidMedia PaidMedia() This object describes paid media. Currently, it can be one of
 * @method PaidMediaLivePhoto PaidMediaLivePhoto() The paid media is a <a href="https://core.telegram.org/bots/api#livephoto">live photo</a>.
 * @method PaidMediaPhoto PaidMediaPhoto() The paid media is a photo.
 * @method PaidMediaPreview PaidMediaPreview() The paid media isn't available before the payment.
 * @method PaidMediaVideo PaidMediaVideo() The paid media is a video.
 * @method Contact Contact() This object represents a phone contact.
 * @method Dice Dice() This object represents an animated emoji that displays a random value.
 * @method Link Link() Represents an HTTP link.
 * @method PollMedia PollMedia() At most <strong>one</strong> of the optional fields can be present in any given object.
 * @method InputPollMedia InputPollMedia() This object represents the content of a poll description or a quiz explanation to be sent. It should be one of
 * @method InputPollOptionMedia InputPollOptionMedia() This object represents the content of a poll option to be sent. It should be one of
 * @method PollOption PollOption() This object contains information about one answer option in a poll.
 * @method InputPollOption InputPollOption() This object contains information about one answer option in a poll to be sent.
 * @method PollAnswer PollAnswer() This object represents an answer of a user in a non-anonymous poll.
 * @method Poll Poll() This object contains information about a poll.
 * @method ChecklistTask ChecklistTask() Describes a task in a checklist.
 * @method Checklist Checklist() Describes a checklist.
 * @method InputChecklistTask InputChecklistTask() Describes a task to add to a checklist.
 * @method InputChecklist InputChecklist() Describes a checklist to create.
 * @method Location Location() This object represents a point on the map.
 * @method Venue Venue() This object represents a venue.
 * @method WebAppData WebAppData() Describes data sent from a <a href="/bots/webapps">Web App</a> to the bot.
 * @method ProximityAlertTriggered ProximityAlertTriggered() This object represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
 * @method MessageAutoDeleteTimerChanged MessageAutoDeleteTimerChanged() This object represents a service message about a change in auto-delete timer settings.
 * @method ManagedBotCreated ManagedBotCreated() This object contains information about the bot that was created to be managed by the current bot.
 * @method ManagedBotUpdated ManagedBotUpdated() This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
 * @method BotSubscriptionUpdated BotSubscriptionUpdated() This object contains information about changes to a user payment subscription toward the current bot.
 * @method MessageGenerationStopped MessageGenerationStopped() This object describes an update about a user stopping message generation.
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
 * @method ChecklistTasksDone ChecklistTasksDone() Describes a service message about checklist tasks marked as done or not done.
 * @method ChecklistTasksAdded ChecklistTasksAdded() Describes a service message about tasks added to a checklist.
 * @method CommunityChatAdded CommunityChatAdded() Describes a service message about a chat or a bot being added to a community.
 * @method CommunityChatJoined CommunityChatJoined() Describes a service message about a chat being joined by a user from a community.
 * @method CommunityChatRemoved CommunityChatRemoved() Describes a service message about a chat or a bot being removed from a community. Currently holds no information.
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
 * @method PaidMessagesWaived PaidMessagesWaived() Describes a service message about the sender of the message using a link of the other user, which allows sending messages to the user without paying Telegram Stars. Currently holds no information.
 * @method TonWalletTransfer TonWalletTransfer() Describes a service message about a transfer with a TON wallet.
 * @method GiveawayCreated GiveawayCreated() This object represents a service message about the creation of a scheduled giveaway.
 * @method Giveaway Giveaway() This object represents a message about a scheduled giveaway.
 * @method GiveawayWinners GiveawayWinners() This object represents a message about the completion of a giveaway with public winners.
 * @method GiveawayCompleted GiveawayCompleted() This object represents a service message about the completion of a giveaway without public winners.
 * @method LinkPreviewOptions LinkPreviewOptions() Describes the options used for link preview generation.
 * @method SuggestedPostPrice SuggestedPostPrice() Describes the price of a suggested post.
 * @method SuggestedPostInfo SuggestedPostInfo() Contains information about a suggested post.
 * @method SuggestedPostParameters SuggestedPostParameters() Contains parameters of a post that is being suggested by the bot.
 * @method DirectMessagesTopic DirectMessagesTopic() Describes a topic of a direct messages chat.
 * @method UserProfilePhotos UserProfilePhotos() This object represent a user's profile pictures.
 * @method UserProfileAudios UserProfileAudios() This object represents the audios displayed on a user's profile.
 * @method File File() This object represents a file ready to be downloaded. The file can be downloaded via the link <code>https://api.telegram.org/file/bot<token>/<file_path></code>. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling <a href="https://core.telegram.org/bots/api#getfile">getFile</a>.
 * @method WebAppInfo WebAppInfo() Describes a <a href="/bots/webapps">Web App</a>.
 * @method ReplyKeyboardMarkup ReplyKeyboardMarkup() This object represents a <a href="/bots/features#keyboards">custom keyboard</a> with reply options (see <a href="/bots/features#keyboards">Introduction to bots</a> for details and examples). Not supported in channels and for messages sent on behalf of a business account.
 * @method KeyboardButton KeyboardButton() This object represents one button of the reply keyboard. At most one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button. For simple text buttons, <em>String</em> can be used instead of this object to specify the button text.
 * @method KeyboardButtonRequestUsers KeyboardButtonRequestUsers() This object defines the criteria used to request suitable users. Information about the selected users will be shared with the bot when the corresponding button is pressed. <a href="/bots/features#chat-and-user-selection">More about requesting users »</a>
 * @method KeyboardButtonRequestChat KeyboardButtonRequestChat() This object defines the criteria used to request a suitable chat. Information about the selected chat will be shared with the bot when the corresponding button is pressed. The bot will be granted requested rights in the chat if appropriate. <a href="/bots/features#chat-and-user-selection">More about requesting chats »</a>.
 * @method KeyboardButtonRequestManagedBot KeyboardButtonRequestManagedBot() This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update <em>managed_bot</em> and a <a href="https://core.telegram.org/bots/api#message">Message</a> with the field <em>managed_bot_created</em>.
 * @method KeyboardButtonPollType KeyboardButtonPollType() This object represents type of a poll, which is allowed to be created and sent when the corresponding button is pressed.
 * @method ReplyKeyboardRemove ReplyKeyboardRemove() Upon receiving a message with this object, Telegram clients will remove the current custom keyboard and display the default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot. An exception is made for one-time keyboards that are hidden immediately after the user presses a button (see <a href="https://core.telegram.org/bots/api#replykeyboardmarkup">ReplyKeyboardMarkup</a>). Not supported in channels and for messages sent on behalf of a business account.
 * @method InlineKeyboardMarkup InlineKeyboardMarkup() This object represents an <a href="/bots/features#inline-keyboards">inline keyboard</a> that appears right next to the message it belongs to.
 * @method InlineKeyboardButton InlineKeyboardButton() This object represents one button of an inline keyboard. Exactly one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button.
 * @method LoginUrl LoginUrl() This object represents a parameter of the inline keyboard button used to automatically authorize a user. It serves as a great replacement for the <a href="/widgets/login">Telegram Login Widget</a> when the user is coming from Telegram. All the user needs to do is tap/click a button and confirm that they want to log in:
 * @method SwitchInlineQueryChosenChat SwitchInlineQueryChosenChat() This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
 * @method CopyTextButton CopyTextButton() This object represents an inline keyboard button that copies specified text to the clipboard.
 * @method DisabledButton DisabledButton() This object represents a disabled button which does nothing. Currently holds no information.
 * @method CallbackQuery CallbackQuery() This object represents an incoming callback query from a callback button in an <a href="/bots/features#inline-keyboards">inline keyboard</a>. If the button that originated the query was attached to a message sent by the bot, the field <em>message</em> will be present. If the button was attached to a message sent via the bot (in <a href="https://core.telegram.org/bots/api#inline-mode">inline mode</a>), the field <em>inline_message_id</em> will be present. Exactly one of the fields <em>data</em> or <em>game_short_name</em> will be present.
 * @method WidgetQueryType WidgetQueryType() This object describes the type of a query from a widget. Currently, it can be one of
 * @method WidgetQueryTypeData WidgetQueryTypeData() The query contains data for the bot.
 * @method WidgetQueryTypeSignData WidgetQueryTypeSignData() The query contains data signed by a TON wallet of the user who sent the query. The data is signed for the domain <code>bot<bot_id></code>, where <code><bot_id></code> is the identifier of the bot.
 * @method WidgetQueryTypeSentTransaction WidgetQueryTypeSentTransaction() The query notifies the bot about a transaction sent from a TON wallet of the user who sent the query.
 * @method WidgetQueryWallet WidgetQueryWallet() This object describes a TON wallet of the user, which is used in a query from a widget.
 * @method WidgetQuery WidgetQuery() This object represents an incoming query from a widget in a message sent by the bot or via the bot. Use <a href="https://core.telegram.org/bots/api#answerwidgetquery">answerWidgetQuery</a> to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 * @method SharedWidgetQuery SharedWidgetQuery() This object represents an incoming query from a forwarded message with a shared widget created by the bot. Use <a href="https://core.telegram.org/bots/api#answerwidgetquery">answerWidgetQuery</a> to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 * @method ForceReply ForceReply() Upon receiving a message with this object, Telegram clients will display a reply interface to the user (act as if the user has selected the bot's message and tapped 'Reply'). This can be extremely useful if you want to create user-friendly step-by-step interfaces without having to sacrifice <a href="/bots/features#privacy-mode">privacy mode</a>. Not supported in channels and for messages sent on behalf of a user account.
 * @method Community Community() Represents a community (a group of chats).
 * @method ChatPhoto ChatPhoto() This object represents a chat photo.
 * @method ChatInviteLink ChatInviteLink() Represents an invite link for a chat.
 * @method ChatAdministratorRights ChatAdministratorRights() Represents the rights of an administrator in a chat.
 * @method ChatMemberUpdated ChatMemberUpdated() This object represents changes in the status of a chat member.
 * @method ChatMember ChatMember() This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:
 * @method ChatMemberOwner ChatMemberOwner() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that owns the chat and has all administrator privileges.
 * @method ChatMemberAdministrator ChatMemberAdministrator() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has some additional privileges.
 * @method ChatMemberMember ChatMemberMember() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has no additional privileges or restrictions.
 * @method ChatMemberRestricted ChatMemberRestricted() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that is under certain restrictions in the chat. Supergroups only.
 * @method ChatMemberLeft ChatMemberLeft() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that isn't currently a member of the chat, but may join it themselves.
 * @method ChatMemberBanned ChatMemberBanned() Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that was banned in the chat and can't return to the chat or view chat messages.
 * @method ChatJoinRequest ChatJoinRequest() Represents a join request sent to a chat.
 * @method ChatPermissions ChatPermissions() Describes actions that a non-administrator user is allowed to take in a chat.
 * @method Birthdate Birthdate() Describes the birthdate of a user.
 * @method BusinessIntro BusinessIntro() Contains information about the start page settings of a Telegram Business account.
 * @method BusinessLocation BusinessLocation() Contains information about the location of a Telegram Business account.
 * @method BusinessOpeningHoursInterval BusinessOpeningHoursInterval() Describes an interval of time during which a business is open.
 * @method BusinessOpeningHours BusinessOpeningHours() Describes the opening hours of a business.
 * @method UserRating UserRating() This object describes the rating of a user based on their Telegram Star spendings.
 * @method AreaPosition AreaPosition() Describes the position of a rectangle area on a story media or a TON wallet card.
 * @method TonNft TonNft() This object represents an NFT in the TON blockchain.
 * @method TonNftAttribute TonNftAttribute() This object represents an attribute of an NFT in the TON blockchain.
 * @method TonWalletCardSticker TonWalletCardSticker() This object describes a sticker on a TON wallet card.
 * @method TonWalletCard TonWalletCard() This object describes a TON wallet card, which is shown on the profile page of a user to everyone.
 * @method EmojiType EmojiType() This object describes an emoji. Currently, it can be one of
 * @method EmojiTypeEmoji EmojiTypeEmoji() A regular emoji.
 * @method EmojiTypeCustomEmoji EmojiTypeCustomEmoji() A custom emoji.
 * @method ChatShareTheme ChatShareTheme() This object describes the theme of the screen for sharing a link to a user or a chat. Currently, it can be one of
 * @method ChatShareThemeEmoji ChatShareThemeEmoji() The theme with emoji placed on a background.
 * @method ChatShareThemeUniqueGift ChatShareThemeUniqueGift() The theme based on a unique gift owned by the user or the chat.
 * @method ChatShareThemeBackdrops ChatShareThemeBackdrops() This object contains a list of backdrops, which can be used as the background of a theme of the screen for sharing a link to a user or a chat.
 * @method ChatShareThemeBackdrop ChatShareThemeBackdrop() This object describes a backdrop, which can be used as the background of a theme of the screen for sharing a link to a user or a chat.
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
 * @method UniqueGiftColors UniqueGiftColors() This object contains information about the color scheme for a user's name, message replies and link previews based on a unique gift.
 * @method UniqueGift UniqueGift() This object describes a unique gift that was upgraded from a regular gift.
 * @method GiftInfo GiftInfo() Describes a service message about a regular gift that was sent or received.
 * @method UniqueGiftInfo UniqueGiftInfo() Describes a service message about a unique gift that was sent or received.
 * @method OwnedGift OwnedGift() This object describes a gift received and owned by a user or a chat. Currently, it can be one of
 * @method OwnedGiftRegular OwnedGiftRegular() Describes a regular gift owned by a user or a chat.
 * @method OwnedGiftUnique OwnedGiftUnique() Describes a unique gift received and owned by a user or a chat.
 * @method OwnedGifts OwnedGifts() Contains the list of gifts received and owned by a user or a chat.
 * @method BotAccessSettings BotAccessSettings() This object describes the access settings of a bot.
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
 * @method BotName BotName() This object represents the bot's name.
 * @method BotDescription BotDescription() This object represents the bot's description.
 * @method BotShortDescription BotShortDescription() This object represents the bot's short description.
 * @method MenuButton MenuButton() This object describes the bot's menu button in a private chat. It should be one of
 * @method MenuButtonCommands MenuButtonCommands() Represents a menu button, which opens the bot's list of commands.
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
 * @method SentGuestMessage SentGuestMessage() Describes an inline message sent by a guest bot.
 * @method PreparedInlineMessage PreparedInlineMessage() Describes an inline message to be sent by a user of a Mini App.
 * @method PreparedKeyboardButton PreparedKeyboardButton() Describes a keyboard button to be used by a user of a Mini App.
 * @method ResponseParameters ResponseParameters() Describes why a request was unsuccessful.
 * @method InputMedia InputMedia() This object represents the content of a media message to be sent. It should be one of
 * @method InputMediaAnimation InputMediaAnimation() Represents an animation file (GIF or H.264/MPEG-4 AVC video without sound) to be sent.
 * @method InputMediaAudio InputMediaAudio() Represents an audio file to be treated as music to be sent.
 * @method InputMediaDocument InputMediaDocument() Represents a general file to be sent.
 * @method InputMediaLink InputMediaLink() Represents an HTTP link to be sent.
 * @method InputMediaLivePhoto InputMediaLivePhoto() Represents a live photo to be sent.
 * @method InputMediaLocation InputMediaLocation() Represents a location to be sent.
 * @method InputMediaPhoto InputMediaPhoto() Represents a photo to be sent.
 * @method InputMediaSticker InputMediaSticker() Represents a sticker file to be sent.
 * @method InputMediaVenue InputMediaVenue() Represents a venue to be sent.
 * @method InputMediaVideo InputMediaVideo() Represents a video to be sent.
 * @method InputMediaVoiceNote InputMediaVoiceNote() Represents a voice message file to be sent.
 * @method InputFile InputFile() This object represents the contents of a file to be uploaded. Must be posted using multipart/form-data in the usual way that files are uploaded via the browser.
 * @method InputPaidMedia InputPaidMedia() This object describes the paid media to be sent. Currently, it can be one of
 * @method InputPaidMediaLivePhoto InputPaidMediaLivePhoto() The paid media to send is a live photo.
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
 * @method RichMessage RichMessage() Rich formatted message.
 * @method InputRichMessage InputRichMessage() Describes a rich message to be sent. Exactly <strong>one</strong> of the fields <em>html</em>, <em>markdown</em>, or <em>blocks</em> must be used.
 * @method InputRichMessageMedia InputRichMessageMedia() Describes a media element embedded in an outgoing rich message.
 * @method RichMessageButton RichMessageButton() This object represents a button in a <a href="https://core.telegram.org/bots/api#richmessage">RichMessage</a>. Exactly one of the fields other than <em>text</em> and <em>style</em> must be used to specify the type of the button.
 * @method RichText RichText() This object represents a rich formatted text. Currently, it can be either a String for plain text, an Array of <a href="https://core.telegram.org/bots/api#richtext">RichText</a>, or any of the following types:
 * @method RichTextBold RichTextBold() A bold text.
 * @method RichTextItalic RichTextItalic() An italicized text.
 * @method RichTextUnderline RichTextUnderline() An underlined text.
 * @method RichTextStrikethrough RichTextStrikethrough() A strikethrough text.
 * @method RichTextSpoiler RichTextSpoiler() A text covered by a spoiler.
 * @method RichTextDateTime RichTextDateTime() Formatted date and time.
 * @method RichTextTextMention RichTextTextMention() A mention of a Telegram user by their identifier.
 * @method RichTextSubscript RichTextSubscript() A subscript text.
 * @method RichTextSuperscript RichTextSuperscript() A superscript text.
 * @method RichTextMarked RichTextMarked() A marked text.
 * @method RichTextCode RichTextCode() A monowidth text.
 * @method RichTextCustomEmoji RichTextCustomEmoji() A custom emoji.
 * @method RichTextMathematicalExpression RichTextMathematicalExpression() A mathematical expression.
 * @method RichTextUrl RichTextUrl() A text with a link.
 * @method RichTextEmailAddress RichTextEmailAddress() A text with an email address.
 * @method RichTextPhoneNumber RichTextPhoneNumber() A text with a phone number.
 * @method RichTextBankCardNumber RichTextBankCardNumber() A text with a bank card number.
 * @method RichTextTonAddress RichTextTonAddress() An address in the TON blockchain.
 * @method RichTextMention RichTextMention() A mention by a username.
 * @method RichTextHashtag RichTextHashtag() A hashtag.
 * @method RichTextCashtag RichTextCashtag() A cashtag.
 * @method RichTextBotCommand RichTextBotCommand() A bot command.
 * @method RichTextButton RichTextButton() A button.
 * @method RichTextAnchor RichTextAnchor() An anchor.
 * @method RichTextAnchorLink RichTextAnchorLink() A link to an anchor.
 * @method RichTextReference RichTextReference() A reference.
 * @method RichTextReferenceLink RichTextReferenceLink() A link to a reference.
 * @method RichBlockCaption RichBlockCaption() Caption of a rich formatted block.
 * @method RichBlockTableCell RichBlockTableCell() Cell in a table.
 * @method RichBlockListItem RichBlockListItem() An item of a list.
 * @method RichBlock RichBlock() This object represents a block in a rich formatted message. Currently, it can be any of the following types:
 * @method RichBlockParagraph RichBlockParagraph() A text paragraph, corresponding to the HTML tag <code><p></code>.
 * @method RichBlockSectionHeading RichBlockSectionHeading() A section heading, corresponding to the HTML tags <code><h1></code>, <code><h2></code>, <code><h3></code>, <code><h4></code>, <code><h5></code>, or <code><h6></code>.
 * @method RichBlockPreformatted RichBlockPreformatted() A preformatted text block, corresponding to the nested HTML tags <code><pre></code> and <code><code></code>.
 * @method RichBlockFooter RichBlockFooter() A footer, corresponding to the HTML tag <code><footer></code>.
 * @method RichBlockDivider RichBlockDivider() A divider, corresponding to the HTML tag <code><hr/></code>.
 * @method RichBlockMathematicalExpression RichBlockMathematicalExpression() A block with a mathematical expression in LaTeX format, corresponding to the custom HTML tag <code><tg-math-block></code>.
 * @method RichBlockAnchor RichBlockAnchor() A block with an anchor, corresponding to the HTML tag <code><a></code> with the attribute <code>name</code>.
 * @method RichBlockList RichBlockList() A list of blocks, corresponding to the HTML tag <code><ul></code> or <code><ol></code> with multiple nested tags <code><li></code>.
 * @method RichBlockBlockQuotation RichBlockBlockQuotation() A block quotation, corresponding to the HTML tag <code><blockquote></code>.
 * @method RichBlockExpandableBlockQuotation RichBlockExpandableBlockQuotation() A block quotation, corresponding to the HTML tag <code><blockquote></code> with custom attribute <code>"expandable"</code>.
 * @method RichBlockPullQuotation RichBlockPullQuotation() A quotation with centered text, loosely corresponding to the HTML tag <code><aside></code>.
 * @method RichBlockCollage RichBlockCollage() A collage, corresponding to the custom HTML tag <code><tg-collage></code>.
 * @method RichBlockSlideshow RichBlockSlideshow() A slideshow, corresponding to the custom HTML tag <code><tg-slideshow></code>.
 * @method RichBlockTable RichBlockTable() A table, corresponding to the HTML tag <code><table></code>.
 * @method RichBlockDetails RichBlockDetails() An expandable block for details disclosure, corresponding to the HTML tag <code><details></code>.
 * @method RichBlockMap RichBlockMap() A block with a map, corresponding to the custom HTML tag <code><tg-map></code>.
 * @method RichBlockButtons RichBlockButtons() A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <code><tg-button-row></code>.
 * @method RichBlockAnimation RichBlockAnimation() A block with an animation, corresponding to the HTML tag <code><video></code>.
 * @method RichBlockAudio RichBlockAudio() A block with a music file, corresponding to the HTML tag <code><audio></code>.
 * @method RichBlockDocument RichBlockDocument() A block with a general file, corresponding to the custom HTML tag <code><tg-document></code>.
 * @method RichBlockPhoto RichBlockPhoto() A block with a photo, corresponding to the HTML tag <code><img></code>.
 * @method RichBlockVideo RichBlockVideo() A block with a video, corresponding to the HTML tag <code><video></code>.
 * @method RichBlockVoiceNote RichBlockVoiceNote() A block with a voice note, corresponding to the HTML tag <code><audio></code>.
 * @method RichBlockThinking RichBlockThinking() A block with a “Thinking…” placeholder, corresponding to the custom HTML tag <code><tg-thinking></code>. The block may be used only in <a href="https://core.telegram.org/bots/api#sendrichmessagedraft">sendRichMessageDraft</a>, therefore it can't be received in messages. See <a href="https://t.me/addemoji/AIActions"><a href="https://t.me/addemoji/AIActions">https://t.me/addemoji/AIActions</a></a> for examples of custom emoji that are recommended for usage in the block.
 * @method InputRichBlockListItem InputRichBlockListItem() An item of a list to be sent.
 * @method InputRichBlock InputRichBlock() This object represents a block in a rich formatted message to be sent. Currently, it can be any of the following types:
 * @method InputRichBlockParagraph InputRichBlockParagraph() A text paragraph, corresponding to the HTML tag <code><p></code>.
 * @method InputRichBlockSectionHeading InputRichBlockSectionHeading() A section heading, corresponding to the HTML tags <code><h1></code>, <code><h2></code>, <code><h3></code>, <code><h4></code>, <code><h5></code>, or <code><h6></code>.
 * @method InputRichBlockPreformatted InputRichBlockPreformatted() A preformatted text block, corresponding to the nested HTML tags <code><pre></code> and <code><code></code>.
 * @method InputRichBlockFooter InputRichBlockFooter() A footer, corresponding to the HTML tag <code><footer></code>.
 * @method InputRichBlockDivider InputRichBlockDivider() A divider, corresponding to the HTML tag <code><hr/></code>.
 * @method InputRichBlockMathematicalExpression InputRichBlockMathematicalExpression() A block with a mathematical expression in LaTeX format, corresponding to the custom HTML tag <code><tg-math-block></code>.
 * @method InputRichBlockAnchor InputRichBlockAnchor() A block with an anchor, corresponding to the HTML tag <code><a></code> with the attribute <code>name</code>.
 * @method InputRichBlockList InputRichBlockList() A list of blocks, corresponding to the HTML tag <code><ul></code> or <code><ol></code> with multiple nested tags <code><li></code>.
 * @method InputRichBlockBlockQuotation InputRichBlockBlockQuotation() A block quotation, corresponding to the HTML tag <code><blockquote></code>.
 * @method InputRichBlockExpandableBlockQuotation InputRichBlockExpandableBlockQuotation() A block quotation, corresponding to the HTML tag <code><blockquote></code> with custom attribute <code>"expandable"</code>.
 * @method InputRichBlockPullQuotation InputRichBlockPullQuotation() A quotation with centered text, loosely corresponding to the HTML tag <code><aside></code>.
 * @method InputRichBlockCollage InputRichBlockCollage() A collage, corresponding to the custom HTML tag <code><tg-collage></code>.
 * @method InputRichBlockSlideshow InputRichBlockSlideshow() A slideshow, corresponding to the custom HTML tag <code><tg-slideshow></code>.
 * @method InputRichBlockTable InputRichBlockTable() A table, corresponding to the HTML tag <code><table></code>.
 * @method InputRichBlockDetails InputRichBlockDetails() An expandable block for details disclosure, corresponding to the HTML tag <code><details></code>.
 * @method InputRichBlockMap InputRichBlockMap() A block with a map, corresponding to the custom HTML tag <code><tg-map></code>. The map's width and height must not exceed 10000 in total. The width and height ratio must be at most 20.
 * @method InputRichBlockButtons InputRichBlockButtons() A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <code><tg-button-row></code>.
 * @method InputRichBlockAnimation InputRichBlockAnimation() A block with an animation, corresponding to the HTML tag <code><video></code>.
 * @method InputRichBlockAudio InputRichBlockAudio() A block with a music file, corresponding to the HTML tag <code><audio></code>.
 * @method InputRichBlockDocument InputRichBlockDocument() A block with a general file, corresponding to the custom HTML tag <code><tg-document></code>.
 * @method InputRichBlockPhoto InputRichBlockPhoto() A block with a photo, corresponding to the HTML tag <code><img></code>.
 * @method InputRichBlockVideo InputRichBlockVideo() A block with a video, corresponding to the HTML tag <code><video></code>.
 * @method InputRichBlockVoiceNote InputRichBlockVoiceNote() A block with a voice note, corresponding to the HTML tag <code><audio></code>.
 * @method InputRichBlockThinking InputRichBlockThinking() A block with a “Thinking…” placeholder, corresponding to the custom HTML tag <code><tg-thinking></code>. The block may be used only in <a href="https://core.telegram.org/bots/api#sendrichmessagedraft">sendRichMessageDraft</a>, therefore it can't be received in messages. See <a href="https://t.me/addemoji/AIActions"><a href="https://t.me/addemoji/AIActions">https://t.me/addemoji/AIActions</a></a> for examples of custom emoji that are recommended for usage in the block.
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
 * @method InlineQueryResultGame InlineQueryResultGame() Represents a <a href="https://core.telegram.org/bots/api#game">Game</a>.
 * @method InlineQueryResultCachedPhoto InlineQueryResultCachedPhoto() Represents a link to a photo stored on the Telegram servers. By default, this photo will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the photo.
 * @method InlineQueryResultCachedGif InlineQueryResultCachedGif() Represents a link to an animated GIF file stored on the Telegram servers. By default, this animated GIF file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with specified content instead of the animation.
 * @method InlineQueryResultCachedMpeg4Gif InlineQueryResultCachedMpeg4Gif() Represents a link to a video animation (H.264/MPEG-4 AVC video without sound) stored on the Telegram servers. By default, this animated MPEG-4 file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the animation.
 * @method InlineQueryResultCachedSticker InlineQueryResultCachedSticker() Represents a link to a sticker stored on the Telegram servers. By default, this sticker will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the sticker.
 * @method InlineQueryResultCachedDocument InlineQueryResultCachedDocument() Represents a link to a file stored on the Telegram servers. By default, this file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the file.
 * @method InlineQueryResultCachedVideo InlineQueryResultCachedVideo() Represents a link to a video file stored on the Telegram servers. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the video.
 * @method InlineQueryResultCachedVoice InlineQueryResultCachedVoice() Represents a link to a voice message stored on the Telegram servers. By default, this voice message will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the voice message.
 * @method InlineQueryResultCachedAudio InlineQueryResultCachedAudio() Represents a link to an MP3 audio file stored on the Telegram servers. By default, this audio file will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the audio.
 * @method InputMessageContent InputMessageContent() This object represents the content of a message to be sent as a result of an inline query. Telegram clients currently support the following types:
 * @method InputTextMessageContent InputTextMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a text message to be sent as the result of an inline query.
 * @method InputRichMessageContent InputRichMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a rich message to be sent as the result of an inline query.
 * @method InputLocationMessageContent InputLocationMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a location message to be sent as the result of an inline query.
 * @method InputVenueMessageContent InputVenueMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a venue message to be sent as the result of an inline query.
 * @method InputContactMessageContent InputContactMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a contact message to be sent as the result of an inline query.
 * @method InputInvoiceMessageContent InputInvoiceMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of an invoice message to be sent as the result of an inline query.
 * @method InputWidgetMessageContent InputWidgetMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a message with an <a href="/bots/widgets">HTML widget</a> to be sent as the result of an inline query. Currently, widgets can't be sent in response to inline queries in the production environment.
 * @method InputSharedWidgetMessageContent InputSharedWidgetMessageContent() Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a message with a shared widget to be sent as the result of an inline query. Currently, widgets can't be sent in response to inline queries in the production environment.
 * @method ChosenInlineResult ChosenInlineResult() Represents a <a href="https://core.telegram.org/bots/api#inlinequeryresult">result</a> of an inline query that was chosen by the user and sent to their chat partner.
 * @method LabeledPrice LabeledPrice() This object represents a portion of the price for goods or services.
 * @method Invoice Invoice() This object contains basic information about an invoice.
 * @method ShippingAddress ShippingAddress() This object represents a shipping address.
 * @method OrderInfo OrderInfo() This object represents information about an order.
 * @method ShippingOption ShippingOption() This object represents one shipping option.
 * @method SuccessfulPayment SuccessfulPayment() This object contains basic information about a successful payment. Note that if the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram's control.
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
 * @method StarTransaction StarTransaction() Describes a Telegram Star transaction. Note that if the buyer initiates a chargeback with the payment provider from whom they acquired Stars (e.g., Apple, Google) following this transaction, the refunded Stars will be deducted from the bot's balance. This is outside of Telegram's control.
 * @method StarTransactions StarTransactions() Contains a list of Telegram Star transactions.
 * @method PassportData PassportData() Describes Telegram Passport data shared with the bot by the user.
 * @method PassportFile PassportFile() This object represents a file uploaded to Telegram Passport. Currently all Telegram Passport files are in JPEG format when decrypted and don't exceed 10MB.
 * @method EncryptedPassportElement EncryptedPassportElement() Describes documents or other Telegram Passport elements shared with the bot by the user.
 * @method EncryptedCredentials EncryptedCredentials() Describes data required for decrypting and authenticating <a href="https://core.telegram.org/bots/api#encryptedpassportelement">EncryptedPassportElement</a>. See the <a href="/passport#receiving-information">Telegram Passport Documentation</a> for a complete description of the data decryption and authentication processes.
 * @method PassportElementError PassportElementError() This object represents an error in the Telegram Passport element which was submitted that should be resolved by the user. It should be one of:
 * @method PassportElementErrorDataField PassportElementErrorDataField() Represents an issue in one of the data fields that was provided by the user. The error is considered resolved when the field's value changes.
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
    public mixed $update;

    public function __construct(mixed $update)
    {
        $update = json::to_array($update);
        if (!is_array($update))
            $update = is_object($update) ? (array) $update : [];
        $this->update = (isset($update['ok']) && isset($update['result'])) ? $update['result'] : $update;
    }

    public static function make(mixed $response): Types
    {
        return new self($response);
    }

    /**
     * Instantiate a type class from the stored payload.
     *
     * A non-empty list payload (for example a getUpdates result) resolves to an
     * array of the requested type instead of a single instance.
     *
     * @return object|array<object>
     */
    public function __call(string $name, array $arguments): mixed
    {
        $class = "\\EasyTel\\Types\\$name";

        if (!class_exists($class))
            throw new \BadMethodCallException(sprintf('Unknown Telegram type "%s".', $name));

        if (is_array($this->update) && $this->update !== [] && array_is_list($this->update))
            return array_map(
                fn($item) => new $class(is_array($item) ? $item : (array) $item),
                $this->update
            );

        return new $class(is_array($this->update) ? $this->update : (array) $this->update);
    }
}