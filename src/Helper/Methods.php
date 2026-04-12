<?php

namespace EasyTel\Helper;

use EasyTel\Handler\Request;
use EasyTel\Telegram;
use GuzzleHttp\Client;
use JSON\json;
use EasyTel\Methods\GetUpdates;
use EasyTel\Methods\SetWebhook;
use EasyTel\Methods\DeleteWebhook;
use EasyTel\Methods\GetWebhookInfo;
use EasyTel\Methods\GetMe;
use EasyTel\Methods\LogOut;
use EasyTel\Methods\Close;
use EasyTel\Methods\SendMessage;
use EasyTel\Methods\ForwardMessage;
use EasyTel\Methods\ForwardMessages;
use EasyTel\Methods\CopyMessage;
use EasyTel\Methods\CopyMessages;
use EasyTel\Methods\SendPhoto;
use EasyTel\Methods\SendAudio;
use EasyTel\Methods\SendDocument;
use EasyTel\Methods\SendVideo;
use EasyTel\Methods\SendAnimation;
use EasyTel\Methods\SendVoice;
use EasyTel\Methods\SendVideoNote;
use EasyTel\Methods\SendPaidMedia;
use EasyTel\Methods\SendMediaGroup;
use EasyTel\Methods\SendLocation;
use EasyTel\Methods\SendVenue;
use EasyTel\Methods\SendContact;
use EasyTel\Methods\SendPoll;
use EasyTel\Methods\SendChecklist;
use EasyTel\Methods\SendDice;
use EasyTel\Methods\SendMessageDraft;
use EasyTel\Methods\SendChatAction;
use EasyTel\Methods\SetMessageReaction;
use EasyTel\Methods\GetUserProfilePhotos;
use EasyTel\Methods\GetUserProfileAudios;
use EasyTel\Methods\SetUserEmojiStatus;
use EasyTel\Methods\GetFile;
use EasyTel\Methods\BanChatMember;
use EasyTel\Methods\UnbanChatMember;
use EasyTel\Methods\RestrictChatMember;
use EasyTel\Methods\PromoteChatMember;
use EasyTel\Methods\SetChatAdministratorCustomTitle;
use EasyTel\Methods\SetChatMemberTag;
use EasyTel\Methods\BanChatSenderChat;
use EasyTel\Methods\UnbanChatSenderChat;
use EasyTel\Methods\SetChatPermissions;
use EasyTel\Methods\ExportChatInviteLink;
use EasyTel\Methods\CreateChatInviteLink;
use EasyTel\Methods\EditChatInviteLink;
use EasyTel\Methods\CreateChatSubscriptionInviteLink;
use EasyTel\Methods\EditChatSubscriptionInviteLink;
use EasyTel\Methods\RevokeChatInviteLink;
use EasyTel\Methods\ApproveChatJoinRequest;
use EasyTel\Methods\DeclineChatJoinRequest;
use EasyTel\Methods\SetChatPhoto;
use EasyTel\Methods\DeleteChatPhoto;
use EasyTel\Methods\SetChatTitle;
use EasyTel\Methods\SetChatDescription;
use EasyTel\Methods\PinChatMessage;
use EasyTel\Methods\UnpinChatMessage;
use EasyTel\Methods\UnpinAllChatMessages;
use EasyTel\Methods\LeaveChat;
use EasyTel\Methods\GetChat;
use EasyTel\Methods\GetChatAdministrators;
use EasyTel\Methods\GetChatMemberCount;
use EasyTel\Methods\GetChatMember;
use EasyTel\Methods\SetChatStickerSet;
use EasyTel\Methods\DeleteChatStickerSet;
use EasyTel\Methods\GetForumTopicIconStickers;
use EasyTel\Methods\CreateForumTopic;
use EasyTel\Methods\EditForumTopic;
use EasyTel\Methods\CloseForumTopic;
use EasyTel\Methods\ReopenForumTopic;
use EasyTel\Methods\DeleteForumTopic;
use EasyTel\Methods\UnpinAllForumTopicMessages;
use EasyTel\Methods\EditGeneralForumTopic;
use EasyTel\Methods\CloseGeneralForumTopic;
use EasyTel\Methods\ReopenGeneralForumTopic;
use EasyTel\Methods\HideGeneralForumTopic;
use EasyTel\Methods\UnhideGeneralForumTopic;
use EasyTel\Methods\UnpinAllGeneralForumTopicMessages;
use EasyTel\Methods\AnswerCallbackQuery;
use EasyTel\Methods\GetUserChatBoosts;
use EasyTel\Methods\GetBusinessConnection;
use EasyTel\Methods\GetManagedBotToken;
use EasyTel\Methods\ReplaceManagedBotToken;
use EasyTel\Methods\SetMyCommands;
use EasyTel\Methods\DeleteMyCommands;
use EasyTel\Methods\GetMyCommands;
use EasyTel\Methods\SetMyName;
use EasyTel\Methods\GetMyName;
use EasyTel\Methods\SetMyDescription;
use EasyTel\Methods\GetMyDescription;
use EasyTel\Methods\SetMyShortDescription;
use EasyTel\Methods\GetMyShortDescription;
use EasyTel\Methods\SetMyProfilePhoto;
use EasyTel\Methods\RemoveMyProfilePhoto;
use EasyTel\Methods\SetChatMenuButton;
use EasyTel\Methods\GetChatMenuButton;
use EasyTel\Methods\SetMyDefaultAdministratorRights;
use EasyTel\Methods\GetMyDefaultAdministratorRights;
use EasyTel\Methods\GetAvailableGifts;
use EasyTel\Methods\SendGift;
use EasyTel\Methods\GiftPremiumSubscription;
use EasyTel\Methods\VerifyUser;
use EasyTel\Methods\VerifyChat;
use EasyTel\Methods\RemoveUserVerification;
use EasyTel\Methods\RemoveChatVerification;
use EasyTel\Methods\ReadBusinessMessage;
use EasyTel\Methods\DeleteBusinessMessages;
use EasyTel\Methods\SetBusinessAccountName;
use EasyTel\Methods\SetBusinessAccountUsername;
use EasyTel\Methods\SetBusinessAccountBio;
use EasyTel\Methods\SetBusinessAccountProfilePhoto;
use EasyTel\Methods\RemoveBusinessAccountProfilePhoto;
use EasyTel\Methods\SetBusinessAccountGiftSettings;
use EasyTel\Methods\GetBusinessAccountStarBalance;
use EasyTel\Methods\TransferBusinessAccountStars;
use EasyTel\Methods\GetBusinessAccountGifts;
use EasyTel\Methods\GetUserGifts;
use EasyTel\Methods\GetChatGifts;
use EasyTel\Methods\ConvertGiftToStars;
use EasyTel\Methods\UpgradeGift;
use EasyTel\Methods\TransferGift;
use EasyTel\Methods\PostStory;
use EasyTel\Methods\RepostStory;
use EasyTel\Methods\EditStory;
use EasyTel\Methods\DeleteStory;
use EasyTel\Methods\AnswerWebAppQuery;
use EasyTel\Methods\SavePreparedInlineMessage;
use EasyTel\Methods\SavePreparedKeyboardButton;
use EasyTel\Methods\EditMessageText;
use EasyTel\Methods\EditMessageCaption;
use EasyTel\Methods\EditMessageMedia;
use EasyTel\Methods\EditMessageLiveLocation;
use EasyTel\Methods\StopMessageLiveLocation;
use EasyTel\Methods\EditMessageChecklist;
use EasyTel\Methods\EditMessageReplyMarkup;
use EasyTel\Methods\StopPoll;
use EasyTel\Methods\ApproveSuggestedPost;
use EasyTel\Methods\DeclineSuggestedPost;
use EasyTel\Methods\DeleteMessage;
use EasyTel\Methods\DeleteMessages;
use EasyTel\Methods\SendSticker;
use EasyTel\Methods\GetStickerSet;
use EasyTel\Methods\GetCustomEmojiStickers;
use EasyTel\Methods\UploadStickerFile;
use EasyTel\Methods\CreateNewStickerSet;
use EasyTel\Methods\AddStickerToSet;
use EasyTel\Methods\SetStickerPositionInSet;
use EasyTel\Methods\DeleteStickerFromSet;
use EasyTel\Methods\ReplaceStickerInSet;
use EasyTel\Methods\SetStickerEmojiList;
use EasyTel\Methods\SetStickerKeywords;
use EasyTel\Methods\SetStickerMaskPosition;
use EasyTel\Methods\SetStickerSetTitle;
use EasyTel\Methods\SetStickerSetThumbnail;
use EasyTel\Methods\SetCustomEmojiStickerSetThumbnail;
use EasyTel\Methods\DeleteStickerSet;
use EasyTel\Methods\AnswerInlineQuery;
use EasyTel\Methods\SendInvoice;
use EasyTel\Methods\CreateInvoiceLink;
use EasyTel\Methods\AnswerShippingQuery;
use EasyTel\Methods\AnswerPreCheckoutQuery;
use EasyTel\Methods\GetMyStarBalance;
use EasyTel\Methods\GetStarTransactions;
use EasyTel\Methods\RefundStarPayment;
use EasyTel\Methods\EditUserStarSubscription;
use EasyTel\Methods\SetPassportDataErrors;
use EasyTel\Methods\SendGame;
use EasyTel\Methods\SetGameScore;
use EasyTel\Methods\GetGameHighScores;
use EasyTel\Types\InputChecklist;
use EasyTel\Types\ChatPermissions;
use EasyTel\Types\InputProfilePhoto;
use EasyTel\Types\AcceptedGiftTypes;
use EasyTel\Types\InputStoryContent;
use EasyTel\Types\InlineQueryResult;
use EasyTel\Types\KeyboardButton;
use EasyTel\Types\InputMedia;
use EasyTel\Types\InputSticker;

class Methods
{
    private Request $_request;
    public function __construct(
        Client $guzzle, string $method = 'POST', $output = Telegram::OUTPUT_OBJECT
    )
    {
        $this->_request = new Request($guzzle,$method,$output);
    }

    
    /**
     * Use this method to receive incoming updates using long polling (<a href="https://en.wikipedia.org/wiki/Push_technology#Long_polling">wiki</a>). Returns an Array of <a href="https://core.telegram.org/bots/api#update">Update</a> objects.
     *
     *

     * @return GetUpdates
     * @link https://core.telegram.org/bots/api#getupdates
     */
    public function getUpdates(): GetUpdates
    {
        return new GetUpdates($this->_request);
    }

    /**
     * Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized <a href="https://core.telegram.org/bots/api#update">Update</a>. In case of an unsuccessful request (a request with response <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes">HTTP status code</a> different from <code>2XY</code>), we will repeat the request and give up after a reasonable amount of attempts. Returns <em>True</em> on success.
     *
     * @param string $url HTTPS URL to send updates to. Use an empty string to remove webhook integration
     * @return SetWebhook
     * @link https://core.telegram.org/bots/api#setwebhook
     */
    public function setWebhook(string $url): SetWebhook
    {
        return new SetWebhook($this->_request, $url);
    }

    /**
     * Use this method to remove webhook integration if you decide to switch back to <a href="https://core.telegram.org/bots/api#getupdates">getUpdates</a>. Returns <em>True</em> on success.
     *
     *

     * @return DeleteWebhook
     * @link https://core.telegram.org/bots/api#deletewebhook
     */
    public function deleteWebhook(): DeleteWebhook
    {
        return new DeleteWebhook($this->_request);
    }

    /**
     * Use this method to get current webhook status. Requires no parameters. On success, returns a <a href="https://core.telegram.org/bots/api#webhookinfo">WebhookInfo</a> object. If the bot is using <a href="https://core.telegram.org/bots/api#getupdates">getUpdates</a>, will return an object with the <em>url</em> field empty.
     *
     *

     * @return GetWebhookInfo
     * @link https://core.telegram.org/bots/api#getwebhookinfo
     */
    public function getWebhookInfo(): GetWebhookInfo
    {
        return new GetWebhookInfo($this->_request);
    }

    /**
     * A simple method for testing your bot&#39;s authentication token. Requires no parameters. Returns basic information about the bot in form of a <a href="https://core.telegram.org/bots/api#user">User</a> object.
     *
     *

     * @return GetMe
     * @link https://core.telegram.org/bots/api#getme
     */
    public function getMe(): GetMe
    {
        return new GetMe($this->_request);
    }

    /**
     * Use this method to log out from the cloud Bot API server before launching the bot locally. You <strong>must</strong> log out the bot before running it locally, otherwise there is no guarantee that the bot will receive updates. After a successful call, you can immediately log in on a local server, but will not be able to log in back to the cloud Bot API server for 10 minutes. Returns <em>True</em> on success. Requires no parameters.
     *
     *

     * @return LogOut
     * @link https://core.telegram.org/bots/api#logout
     */
    public function logOut(): LogOut
    {
        return new LogOut($this->_request);
    }

    /**
     * Use this method to close the bot instance before moving it from one local server to another. You need to delete the webhook before calling this method to ensure that the bot isn&#39;t launched again after server restart. The method will return error 429 in the first 10 minutes after the bot is launched. Returns <em>True</em> on success. Requires no parameters.
     *
     *

     * @return Close
     * @link https://core.telegram.org/bots/api#close
     */
    public function close(): Close
    {
        return new Close($this->_request);
    }

    /**
     * Use this method to send text messages. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $text Text of the message to be sent, 1-4096 characters after entities parsing
     * @return SendMessage
     * @link https://core.telegram.org/bots/api#sendmessage
     */
    public function sendMessage(int|string $chat_id, string $text): SendMessage
    {
        return new SendMessage($this->_request, $chat_id, $text);
    }

    /**
     * Use this method to forward messages of any kind. Service messages and messages with protected content can&#39;t be forwarded. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int|string $from_chat_id Unique identifier for the chat where the original message was sent (or channel username in the format <code>@channelusername</code>)
     * @param int $message_id Message identifier in the chat specified in <em>from_chat_id</em>
     * @return ForwardMessage
     * @link https://core.telegram.org/bots/api#forwardmessage
     */
    public function forwardMessage(int|string $chat_id, int|string $from_chat_id, int $message_id): ForwardMessage
    {
        return new ForwardMessage($this->_request, $chat_id, $from_chat_id, $message_id);
    }

    /**
     * Use this method to forward multiple messages of any kind. If some of the specified messages can&#39;t be found or forwarded, they are skipped. Service messages and messages with protected content can&#39;t be forwarded. Album grouping is kept for forwarded messages. On success, an array of <a href="https://core.telegram.org/bots/api#messageid">MessageId</a> of the sent messages is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int|string $from_chat_id Unique identifier for the chat where the original messages were sent (or channel username in the format <code>@channelusername</code>)
     * @param string  $message_ids A JSON-serialized list of 1-100 identifiers of messages in the chat <em>from_chat_id</em> to forward. The identifiers must be specified in a strictly increasing order.
     * @return ForwardMessages
     * @link https://core.telegram.org/bots/api#forwardmessages
     */
    public function forwardMessages(int|string $chat_id, int|string $from_chat_id, string  $message_ids): ForwardMessages
    {
        return new ForwardMessages($this->_request, $chat_id, $from_chat_id, $message_ids);
    }

    /**
     * Use this method to copy messages of any kind. Service messages, paid media messages, giveaway messages, giveaway winners messages, and invoice messages can&#39;t be copied. A quiz <a href="https://core.telegram.org/bots/api#poll">poll</a> can be copied only if the value of the field <em>correct_option_id</em> is known to the bot. The method is analogous to the method <a href="https://core.telegram.org/bots/api#forwardmessage">forwardMessage</a>, but the copied message doesn&#39;t have a link to the original message. Returns the <a href="https://core.telegram.org/bots/api#messageid">MessageId</a> of the sent message on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int|string $from_chat_id Unique identifier for the chat where the original message was sent (or channel username in the format <code>@channelusername</code>)
     * @param int $message_id Message identifier in the chat specified in <em>from_chat_id</em>
     * @return CopyMessage
     * @link https://core.telegram.org/bots/api#copymessage
     */
    public function copyMessage(int|string $chat_id, int|string $from_chat_id, int $message_id): CopyMessage
    {
        return new CopyMessage($this->_request, $chat_id, $from_chat_id, $message_id);
    }

    /**
     * Use this method to copy messages of any kind. If some of the specified messages can&#39;t be found or copied, they are skipped. Service messages, paid media messages, giveaway messages, giveaway winners messages, and invoice messages can&#39;t be copied. A quiz <a href="https://core.telegram.org/bots/api#poll">poll</a> can be copied only if the value of the field <em>correct_option_id</em> is known to the bot. The method is analogous to the method <a href="https://core.telegram.org/bots/api#forwardmessages">forwardMessages</a>, but the copied messages don&#39;t have a link to the original message. Album grouping is kept for copied messages. On success, an array of <a href="https://core.telegram.org/bots/api#messageid">MessageId</a> of the sent messages is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int|string $from_chat_id Unique identifier for the chat where the original messages were sent (or channel username in the format <code>@channelusername</code>)
     * @param string  $message_ids A JSON-serialized list of 1-100 identifiers of messages in the chat <em>from_chat_id</em> to copy. The identifiers must be specified in a strictly increasing order.
     * @return CopyMessages
     * @link https://core.telegram.org/bots/api#copymessages
     */
    public function copyMessages(int|string $chat_id, int|string $from_chat_id, string  $message_ids): CopyMessages
    {
        return new CopyMessages($this->_request, $chat_id, $from_chat_id, $message_ids);
    }

    /**
     * Use this method to send photos. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $photo Photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a photo from the Internet, or upload a new photo using multipart/form-data. The photo must be at most 10 MB in size. The photo&#39;s width and height must not exceed 10000 in total. Width and height ratio must be at most 20. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @return SendPhoto
     * @link https://core.telegram.org/bots/api#sendphoto
     */
    public function sendPhoto(int|string $chat_id, mixed $photo): SendPhoto
    {
        return new SendPhoto($this->_request, $chat_id, $photo);
    }

    /**
     * Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .MP3 or .M4A format. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $audio Audio file to send. Pass a file_id as String to send an audio file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an audio file from the Internet, or upload a new one using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @return SendAudio
     * @link https://core.telegram.org/bots/api#sendaudio
     */
    public function sendAudio(int|string $chat_id, mixed $audio): SendAudio
    {
        return new SendAudio($this->_request, $chat_id, $audio);
    }

    /**
     * Use this method to send general files. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $document File to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @return SendDocument
     * @link https://core.telegram.org/bots/api#senddocument
     */
    public function sendDocument(int|string $chat_id, mixed $document): SendDocument
    {
        return new SendDocument($this->_request, $chat_id, $document);
    }

    /**
     * Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as <a href="https://core.telegram.org/bots/api#document">Document</a>). On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $video Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a video from the Internet, or upload a new video using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @return SendVideo
     * @link https://core.telegram.org/bots/api#sendvideo
     */
    public function sendVideo(int|string $chat_id, mixed $video): SendVideo
    {
        return new SendVideo($this->_request, $chat_id, $video);
    }

    /**
     * Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $animation Animation to send. Pass a file_id as String to send an animation that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an animation from the Internet, or upload a new animation using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @return SendAnimation
     * @link https://core.telegram.org/bots/api#sendanimation
     */
    public function sendAnimation(int|string $chat_id, mixed $animation): SendAnimation
    {
        return new SendAnimation($this->_request, $chat_id, $animation);
    }

    /**
     * Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .OGG file encoded with OPUS, or in .MP3 format, or in .M4A format (other formats may be sent as <a href="https://core.telegram.org/bots/api#audio">Audio</a> or <a href="https://core.telegram.org/bots/api#document">Document</a>). On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $voice Audio file to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @return SendVoice
     * @link https://core.telegram.org/bots/api#sendvoice
     */
    public function sendVoice(int|string $chat_id, mixed $voice): SendVoice
    {
        return new SendVoice($this->_request, $chat_id, $voice);
    }

    /**
     * As of <a href="https://telegram.org/blog/video-messages-and-telescope">v.4.0</a>, Telegram clients support rounded square MPEG4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $video_note Video note to send. Pass a file_id as String to send a video note that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>. Sending video notes by a URL is currently unsupported
     * @return SendVideoNote
     * @link https://core.telegram.org/bots/api#sendvideonote
     */
    public function sendVideoNote(int|string $chat_id, mixed $video_note): SendVideoNote
    {
        return new SendVideoNote($this->_request, $chat_id, $video_note);
    }

    /**
     * Use this method to send paid media. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>). If the chat is a channel, all Telegram Star proceeds from this media will be credited to the chat&#39;s balance. Otherwise, they will be credited to the bot&#39;s balance.
     * @param int $star_count The number of Telegram Stars that must be paid to buy access to the media; 1-25000
     * @param string  $media A JSON-serialized array describing the media to be sent; up to 10 items
     * @return SendPaidMedia
     * @link https://core.telegram.org/bots/api#sendpaidmedia
     */
    public function sendPaidMedia(int|string $chat_id, int $star_count, string  $media): SendPaidMedia
    {
        return new SendPaidMedia($this->_request, $chat_id, $star_count, $media);
    }

    /**
     * Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of <a href="https://core.telegram.org/bots/api#message">Message</a> objects that were sent is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string  $media A JSON-serialized array describing messages to be sent, must include 2-10 items
     * @return SendMediaGroup
     * @link https://core.telegram.org/bots/api#sendmediagroup
     */
    public function sendMediaGroup(int|string $chat_id, string  $media): SendMediaGroup
    {
        return new SendMediaGroup($this->_request, $chat_id, $media);
    }

    /**
     * Use this method to send point on the map. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param Float $latitude Latitude of the location
     * @param Float $longitude Longitude of the location
     * @return SendLocation
     * @link https://core.telegram.org/bots/api#sendlocation
     */
    public function sendLocation(int|string $chat_id, Float $latitude, Float $longitude): SendLocation
    {
        return new SendLocation($this->_request, $chat_id, $latitude, $longitude);
    }

    /**
     * Use this method to send information about a venue. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param Float $latitude Latitude of the venue
     * @param Float $longitude Longitude of the venue
     * @param string $title Name of the venue
     * @param string $address Address of the venue
     * @return SendVenue
     * @link https://core.telegram.org/bots/api#sendvenue
     */
    public function sendVenue(int|string $chat_id, Float $latitude, Float $longitude, string $title, string $address): SendVenue
    {
        return new SendVenue($this->_request, $chat_id, $latitude, $longitude, $title, $address);
    }

    /**
     * Use this method to send phone contacts. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $phone_number Contact&#39;s phone number
     * @param string $first_name Contact&#39;s first name
     * @return SendContact
     * @link https://core.telegram.org/bots/api#sendcontact
     */
    public function sendContact(int|string $chat_id, string $phone_number, string $first_name): SendContact
    {
        return new SendContact($this->_request, $chat_id, $phone_number, $first_name);
    }

    /**
     * Use this method to send a native poll. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>). Polls can&#39;t be sent to channel direct messages chats.
     * @param string $question Poll question, 1-300 characters
     * @param string  $options A JSON-serialized list of 2-12 answer options
     * @return SendPoll
     * @link https://core.telegram.org/bots/api#sendpoll
     */
    public function sendPoll(int|string $chat_id, string $question, string  $options): SendPoll
    {
        return new SendPoll($this->_request, $chat_id, $question, $options);
    }

    /**
     * Use this method to send a checklist on behalf of a connected business account. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param string $business_connection_id Unique identifier of the business connection on behalf of which the message will be sent
     * @param int $chat_id Unique identifier for the target chat
     * @param InputChecklist $checklist A JSON-serialized object for the checklist to send
     * @return SendChecklist
     * @link https://core.telegram.org/bots/api#sendchecklist
     */
    public function sendChecklist(string $business_connection_id, int $chat_id, InputChecklist $checklist): SendChecklist
    {
        return new SendChecklist($this->_request, $business_connection_id, $chat_id, $checklist);
    }

    /**
     * Use this method to send an animated emoji that will display a random value. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return SendDice
     * @link https://core.telegram.org/bots/api#senddice
     */
    public function sendDice(int|string $chat_id): SendDice
    {
        return new SendDice($this->_request, $chat_id);
    }

    /**
     * Use this method to stream a partial message to a user while the message is being generated. Returns <em>True</em> on success.
     *
     * @param int $chat_id Unique identifier for the target private chat
     * @param int $draft_id Unique identifier of the message draft; must be non-zero. Changes of drafts with the same identifier are animated
     * @param string $text Text of the message to be sent, 1-4096 characters after entities parsing
     * @return SendMessageDraft
     * @link https://core.telegram.org/bots/api#sendmessagedraft
     */
    public function sendMessageDraft(int $chat_id, int $draft_id, string $text): SendMessageDraft
    {
        return new SendMessageDraft($this->_request, $chat_id, $draft_id, $text);
    }

    /**
     * Use this method when you need to tell the user that something is happening on the bot&#39;s side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>). Channel chats and channel direct messages chats aren&#39;t supported.
     * @param string $action Type of action to broadcast. Choose one, depending on what the user is about to receive: <em>typing</em> for <a href="https://core.telegram.org/bots/api#sendmessage">text messages</a>, <em>upload_photo</em> for <a href="https://core.telegram.org/bots/api#sendphoto">photos</a>, <em>record_video</em> or <em>upload_video</em> for <a href="https://core.telegram.org/bots/api#sendvideo">videos</a>, <em>record_voice</em> or <em>upload_voice</em> for <a href="https://core.telegram.org/bots/api#sendvoice">voice notes</a>, <em>upload_document</em> for <a href="https://core.telegram.org/bots/api#senddocument">general files</a>, <em>choose_sticker</em> for <a href="https://core.telegram.org/bots/api#sendsticker">stickers</a>, <em>find_location</em> for <a href="https://core.telegram.org/bots/api#sendlocation">location data</a>, <em>record_video_note</em> or <em>upload_video_note</em> for <a href="https://core.telegram.org/bots/api#sendvideonote">video notes</a>.
     * @return SendChatAction
     * @link https://core.telegram.org/bots/api#sendchataction
     */
    public function sendChatAction(int|string $chat_id, string $action): SendChatAction
    {
        return new SendChatAction($this->_request, $chat_id, $action);
    }

    /**
     * Use this method to change the chosen reactions on a message. Service messages of some types can&#39;t be reacted to. Automatically forwarded messages from a channel to its discussion group have the same available reactions as messages in the channel. Bots can&#39;t use paid reactions. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $message_id Identifier of the target message. If the message belongs to a media group, the reaction is set to the first non-deleted message in the group instead.
     * @return SetMessageReaction
     * @link https://core.telegram.org/bots/api#setmessagereaction
     */
    public function setMessageReaction(int|string $chat_id, int $message_id): SetMessageReaction
    {
        return new SetMessageReaction($this->_request, $chat_id, $message_id);
    }

    /**
     * Use this method to get a list of profile pictures for a user. Returns a <a href="https://core.telegram.org/bots/api#userprofilephotos">UserProfilePhotos</a> object.
     *
     * @param int $user_id Unique identifier of the target user
     * @return GetUserProfilePhotos
     * @link https://core.telegram.org/bots/api#getuserprofilephotos
     */
    public function getUserProfilePhotos(int $user_id): GetUserProfilePhotos
    {
        return new GetUserProfilePhotos($this->_request, $user_id);
    }

    /**
     * Use this method to get a list of profile audios for a user. Returns a <a href="https://core.telegram.org/bots/api#userprofileaudios">UserProfileAudios</a> object.
     *
     * @param int $user_id Unique identifier of the target user
     * @return GetUserProfileAudios
     * @link https://core.telegram.org/bots/api#getuserprofileaudios
     */
    public function getUserProfileAudios(int $user_id): GetUserProfileAudios
    {
        return new GetUserProfileAudios($this->_request, $user_id);
    }

    /**
     * Changes the emoji status for a given user that previously allowed the bot to manage their emoji status via the Mini App method <a href="/bots/webapps#initializing-mini-apps">requestEmojiStatusAccess</a>. Returns <em>True</em> on success.
     *
     * @param int $user_id Unique identifier of the target user
     * @return SetUserEmojiStatus
     * @link https://core.telegram.org/bots/api#setuseremojistatus
     */
    public function setUserEmojiStatus(int $user_id): SetUserEmojiStatus
    {
        return new SetUserEmojiStatus($this->_request, $user_id);
    }

    /**
     * Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a <a href="https://core.telegram.org/bots/api#file">File</a> object is returned. The file can then be downloaded via the link <code>https://api.telegram.org/file/bot&lt;token&gt;/&lt;file_path&gt;</code>, where <code>&lt;file_path&gt;</code> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling <a href="https://core.telegram.org/bots/api#getfile">getFile</a> again.
     *
     * @param string $file_id File identifier to get information about
     * @return GetFile
     * @link https://core.telegram.org/bots/api#getfile
     */
    public function getFile(string $file_id): GetFile
    {
        return new GetFile($this->_request, $file_id);
    }

    /**
     * Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless <a href="https://core.telegram.org/bots/api#unbanchatmember">unbanned</a> first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target group or username of the target supergroup or channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return BanChatMember
     * @link https://core.telegram.org/bots/api#banchatmember
     */
    public function banChatMember(int|string $chat_id, int $user_id): BanChatMember
    {
        return new BanChatMember($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to unban a previously banned user in a supergroup or channel. The user will <strong>not</strong> return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be <strong>removed</strong> from the chat. If you don&#39;t want this, use the parameter <em>only_if_banned</em>. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target group or username of the target supergroup or channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return UnbanChatMember
     * @link https://core.telegram.org/bots/api#unbanchatmember
     */
    public function unbanChatMember(int|string $chat_id, int $user_id): UnbanChatMember
    {
        return new UnbanChatMember($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass <em>True</em> for all permissions to lift restrictions from a user. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @param ChatPermissions $permissions A JSON-serialized object for new user permissions
     * @return RestrictChatMember
     * @link https://core.telegram.org/bots/api#restrictchatmember
     */
    public function restrictChatMember(int|string $chat_id, int $user_id, ChatPermissions $permissions): RestrictChatMember
    {
        return new RestrictChatMember($this->_request, $chat_id, $user_id, $permissions);
    }

    /**
     * Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass <em>False</em> for all boolean parameters to demote a user. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return PromoteChatMember
     * @link https://core.telegram.org/bots/api#promotechatmember
     */
    public function promoteChatMember(int|string $chat_id, int $user_id): PromoteChatMember
    {
        return new PromoteChatMember($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @param string $custom_title New custom title for the administrator; 0-16 characters, emoji are not allowed
     * @return SetChatAdministratorCustomTitle
     * @link https://core.telegram.org/bots/api#setchatadministratorcustomtitle
     */
    public function setChatAdministratorCustomTitle(int|string $chat_id, int $user_id, string $custom_title): SetChatAdministratorCustomTitle
    {
        return new SetChatAdministratorCustomTitle($this->_request, $chat_id, $user_id, $custom_title);
    }

    /**
     * Use this method to set a tag for a regular member in a group or a supergroup. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_tags</em> administrator right. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return SetChatMemberTag
     * @link https://core.telegram.org/bots/api#setchatmembertag
     */
    public function setChatMemberTag(int|string $chat_id, int $user_id): SetChatMemberTag
    {
        return new SetChatMemberTag($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to ban a channel chat in a supergroup or a channel. Until the chat is <a href="https://core.telegram.org/bots/api#unbanchatsenderchat">unbanned</a>, the owner of the banned chat won&#39;t be able to send messages on behalf of <strong>any of their channels</strong>. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $sender_chat_id Unique identifier of the target sender chat
     * @return BanChatSenderChat
     * @link https://core.telegram.org/bots/api#banchatsenderchat
     */
    public function banChatSenderChat(int|string $chat_id, int $sender_chat_id): BanChatSenderChat
    {
        return new BanChatSenderChat($this->_request, $chat_id, $sender_chat_id);
    }

    /**
     * Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $sender_chat_id Unique identifier of the target sender chat
     * @return UnbanChatSenderChat
     * @link https://core.telegram.org/bots/api#unbanchatsenderchat
     */
    public function unbanChatSenderChat(int|string $chat_id, int $sender_chat_id): UnbanChatSenderChat
    {
        return new UnbanChatSenderChat($this->_request, $chat_id, $sender_chat_id);
    }

    /**
     * Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the <em>can_restrict_members</em> administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param ChatPermissions $permissions A JSON-serialized object for new default chat permissions
     * @return SetChatPermissions
     * @link https://core.telegram.org/bots/api#setchatpermissions
     */
    public function setChatPermissions(int|string $chat_id, ChatPermissions $permissions): SetChatPermissions
    {
        return new SetChatPermissions($this->_request, $chat_id, $permissions);
    }

    /**
     * Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as <em>String</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return ExportChatInviteLink
     * @link https://core.telegram.org/bots/api#exportchatinvitelink
     */
    public function exportChatInviteLink(int|string $chat_id): ExportChatInviteLink
    {
        return new ExportChatInviteLink($this->_request, $chat_id);
    }

    /**
     * Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method <a href="https://core.telegram.org/bots/api#revokechatinvitelink">revokeChatInviteLink</a>. Returns the new invite link as <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return CreateChatInviteLink
     * @link https://core.telegram.org/bots/api#createchatinvitelink
     */
    public function createChatInviteLink(int|string $chat_id): CreateChatInviteLink
    {
        return new CreateChatInviteLink($this->_request, $chat_id);
    }

    /**
     * Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $invite_link The invite link to edit
     * @return EditChatInviteLink
     * @link https://core.telegram.org/bots/api#editchatinvitelink
     */
    public function editChatInviteLink(int|string $chat_id, string $invite_link): EditChatInviteLink
    {
        return new EditChatInviteLink($this->_request, $chat_id, $invite_link);
    }

    /**
     * Use this method to create a <a href="https://telegram.org/blog/superchannels-star-reactions-subscriptions#star-subscriptions">subscription invite link</a> for a channel chat. The bot must have the <em>can_invite_users</em> administrator rights. The link can be edited using the method <a href="https://core.telegram.org/bots/api#editchatsubscriptioninvitelink">editChatSubscriptionInviteLink</a> or revoked using the method <a href="https://core.telegram.org/bots/api#revokechatinvitelink">revokeChatInviteLink</a>. Returns the new invite link as a <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
     *
     * @param int|string $chat_id Unique identifier for the target channel chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $subscription_period The number of seconds the subscription will be active for before the next payment. Currently, it must always be 2592000 (30 days).
     * @param int $subscription_price The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat; 1-10000
     * @return CreateChatSubscriptionInviteLink
     * @link https://core.telegram.org/bots/api#createchatsubscriptioninvitelink
     */
    public function createChatSubscriptionInviteLink(int|string $chat_id, int $subscription_period, int $subscription_price): CreateChatSubscriptionInviteLink
    {
        return new CreateChatSubscriptionInviteLink($this->_request, $chat_id, $subscription_period, $subscription_price);
    }

    /**
     * Use this method to edit a subscription invite link created by the bot. The bot must have the <em>can_invite_users</em> administrator rights. Returns the edited invite link as a <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $invite_link The invite link to edit
     * @return EditChatSubscriptionInviteLink
     * @link https://core.telegram.org/bots/api#editchatsubscriptioninvitelink
     */
    public function editChatSubscriptionInviteLink(int|string $chat_id, string $invite_link): EditChatSubscriptionInviteLink
    {
        return new EditChatSubscriptionInviteLink($this->_request, $chat_id, $invite_link);
    }

    /**
     * Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
     *
     * @param int|string $chat_id Unique identifier of the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $invite_link The invite link to revoke
     * @return RevokeChatInviteLink
     * @link https://core.telegram.org/bots/api#revokechatinvitelink
     */
    public function revokeChatInviteLink(int|string $chat_id, string $invite_link): RevokeChatInviteLink
    {
        return new RevokeChatInviteLink($this->_request, $chat_id, $invite_link);
    }

    /**
     * Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the <em>can_invite_users</em> administrator right. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return ApproveChatJoinRequest
     * @link https://core.telegram.org/bots/api#approvechatjoinrequest
     */
    public function approveChatJoinRequest(int|string $chat_id, int $user_id): ApproveChatJoinRequest
    {
        return new ApproveChatJoinRequest($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the <em>can_invite_users</em> administrator right. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return DeclineChatJoinRequest
     * @link https://core.telegram.org/bots/api#declinechatjoinrequest
     */
    public function declineChatJoinRequest(int|string $chat_id, int $user_id): DeclineChatJoinRequest
    {
        return new DeclineChatJoinRequest($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to set a new profile photo for the chat. Photos can&#39;t be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $photo New chat photo, uploaded using multipart/form-data
     * @return SetChatPhoto
     * @link https://core.telegram.org/bots/api#setchatphoto
     */
    public function setChatPhoto(int|string $chat_id, mixed $photo): SetChatPhoto
    {
        return new SetChatPhoto($this->_request, $chat_id, $photo);
    }

    /**
     * Use this method to delete a chat photo. Photos can&#39;t be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return DeleteChatPhoto
     * @link https://core.telegram.org/bots/api#deletechatphoto
     */
    public function deleteChatPhoto(int|string $chat_id): DeleteChatPhoto
    {
        return new DeleteChatPhoto($this->_request, $chat_id);
    }

    /**
     * Use this method to change the title of a chat. Titles can&#39;t be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $title New chat title, 1-128 characters
     * @return SetChatTitle
     * @link https://core.telegram.org/bots/api#setchattitle
     */
    public function setChatTitle(int|string $chat_id, string $title): SetChatTitle
    {
        return new SetChatTitle($this->_request, $chat_id, $title);
    }

    /**
     * Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return SetChatDescription
     * @link https://core.telegram.org/bots/api#setchatdescription
     */
    public function setChatDescription(int|string $chat_id): SetChatDescription
    {
        return new SetChatDescription($this->_request, $chat_id);
    }

    /**
     * Use this method to add a message to the list of pinned messages in a chat. In private chats and channel direct messages chats, all non-service messages can be pinned. Conversely, the bot must be an administrator with the &#39;can_pin_messages&#39; right or the &#39;can_edit_messages&#39; right to pin messages in groups and channels respectively. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $message_id Identifier of a message to pin
     * @return PinChatMessage
     * @link https://core.telegram.org/bots/api#pinchatmessage
     */
    public function pinChatMessage(int|string $chat_id, int $message_id): PinChatMessage
    {
        return new PinChatMessage($this->_request, $chat_id, $message_id);
    }

    /**
     * Use this method to remove a message from the list of pinned messages in a chat. In private chats and channel direct messages chats, all messages can be unpinned. Conversely, the bot must be an administrator with the &#39;can_pin_messages&#39; right or the &#39;can_edit_messages&#39; right to unpin messages in groups and channels respectively. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return UnpinChatMessage
     * @link https://core.telegram.org/bots/api#unpinchatmessage
     */
    public function unpinChatMessage(int|string $chat_id): UnpinChatMessage
    {
        return new UnpinChatMessage($this->_request, $chat_id);
    }

    /**
     * Use this method to clear the list of pinned messages in a chat. In private chats and channel direct messages chats, no additional rights are required to unpin all pinned messages. Conversely, the bot must be an administrator with the &#39;can_pin_messages&#39; right or the &#39;can_edit_messages&#39; right to unpin all pinned messages in groups and channels respectively. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return UnpinAllChatMessages
     * @link https://core.telegram.org/bots/api#unpinallchatmessages
     */
    public function unpinAllChatMessages(int|string $chat_id): UnpinAllChatMessages
    {
        return new UnpinAllChatMessages($this->_request, $chat_id);
    }

    /**
     * Use this method for your bot to leave a group, supergroup or channel. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel (in the format <code>@channelusername</code>). Channel direct messages chats aren&#39;t supported; leave the corresponding channel instead.
     * @return LeaveChat
     * @link https://core.telegram.org/bots/api#leavechat
     */
    public function leaveChat(int|string $chat_id): LeaveChat
    {
        return new LeaveChat($this->_request, $chat_id);
    }

    /**
     * Use this method to get up-to-date information about the chat. Returns a <a href="https://core.telegram.org/bots/api#chatfullinfo">ChatFullInfo</a> object on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel (in the format <code>@channelusername</code>)
     * @return GetChat
     * @link https://core.telegram.org/bots/api#getchat
     */
    public function getChat(int|string $chat_id): GetChat
    {
        return new GetChat($this->_request, $chat_id);
    }

    /**
     * Use this method to get a list of administrators in a chat, which aren&#39;t bots. Returns an Array of <a href="https://core.telegram.org/bots/api#chatmember">ChatMember</a> objects.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel (in the format <code>@channelusername</code>)
     * @return GetChatAdministrators
     * @link https://core.telegram.org/bots/api#getchatadministrators
     */
    public function getChatAdministrators(int|string $chat_id): GetChatAdministrators
    {
        return new GetChatAdministrators($this->_request, $chat_id);
    }

    /**
     * Use this method to get the number of members in a chat. Returns <em>Int</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel (in the format <code>@channelusername</code>)
     * @return GetChatMemberCount
     * @link https://core.telegram.org/bots/api#getchatmembercount
     */
    public function getChatMemberCount(int|string $chat_id): GetChatMemberCount
    {
        return new GetChatMemberCount($this->_request, $chat_id);
    }

    /**
     * Use this method to get information about a member of a chat. The method is only guaranteed to work for other users if the bot is an administrator in the chat. Returns a <a href="https://core.telegram.org/bots/api#chatmember">ChatMember</a> object on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return GetChatMember
     * @link https://core.telegram.org/bots/api#getchatmember
     */
    public function getChatMember(int|string $chat_id, int $user_id): GetChatMember
    {
        return new GetChatMember($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field <em>can_set_sticker_set</em> optionally returned in <a href="https://core.telegram.org/bots/api#getchat">getChat</a> requests to check if the bot can use this method. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param string $sticker_set_name Name of the sticker set to be set as the group sticker set
     * @return SetChatStickerSet
     * @link https://core.telegram.org/bots/api#setchatstickerset
     */
    public function setChatStickerSet(int|string $chat_id, string $sticker_set_name): SetChatStickerSet
    {
        return new SetChatStickerSet($this->_request, $chat_id, $sticker_set_name);
    }

    /**
     * Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field <em>can_set_sticker_set</em> optionally returned in <a href="https://core.telegram.org/bots/api#getchat">getChat</a> requests to check if the bot can use this method. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @return DeleteChatStickerSet
     * @link https://core.telegram.org/bots/api#deletechatstickerset
     */
    public function deleteChatStickerSet(int|string $chat_id): DeleteChatStickerSet
    {
        return new DeleteChatStickerSet($this->_request, $chat_id);
    }

    /**
     * Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of <a href="https://core.telegram.org/bots/api#sticker">Sticker</a> objects.
     *
     *

     * @return GetForumTopicIconStickers
     * @link https://core.telegram.org/bots/api#getforumtopiciconstickers
     */
    public function getForumTopicIconStickers(): GetForumTopicIconStickers
    {
        return new GetForumTopicIconStickers($this->_request);
    }

    /**
     * Use this method to create a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator right. Returns information about the created topic as a <a href="https://core.telegram.org/bots/api#forumtopic">ForumTopic</a> object.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param string $name Topic name, 1-128 characters
     * @return CreateForumTopic
     * @link https://core.telegram.org/bots/api#createforumtopic
     */
    public function createForumTopic(int|string $chat_id, string $name): CreateForumTopic
    {
        return new CreateForumTopic($this->_request, $chat_id, $name);
    }

    /**
     * Use this method to edit name and icon of a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights, unless it is the creator of the topic. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
     * @return EditForumTopic
     * @link https://core.telegram.org/bots/api#editforumtopic
     */
    public function editForumTopic(int|string $chat_id, int $message_thread_id): EditForumTopic
    {
        return new EditForumTopic($this->_request, $chat_id, $message_thread_id);
    }

    /**
     * Use this method to close an open topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights, unless it is the creator of the topic. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
     * @return CloseForumTopic
     * @link https://core.telegram.org/bots/api#closeforumtopic
     */
    public function closeForumTopic(int|string $chat_id, int $message_thread_id): CloseForumTopic
    {
        return new CloseForumTopic($this->_request, $chat_id, $message_thread_id);
    }

    /**
     * Use this method to reopen a closed topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights, unless it is the creator of the topic. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
     * @return ReopenForumTopic
     * @link https://core.telegram.org/bots/api#reopenforumtopic
     */
    public function reopenForumTopic(int|string $chat_id, int $message_thread_id): ReopenForumTopic
    {
        return new ReopenForumTopic($this->_request, $chat_id, $message_thread_id);
    }

    /**
     * Use this method to delete a forum topic along with all its messages in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the <em>can_delete_messages</em> administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
     * @return DeleteForumTopic
     * @link https://core.telegram.org/bots/api#deleteforumtopic
     */
    public function deleteForumTopic(int|string $chat_id, int $message_thread_id): DeleteForumTopic
    {
        return new DeleteForumTopic($this->_request, $chat_id, $message_thread_id);
    }

    /**
     * Use this method to clear the list of pinned messages in a forum topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the <em>can_pin_messages</em> administrator right in the supergroup. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
     * @return UnpinAllForumTopicMessages
     * @link https://core.telegram.org/bots/api#unpinallforumtopicmessages
     */
    public function unpinAllForumTopicMessages(int|string $chat_id, int $message_thread_id): UnpinAllForumTopicMessages
    {
        return new UnpinAllForumTopicMessages($this->_request, $chat_id, $message_thread_id);
    }

    /**
     * Use this method to edit the name of the &#39;General&#39; topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @param string $name New topic name, 1-128 characters
     * @return EditGeneralForumTopic
     * @link https://core.telegram.org/bots/api#editgeneralforumtopic
     */
    public function editGeneralForumTopic(int|string $chat_id, string $name): EditGeneralForumTopic
    {
        return new EditGeneralForumTopic($this->_request, $chat_id, $name);
    }

    /**
     * Use this method to close an open &#39;General&#39; topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @return CloseGeneralForumTopic
     * @link https://core.telegram.org/bots/api#closegeneralforumtopic
     */
    public function closeGeneralForumTopic(int|string $chat_id): CloseGeneralForumTopic
    {
        return new CloseGeneralForumTopic($this->_request, $chat_id);
    }

    /**
     * Use this method to reopen a closed &#39;General&#39; topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights. The topic will be automatically unhidden if it was hidden. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @return ReopenGeneralForumTopic
     * @link https://core.telegram.org/bots/api#reopengeneralforumtopic
     */
    public function reopenGeneralForumTopic(int|string $chat_id): ReopenGeneralForumTopic
    {
        return new ReopenGeneralForumTopic($this->_request, $chat_id);
    }

    /**
     * Use this method to hide the &#39;General&#39; topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights. The topic will be automatically closed if it was open. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @return HideGeneralForumTopic
     * @link https://core.telegram.org/bots/api#hidegeneralforumtopic
     */
    public function hideGeneralForumTopic(int|string $chat_id): HideGeneralForumTopic
    {
        return new HideGeneralForumTopic($this->_request, $chat_id);
    }

    /**
     * Use this method to unhide the &#39;General&#39; topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator rights. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @return UnhideGeneralForumTopic
     * @link https://core.telegram.org/bots/api#unhidegeneralforumtopic
     */
    public function unhideGeneralForumTopic(int|string $chat_id): UnhideGeneralForumTopic
    {
        return new UnhideGeneralForumTopic($this->_request, $chat_id);
    }

    /**
     * Use this method to clear the list of pinned messages in a General forum topic. The bot must be an administrator in the chat for this to work and must have the <em>can_pin_messages</em> administrator right in the supergroup. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup (in the format <code>@supergroupusername</code>)
     * @return UnpinAllGeneralForumTopicMessages
     * @link https://core.telegram.org/bots/api#unpinallgeneralforumtopicmessages
     */
    public function unpinAllGeneralForumTopicMessages(int|string $chat_id): UnpinAllGeneralForumTopicMessages
    {
        return new UnpinAllGeneralForumTopicMessages($this->_request, $chat_id);
    }

    /**
     * Use this method to send answers to callback queries sent from <a href="/bots/features#inline-keyboards">inline keyboards</a>. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, <em>True</em> is returned.
     *
     * @param string $callback_query_id Unique identifier for the query to be answered
     * @return AnswerCallbackQuery
     * @link https://core.telegram.org/bots/api#answercallbackquery
     */
    public function answerCallbackQuery(string $callback_query_id): AnswerCallbackQuery
    {
        return new AnswerCallbackQuery($this->_request, $callback_query_id);
    }

    /**
     * Use this method to get the list of boosts added to a chat by a user. Requires administrator rights in the chat. Returns a <a href="https://core.telegram.org/bots/api#userchatboosts">UserChatBoosts</a> object.
     *
     * @param int|string $chat_id Unique identifier for the chat or username of the channel (in the format <code>@channelusername</code>)
     * @param int $user_id Unique identifier of the target user
     * @return GetUserChatBoosts
     * @link https://core.telegram.org/bots/api#getuserchatboosts
     */
    public function getUserChatBoosts(int|string $chat_id, int $user_id): GetUserChatBoosts
    {
        return new GetUserChatBoosts($this->_request, $chat_id, $user_id);
    }

    /**
     * Use this method to get information about the connection of the bot with a business account. Returns a <a href="https://core.telegram.org/bots/api#businessconnection">BusinessConnection</a> object on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @return GetBusinessConnection
     * @link https://core.telegram.org/bots/api#getbusinessconnection
     */
    public function getBusinessConnection(string $business_connection_id): GetBusinessConnection
    {
        return new GetBusinessConnection($this->_request, $business_connection_id);
    }

    /**
     * Use this method to get the token of a managed bot. Returns the token as <em>String</em> on success.
     *
     * @param int $user_id User identifier of the managed bot whose token will be returned
     * @return GetManagedBotToken
     * @link https://core.telegram.org/bots/api#getmanagedbottoken
     */
    public function getManagedBotToken(int $user_id): GetManagedBotToken
    {
        return new GetManagedBotToken($this->_request, $user_id);
    }

    /**
     * Use this method to revoke the current token of a managed bot and generate a new one. Returns the new token as <em>String</em> on success.
     *
     * @param int $user_id User identifier of the managed bot whose token will be replaced
     * @return ReplaceManagedBotToken
     * @link https://core.telegram.org/bots/api#replacemanagedbottoken
     */
    public function replaceManagedBotToken(int $user_id): ReplaceManagedBotToken
    {
        return new ReplaceManagedBotToken($this->_request, $user_id);
    }

    /**
     * Use this method to change the list of the bot&#39;s commands. See <a href="/bots/features#commands">this manual</a> for more details about bot commands. Returns <em>True</em> on success.
     *
     * @param string  $commands A JSON-serialized list of bot commands to be set as the list of the bot&#39;s commands. At most 100 commands can be specified.
     * @return SetMyCommands
     * @link https://core.telegram.org/bots/api#setmycommands
     */
    public function setMyCommands(string  $commands): SetMyCommands
    {
        return new SetMyCommands($this->_request, $commands);
    }

    /**
     * Use this method to delete the list of the bot&#39;s commands for the given scope and user language. After deletion, <a href="https://core.telegram.org/bots/api#determining-list-of-commands">higher level commands</a> will be shown to affected users. Returns <em>True</em> on success.
     *
     *

     * @return DeleteMyCommands
     * @link https://core.telegram.org/bots/api#deletemycommands
     */
    public function deleteMyCommands(): DeleteMyCommands
    {
        return new DeleteMyCommands($this->_request);
    }

    /**
     * Use this method to get the current list of the bot&#39;s commands for the given scope and user language. Returns an Array of <a href="https://core.telegram.org/bots/api#botcommand">BotCommand</a> objects. If commands aren&#39;t set, an empty list is returned.
     *
     *

     * @return GetMyCommands
     * @link https://core.telegram.org/bots/api#getmycommands
     */
    public function getMyCommands(): GetMyCommands
    {
        return new GetMyCommands($this->_request);
    }

    /**
     * Use this method to change the bot&#39;s name. Returns <em>True</em> on success.
     *
     *

     * @return SetMyName
     * @link https://core.telegram.org/bots/api#setmyname
     */
    public function setMyName(): SetMyName
    {
        return new SetMyName($this->_request);
    }

    /**
     * Use this method to get the current bot name for the given user language. Returns <a href="https://core.telegram.org/bots/api#botname">BotName</a> on success.
     *
     *

     * @return GetMyName
     * @link https://core.telegram.org/bots/api#getmyname
     */
    public function getMyName(): GetMyName
    {
        return new GetMyName($this->_request);
    }

    /**
     * Use this method to change the bot&#39;s description, which is shown in the chat with the bot if the chat is empty. Returns <em>True</em> on success.
     *
     *

     * @return SetMyDescription
     * @link https://core.telegram.org/bots/api#setmydescription
     */
    public function setMyDescription(): SetMyDescription
    {
        return new SetMyDescription($this->_request);
    }

    /**
     * Use this method to get the current bot description for the given user language. Returns <a href="https://core.telegram.org/bots/api#botdescription">BotDescription</a> on success.
     *
     *

     * @return GetMyDescription
     * @link https://core.telegram.org/bots/api#getmydescription
     */
    public function getMyDescription(): GetMyDescription
    {
        return new GetMyDescription($this->_request);
    }

    /**
     * Use this method to change the bot&#39;s short description, which is shown on the bot&#39;s profile page and is sent together with the link when users share the bot. Returns <em>True</em> on success.
     *
     *

     * @return SetMyShortDescription
     * @link https://core.telegram.org/bots/api#setmyshortdescription
     */
    public function setMyShortDescription(): SetMyShortDescription
    {
        return new SetMyShortDescription($this->_request);
    }

    /**
     * Use this method to get the current bot short description for the given user language. Returns <a href="https://core.telegram.org/bots/api#botshortdescription">BotShortDescription</a> on success.
     *
     *

     * @return GetMyShortDescription
     * @link https://core.telegram.org/bots/api#getmyshortdescription
     */
    public function getMyShortDescription(): GetMyShortDescription
    {
        return new GetMyShortDescription($this->_request);
    }

    /**
     * Changes the profile photo of the bot. Returns <em>True</em> on success.
     *
     * @param InputProfilePhoto $photo The new profile photo to set
     * @return SetMyProfilePhoto
     * @link https://core.telegram.org/bots/api#setmyprofilephoto
     */
    public function setMyProfilePhoto(InputProfilePhoto $photo): SetMyProfilePhoto
    {
        return new SetMyProfilePhoto($this->_request, $photo);
    }

    /**
     * Removes the profile photo of the bot. Requires no parameters. Returns <em>True</em> on success.
     *
     *

     * @return RemoveMyProfilePhoto
     * @link https://core.telegram.org/bots/api#removemyprofilephoto
     */
    public function removeMyProfilePhoto(): RemoveMyProfilePhoto
    {
        return new RemoveMyProfilePhoto($this->_request);
    }

    /**
     * Use this method to change the bot&#39;s menu button in a private chat, or the default menu button. Returns <em>True</em> on success.
     *
     *

     * @return SetChatMenuButton
     * @link https://core.telegram.org/bots/api#setchatmenubutton
     */
    public function setChatMenuButton(): SetChatMenuButton
    {
        return new SetChatMenuButton($this->_request);
    }

    /**
     * Use this method to get the current value of the bot&#39;s menu button in a private chat, or the default menu button. Returns <a href="https://core.telegram.org/bots/api#menubutton">MenuButton</a> on success.
     *
     *

     * @return GetChatMenuButton
     * @link https://core.telegram.org/bots/api#getchatmenubutton
     */
    public function getChatMenuButton(): GetChatMenuButton
    {
        return new GetChatMenuButton($this->_request);
    }

    /**
     * Use this method to change the default administrator rights requested by the bot when it&#39;s added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns <em>True</em> on success.
     *
     *

     * @return SetMyDefaultAdministratorRights
     * @link https://core.telegram.org/bots/api#setmydefaultadministratorrights
     */
    public function setMyDefaultAdministratorRights(): SetMyDefaultAdministratorRights
    {
        return new SetMyDefaultAdministratorRights($this->_request);
    }

    /**
     * Use this method to get the current default administrator rights of the bot. Returns <a href="https://core.telegram.org/bots/api#chatadministratorrights">ChatAdministratorRights</a> on success.
     *
     *

     * @return GetMyDefaultAdministratorRights
     * @link https://core.telegram.org/bots/api#getmydefaultadministratorrights
     */
    public function getMyDefaultAdministratorRights(): GetMyDefaultAdministratorRights
    {
        return new GetMyDefaultAdministratorRights($this->_request);
    }

    /**
     * Returns the list of gifts that can be sent by the bot to users and channel chats. Requires no parameters. Returns a <a href="https://core.telegram.org/bots/api#gifts">Gifts</a> object.
     *
     *

     * @return GetAvailableGifts
     * @link https://core.telegram.org/bots/api#getavailablegifts
     */
    public function getAvailableGifts(): GetAvailableGifts
    {
        return new GetAvailableGifts($this->_request);
    }

    /**
     * Sends a gift to the given user or channel chat. The gift can&#39;t be converted to Telegram Stars by the receiver. Returns <em>True</em> on success.
     *
     * @param string $gift_id Identifier of the gift; limited gifts can&#39;t be sent to channel chats
     * @return SendGift
     * @link https://core.telegram.org/bots/api#sendgift
     */
    public function sendGift(string $gift_id): SendGift
    {
        return new SendGift($this->_request, $gift_id);
    }

    /**
     * Gifts a Telegram Premium subscription to the given user. Returns <em>True</em> on success.
     *
     * @param int $user_id Unique identifier of the target user who will receive a Telegram Premium subscription
     * @param int $month_count Number of months the Telegram Premium subscription will be active for the user; must be one of 3, 6, or 12
     * @param int $star_count Number of Telegram Stars to pay for the Telegram Premium subscription; must be 1000 for 3 months, 1500 for 6 months, and 2500 for 12 months
     * @return GiftPremiumSubscription
     * @link https://core.telegram.org/bots/api#giftpremiumsubscription
     */
    public function giftPremiumSubscription(int $user_id, int $month_count, int $star_count): GiftPremiumSubscription
    {
        return new GiftPremiumSubscription($this->_request, $user_id, $month_count, $star_count);
    }

    /**
     * Verifies a user <a href="https://telegram.org/verify#third-party-verification">on behalf of the organization</a> which is represented by the bot. Returns <em>True</em> on success.
     *
     * @param int $user_id Unique identifier of the target user
     * @return VerifyUser
     * @link https://core.telegram.org/bots/api#verifyuser
     */
    public function verifyUser(int $user_id): VerifyUser
    {
        return new VerifyUser($this->_request, $user_id);
    }

    /**
     * Verifies a chat <a href="https://telegram.org/verify#third-party-verification">on behalf of the organization</a> which is represented by the bot. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>). Channel direct messages chats can&#39;t be verified.
     * @return VerifyChat
     * @link https://core.telegram.org/bots/api#verifychat
     */
    public function verifyChat(int|string $chat_id): VerifyChat
    {
        return new VerifyChat($this->_request, $chat_id);
    }

    /**
     * Removes verification from a user who is currently verified <a href="https://telegram.org/verify#third-party-verification">on behalf of the organization</a> represented by the bot. Returns <em>True</em> on success.
     *
     * @param int $user_id Unique identifier of the target user
     * @return RemoveUserVerification
     * @link https://core.telegram.org/bots/api#removeuserverification
     */
    public function removeUserVerification(int $user_id): RemoveUserVerification
    {
        return new RemoveUserVerification($this->_request, $user_id);
    }

    /**
     * Removes verification from a chat that is currently verified <a href="https://telegram.org/verify#third-party-verification">on behalf of the organization</a> represented by the bot. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return RemoveChatVerification
     * @link https://core.telegram.org/bots/api#removechatverification
     */
    public function removeChatVerification(int|string $chat_id): RemoveChatVerification
    {
        return new RemoveChatVerification($this->_request, $chat_id);
    }

    /**
     * Marks incoming message as read on behalf of a business account. Requires the <em>can_read_messages</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection on behalf of which to read the message
     * @param int $chat_id Unique identifier of the chat in which the message was received. The chat must have been active in the last 24 hours.
     * @param int $message_id Unique identifier of the message to mark as read
     * @return ReadBusinessMessage
     * @link https://core.telegram.org/bots/api#readbusinessmessage
     */
    public function readBusinessMessage(string $business_connection_id, int $chat_id, int $message_id): ReadBusinessMessage
    {
        return new ReadBusinessMessage($this->_request, $business_connection_id, $chat_id, $message_id);
    }

    /**
     * Delete messages on behalf of a business account. Requires the <em>can_delete_sent_messages</em> business bot right to delete messages sent by the bot itself, or the <em>can_delete_all_messages</em> business bot right to delete any message. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection on behalf of which to delete the messages
     * @param string  $message_ids A JSON-serialized list of 1-100 identifiers of messages to delete. All messages must be from the same chat. See <a href="https://core.telegram.org/bots/api#deletemessage">deleteMessage</a> for limitations on which messages can be deleted
     * @return DeleteBusinessMessages
     * @link https://core.telegram.org/bots/api#deletebusinessmessages
     */
    public function deleteBusinessMessages(string $business_connection_id, string  $message_ids): DeleteBusinessMessages
    {
        return new DeleteBusinessMessages($this->_request, $business_connection_id, $message_ids);
    }

    /**
     * Changes the first and last name of a managed business account. Requires the <em>can_change_name</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param string $first_name The new value of the first name for the business account; 1-64 characters
     * @return SetBusinessAccountName
     * @link https://core.telegram.org/bots/api#setbusinessaccountname
     */
    public function setBusinessAccountName(string $business_connection_id, string $first_name): SetBusinessAccountName
    {
        return new SetBusinessAccountName($this->_request, $business_connection_id, $first_name);
    }

    /**
     * Changes the username of a managed business account. Requires the <em>can_change_username</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @return SetBusinessAccountUsername
     * @link https://core.telegram.org/bots/api#setbusinessaccountusername
     */
    public function setBusinessAccountUsername(string $business_connection_id): SetBusinessAccountUsername
    {
        return new SetBusinessAccountUsername($this->_request, $business_connection_id);
    }

    /**
     * Changes the bio of a managed business account. Requires the <em>can_change_bio</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @return SetBusinessAccountBio
     * @link https://core.telegram.org/bots/api#setbusinessaccountbio
     */
    public function setBusinessAccountBio(string $business_connection_id): SetBusinessAccountBio
    {
        return new SetBusinessAccountBio($this->_request, $business_connection_id);
    }

    /**
     * Changes the profile photo of a managed business account. Requires the <em>can_edit_profile_photo</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param InputProfilePhoto $photo The new profile photo to set
     * @return SetBusinessAccountProfilePhoto
     * @link https://core.telegram.org/bots/api#setbusinessaccountprofilephoto
     */
    public function setBusinessAccountProfilePhoto(string $business_connection_id, InputProfilePhoto $photo): SetBusinessAccountProfilePhoto
    {
        return new SetBusinessAccountProfilePhoto($this->_request, $business_connection_id, $photo);
    }

    /**
     * Removes the current profile photo of a managed business account. Requires the <em>can_edit_profile_photo</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @return RemoveBusinessAccountProfilePhoto
     * @link https://core.telegram.org/bots/api#removebusinessaccountprofilephoto
     */
    public function removeBusinessAccountProfilePhoto(string $business_connection_id): RemoveBusinessAccountProfilePhoto
    {
        return new RemoveBusinessAccountProfilePhoto($this->_request, $business_connection_id);
    }

    /**
     * Changes the privacy settings pertaining to incoming gifts in a managed business account. Requires the <em>can_change_gift_settings</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param bool $show_gift_button Pass <em>True</em>, if a button for sending a gift to the user or by the business account must always be shown in the input field
     * @param AcceptedGiftTypes $accepted_gift_types Types of gifts accepted by the business account
     * @return SetBusinessAccountGiftSettings
     * @link https://core.telegram.org/bots/api#setbusinessaccountgiftsettings
     */
    public function setBusinessAccountGiftSettings(string $business_connection_id, bool $show_gift_button, AcceptedGiftTypes $accepted_gift_types): SetBusinessAccountGiftSettings
    {
        return new SetBusinessAccountGiftSettings($this->_request, $business_connection_id, $show_gift_button, $accepted_gift_types);
    }

    /**
     * Returns the amount of Telegram Stars owned by a managed business account. Requires the <em>can_view_gifts_and_stars</em> business bot right. Returns <a href="https://core.telegram.org/bots/api#staramount">StarAmount</a> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @return GetBusinessAccountStarBalance
     * @link https://core.telegram.org/bots/api#getbusinessaccountstarbalance
     */
    public function getBusinessAccountStarBalance(string $business_connection_id): GetBusinessAccountStarBalance
    {
        return new GetBusinessAccountStarBalance($this->_request, $business_connection_id);
    }

    /**
     * Transfers Telegram Stars from the business account balance to the bot&#39;s balance. Requires the <em>can_transfer_stars</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param int $star_count Number of Telegram Stars to transfer; 1-10000
     * @return TransferBusinessAccountStars
     * @link https://core.telegram.org/bots/api#transferbusinessaccountstars
     */
    public function transferBusinessAccountStars(string $business_connection_id, int $star_count): TransferBusinessAccountStars
    {
        return new TransferBusinessAccountStars($this->_request, $business_connection_id, $star_count);
    }

    /**
     * Returns the gifts received and owned by a managed business account. Requires the <em>can_view_gifts_and_stars</em> business bot right. Returns <a href="https://core.telegram.org/bots/api#ownedgifts">OwnedGifts</a> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @return GetBusinessAccountGifts
     * @link https://core.telegram.org/bots/api#getbusinessaccountgifts
     */
    public function getBusinessAccountGifts(string $business_connection_id): GetBusinessAccountGifts
    {
        return new GetBusinessAccountGifts($this->_request, $business_connection_id);
    }

    /**
     * Returns the gifts owned and hosted by a user. Returns <a href="https://core.telegram.org/bots/api#ownedgifts">OwnedGifts</a> on success.
     *
     * @param int $user_id Unique identifier of the user
     * @return GetUserGifts
     * @link https://core.telegram.org/bots/api#getusergifts
     */
    public function getUserGifts(int $user_id): GetUserGifts
    {
        return new GetUserGifts($this->_request, $user_id);
    }

    /**
     * Returns the gifts owned by a chat. Returns <a href="https://core.telegram.org/bots/api#ownedgifts">OwnedGifts</a> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @return GetChatGifts
     * @link https://core.telegram.org/bots/api#getchatgifts
     */
    public function getChatGifts(int|string $chat_id): GetChatGifts
    {
        return new GetChatGifts($this->_request, $chat_id);
    }

    /**
     * Converts a given regular gift to Telegram Stars. Requires the <em>can_convert_gifts_to_stars</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param string $owned_gift_id Unique identifier of the regular gift that should be converted to Telegram Stars
     * @return ConvertGiftToStars
     * @link https://core.telegram.org/bots/api#convertgifttostars
     */
    public function convertGiftToStars(string $business_connection_id, string $owned_gift_id): ConvertGiftToStars
    {
        return new ConvertGiftToStars($this->_request, $business_connection_id, $owned_gift_id);
    }

    /**
     * Upgrades a given regular gift to a unique gift. Requires the <em>can_transfer_and_upgrade_gifts</em> business bot right. Additionally requires the <em>can_transfer_stars</em> business bot right if the upgrade is paid. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param string $owned_gift_id Unique identifier of the regular gift that should be upgraded to a unique one
     * @return UpgradeGift
     * @link https://core.telegram.org/bots/api#upgradegift
     */
    public function upgradeGift(string $business_connection_id, string $owned_gift_id): UpgradeGift
    {
        return new UpgradeGift($this->_request, $business_connection_id, $owned_gift_id);
    }

    /**
     * Transfers an owned unique gift to another user. Requires the <em>can_transfer_and_upgrade_gifts</em> business bot right. Requires <em>can_transfer_stars</em> business bot right if the transfer is paid. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param string $owned_gift_id Unique identifier of the regular gift that should be transferred
     * @param int $new_owner_chat_id Unique identifier of the chat which will own the gift. The chat must be active in the last 24 hours.
     * @return TransferGift
     * @link https://core.telegram.org/bots/api#transfergift
     */
    public function transferGift(string $business_connection_id, string $owned_gift_id, int $new_owner_chat_id): TransferGift
    {
        return new TransferGift($this->_request, $business_connection_id, $owned_gift_id, $new_owner_chat_id);
    }

    /**
     * Posts a story on behalf of a managed business account. Requires the <em>can_manage_stories</em> business bot right. Returns <a href="https://core.telegram.org/bots/api#story">Story</a> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param InputStoryContent $content Content of the story
     * @param int $active_period Period after which the story is moved to the archive, in seconds; must be one of <code>6 * 3600</code>, <code>12 * 3600</code>, <code>86400</code>, or <code>2 * 86400</code>
     * @return PostStory
     * @link https://core.telegram.org/bots/api#poststory
     */
    public function postStory(string $business_connection_id, InputStoryContent $content, int $active_period): PostStory
    {
        return new PostStory($this->_request, $business_connection_id, $content, $active_period);
    }

    /**
     * Reposts a story on behalf of a business account from another business account. Both business accounts must be managed by the same bot, and the story on the source account must have been posted (or reposted) by the bot. Requires the <em>can_manage_stories</em> business bot right for both business accounts. Returns <a href="https://core.telegram.org/bots/api#story">Story</a> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param int $from_chat_id Unique identifier of the chat which posted the story that should be reposted
     * @param int $from_story_id Unique identifier of the story that should be reposted
     * @param int $active_period Period after which the story is moved to the archive, in seconds; must be one of <code>6 * 3600</code>, <code>12 * 3600</code>, <code>86400</code>, or <code>2 * 86400</code>
     * @return RepostStory
     * @link https://core.telegram.org/bots/api#repoststory
     */
    public function repostStory(string $business_connection_id, int $from_chat_id, int $from_story_id, int $active_period): RepostStory
    {
        return new RepostStory($this->_request, $business_connection_id, $from_chat_id, $from_story_id, $active_period);
    }

    /**
     * Edits a story previously posted by the bot on behalf of a managed business account. Requires the <em>can_manage_stories</em> business bot right. Returns <a href="https://core.telegram.org/bots/api#story">Story</a> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param int $story_id Unique identifier of the story to edit
     * @param InputStoryContent $content Content of the story
     * @return EditStory
     * @link https://core.telegram.org/bots/api#editstory
     */
    public function editStory(string $business_connection_id, int $story_id, InputStoryContent $content): EditStory
    {
        return new EditStory($this->_request, $business_connection_id, $story_id, $content);
    }

    /**
     * Deletes a story previously posted by the bot on behalf of a managed business account. Requires the <em>can_manage_stories</em> business bot right. Returns <em>True</em> on success.
     *
     * @param string $business_connection_id Unique identifier of the business connection
     * @param int $story_id Unique identifier of the story to delete
     * @return DeleteStory
     * @link https://core.telegram.org/bots/api#deletestory
     */
    public function deleteStory(string $business_connection_id, int $story_id): DeleteStory
    {
        return new DeleteStory($this->_request, $business_connection_id, $story_id);
    }

    /**
     * Use this method to set the result of an interaction with a <a href="/bots/webapps">Web App</a> and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a <a href="https://core.telegram.org/bots/api#sentwebappmessage">SentWebAppMessage</a> object is returned.
     *
     * @param string $web_app_query_id Unique identifier for the query to be answered
     * @param InlineQueryResult $result A JSON-serialized object describing the message to be sent
     * @return AnswerWebAppQuery
     * @link https://core.telegram.org/bots/api#answerwebappquery
     */
    public function answerWebAppQuery(string $web_app_query_id, InlineQueryResult $result): AnswerWebAppQuery
    {
        return new AnswerWebAppQuery($this->_request, $web_app_query_id, $result);
    }

    /**
     * Stores a message that can be sent by a user of a Mini App. Returns a <a href="https://core.telegram.org/bots/api#preparedinlinemessage">PreparedInlineMessage</a> object.
     *
     * @param int $user_id Unique identifier of the target user that can use the prepared message
     * @param InlineQueryResult $result A JSON-serialized object describing the message to be sent
     * @return SavePreparedInlineMessage
     * @link https://core.telegram.org/bots/api#savepreparedinlinemessage
     */
    public function savePreparedInlineMessage(int $user_id, InlineQueryResult $result): SavePreparedInlineMessage
    {
        return new SavePreparedInlineMessage($this->_request, $user_id, $result);
    }

    /**
     * Stores a keyboard button that can be used by a user within a Mini App. Returns a <a href="https://core.telegram.org/bots/api#preparedkeyboardbutton">PreparedKeyboardButton</a> object.
     *
     * @param int $user_id Unique identifier of the target user that can use the button
     * @param KeyboardButton $button A JSON-serialized object describing the button to be saved. The button must be of the type <em>request_users</em>, <em>request_chat</em>, or <em>request_managed_bot</em>
     * @return SavePreparedKeyboardButton
     * @link https://core.telegram.org/bots/api#savepreparedkeyboardbutton
     */
    public function savePreparedKeyboardButton(int $user_id, KeyboardButton $button): SavePreparedKeyboardButton
    {
        return new SavePreparedKeyboardButton($this->_request, $user_id, $button);
    }

    /**
     * Use this method to edit text and <a href="https://core.telegram.org/bots/api#games">game</a> messages. On success, if the edited message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within <strong>48 hours</strong> from the time they were sent.
     *
     * @param string $text New text of the message, 1-4096 characters after entities parsing
     * @return EditMessageText
     * @link https://core.telegram.org/bots/api#editmessagetext
     */
    public function editMessageText(string $text): EditMessageText
    {
        return new EditMessageText($this->_request, $text);
    }

    /**
     * Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within <strong>48 hours</strong> from the time they were sent.
     *
     *

     * @return EditMessageCaption
     * @link https://core.telegram.org/bots/api#editmessagecaption
     */
    public function editMessageCaption(): EditMessageCaption
    {
        return new EditMessageCaption($this->_request);
    }

    /**
     * Use this method to edit animation, audio, document, photo, or video messages, or to add media to text messages. If a message is part of a message album, then it can be edited only to an audio for audio albums, only to a document for document albums and to a photo or a video otherwise. When an inline message is edited, a new file can&#39;t be uploaded; use a previously uploaded file via its file_id or specify a URL. On success, if the edited message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within <strong>48 hours</strong> from the time they were sent.
     *
     * @param InputMedia $media A JSON-serialized object for a new media content of the message
     * @return EditMessageMedia
     * @link https://core.telegram.org/bots/api#editmessagemedia
     */
    public function editMessageMedia(InputMedia $media): EditMessageMedia
    {
        return new EditMessageMedia($this->_request, $media);
    }

    /**
     * Use this method to edit live location messages. A location can be edited until its <em>live_period</em> expires or editing is explicitly disabled by a call to <a href="https://core.telegram.org/bots/api#stopmessagelivelocation">stopMessageLiveLocation</a>. On success, if the edited message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned.
     *
     * @param Float $latitude Latitude of new location
     * @param Float $longitude Longitude of new location
     * @return EditMessageLiveLocation
     * @link https://core.telegram.org/bots/api#editmessagelivelocation
     */
    public function editMessageLiveLocation(Float $latitude, Float $longitude): EditMessageLiveLocation
    {
        return new EditMessageLiveLocation($this->_request, $latitude, $longitude);
    }

    /**
     * Use this method to stop updating a live location message before <em>live_period</em> expires. On success, if the message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned.
     *
     *

     * @return StopMessageLiveLocation
     * @link https://core.telegram.org/bots/api#stopmessagelivelocation
     */
    public function stopMessageLiveLocation(): StopMessageLiveLocation
    {
        return new StopMessageLiveLocation($this->_request);
    }

    /**
     * Use this method to edit a checklist on behalf of a connected business account. On success, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param string $business_connection_id Unique identifier of the business connection on behalf of which the message will be sent
     * @param int $chat_id Unique identifier for the target chat
     * @param int $message_id Unique identifier for the target message
     * @param InputChecklist $checklist A JSON-serialized object for the new checklist
     * @return EditMessageChecklist
     * @link https://core.telegram.org/bots/api#editmessagechecklist
     */
    public function editMessageChecklist(string $business_connection_id, int $chat_id, int $message_id, InputChecklist $checklist): EditMessageChecklist
    {
        return new EditMessageChecklist($this->_request, $business_connection_id, $chat_id, $message_id, $checklist);
    }

    /**
     * Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within <strong>48 hours</strong> from the time they were sent.
     *
     *

     * @return EditMessageReplyMarkup
     * @link https://core.telegram.org/bots/api#editmessagereplymarkup
     */
    public function editMessageReplyMarkup(): EditMessageReplyMarkup
    {
        return new EditMessageReplyMarkup($this->_request);
    }

    /**
     * Use this method to stop a poll which was sent by the bot. On success, the stopped <a href="https://core.telegram.org/bots/api#poll">Poll</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $message_id Identifier of the original message with the poll
     * @return StopPoll
     * @link https://core.telegram.org/bots/api#stoppoll
     */
    public function stopPoll(int|string $chat_id, int $message_id): StopPoll
    {
        return new StopPoll($this->_request, $chat_id, $message_id);
    }

    /**
     * Use this method to approve a suggested post in a direct messages chat. The bot must have the &#39;can_post_messages&#39; administrator right in the corresponding channel chat. Returns <em>True</em> on success.
     *
     * @param int $chat_id Unique identifier for the target direct messages chat
     * @param int $message_id Identifier of a suggested post message to approve
     * @return ApproveSuggestedPost
     * @link https://core.telegram.org/bots/api#approvesuggestedpost
     */
    public function approveSuggestedPost(int $chat_id, int $message_id): ApproveSuggestedPost
    {
        return new ApproveSuggestedPost($this->_request, $chat_id, $message_id);
    }

    /**
     * Use this method to decline a suggested post in a direct messages chat. The bot must have the &#39;can_manage_direct_messages&#39; administrator right in the corresponding channel chat. Returns <em>True</em> on success.
     *
     * @param int $chat_id Unique identifier for the target direct messages chat
     * @param int $message_id Identifier of a suggested post message to decline
     * @return DeclineSuggestedPost
     * @link https://core.telegram.org/bots/api#declinesuggestedpost
     */
    public function declineSuggestedPost(int $chat_id, int $message_id): DeclineSuggestedPost
    {
        return new DeclineSuggestedPost($this->_request, $chat_id, $message_id);
    }

    /**
     * Use this method to delete a message, including service messages, with the following limitations:<br>- A message can only be deleted if it was sent less than 48 hours ago.<br>- Service messages about a supergroup, channel, or forum topic creation can&#39;t be deleted.<br>- A dice message in a private chat can only be deleted if it was sent more than 24 hours ago.<br>- Bots can delete outgoing messages in private chats, groups, and supergroups.<br>- Bots can delete incoming messages in private chats.<br>- Bots granted <em>can_post_messages</em> permissions can delete outgoing messages in channels.<br>- If the bot is an administrator of a group, it can delete any message there.<br>- If the bot has <em>can_delete_messages</em> administrator right in a supergroup or a channel, it can delete any message there.<br>- If the bot has <em>can_manage_direct_messages</em> administrator right in a channel, it can delete any message in the corresponding direct messages chat.<br>Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param int $message_id Identifier of the message to delete
     * @return DeleteMessage
     * @link https://core.telegram.org/bots/api#deletemessage
     */
    public function deleteMessage(int|string $chat_id, int $message_id): DeleteMessage
    {
        return new DeleteMessage($this->_request, $chat_id, $message_id);
    }

    /**
     * Use this method to delete multiple messages simultaneously. If some of the specified messages can&#39;t be found, they are skipped. Returns <em>True</em> on success.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string  $message_ids A JSON-serialized list of 1-100 identifiers of messages to delete. See <a href="https://core.telegram.org/bots/api#deletemessage">deleteMessage</a> for limitations on which messages can be deleted
     * @return DeleteMessages
     * @link https://core.telegram.org/bots/api#deletemessages
     */
    public function deleteMessages(int|string $chat_id, string  $message_ids): DeleteMessages
    {
        return new DeleteMessages($this->_request, $chat_id, $message_ids);
    }

    /**
     * Use this method to send static .WEBP, <a href="https://telegram.org/blog/animated-stickers">animated</a> .TGS, or <a href="https://telegram.org/blog/video-stickers-better-reactions">video</a> .WEBM stickers. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param mixed $sticker Sticker to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a .WEBP sticker from the Internet, or upload a new .WEBP, .TGS, or .WEBM sticker using multipart/form-data. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>. Video and animated stickers can&#39;t be sent via an HTTP URL.
     * @return SendSticker
     * @link https://core.telegram.org/bots/api#sendsticker
     */
    public function sendSticker(int|string $chat_id, mixed $sticker): SendSticker
    {
        return new SendSticker($this->_request, $chat_id, $sticker);
    }

    /**
     * Use this method to get a sticker set. On success, a <a href="https://core.telegram.org/bots/api#stickerset">StickerSet</a> object is returned.
     *
     * @param string $name Name of the sticker set
     * @return GetStickerSet
     * @link https://core.telegram.org/bots/api#getstickerset
     */
    public function getStickerSet(string $name): GetStickerSet
    {
        return new GetStickerSet($this->_request, $name);
    }

    /**
     * Use this method to get information about custom emoji stickers by their identifiers. Returns an Array of <a href="https://core.telegram.org/bots/api#sticker">Sticker</a> objects.
     *
     * @param string  $custom_emoji_ids A JSON-serialized list of custom emoji identifiers. At most 200 custom emoji identifiers can be specified.
     * @return GetCustomEmojiStickers
     * @link https://core.telegram.org/bots/api#getcustomemojistickers
     */
    public function getCustomEmojiStickers(string  $custom_emoji_ids): GetCustomEmojiStickers
    {
        return new GetCustomEmojiStickers($this->_request, $custom_emoji_ids);
    }

    /**
     * Use this method to upload a file with a sticker for later use in the <a href="https://core.telegram.org/bots/api#createnewstickerset">createNewStickerSet</a>, <a href="https://core.telegram.org/bots/api#addstickertoset">addStickerToSet</a>, or <a href="https://core.telegram.org/bots/api#replacestickerinset">replaceStickerInSet</a> methods (the file can be used multiple times). Returns the uploaded <a href="https://core.telegram.org/bots/api#file">File</a> on success.
     *
     * @param int $user_id User identifier of sticker file owner
     * @param mixed $sticker A file with the sticker in .WEBP, .PNG, .TGS, or .WEBM format. See <a href="/stickers"><a href="https://core.telegram.org/stickers">https://core.telegram.org/stickers</a></a> for technical requirements. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @param string $sticker_format Format of the sticker, must be one of “static”, “animated”, “video”
     * @return UploadStickerFile
     * @link https://core.telegram.org/bots/api#uploadstickerfile
     */
    public function uploadStickerFile(int $user_id, mixed $sticker, string $sticker_format): UploadStickerFile
    {
        return new UploadStickerFile($this->_request, $user_id, $sticker, $sticker_format);
    }

    /**
     * Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns <em>True</em> on success.
     *
     * @param int $user_id User identifier of created sticker set owner
     * @param string $name Short name of sticker set, to be used in <code>t.me/addstickers/</code> URLs (e.g., <em>animals</em>). Can contain only English letters, digits and underscores. Must begin with a letter, can&#39;t contain consecutive underscores and must end in <code>&quot;_by_&lt;bot_username&gt;&quot;</code>. <code>&lt;bot_username&gt;</code> is case insensitive. 1-64 characters.
     * @param string $title Sticker set title, 1-64 characters
     * @param string  $stickers A JSON-serialized list of 1-50 initial stickers to be added to the sticker set
     * @return CreateNewStickerSet
     * @link https://core.telegram.org/bots/api#createnewstickerset
     */
    public function createNewStickerSet(int $user_id, string $name, string $title, string  $stickers): CreateNewStickerSet
    {
        return new CreateNewStickerSet($this->_request, $user_id, $name, $title, $stickers);
    }

    /**
     * Use this method to add a new sticker to a set created by the bot. Emoji sticker sets can have up to 200 stickers. Other sticker sets can have up to 120 stickers. Returns <em>True</em> on success.
     *
     * @param int $user_id User identifier of sticker set owner
     * @param string $name Sticker set name
     * @param InputSticker $sticker A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set isn&#39;t changed.
     * @return AddStickerToSet
     * @link https://core.telegram.org/bots/api#addstickertoset
     */
    public function addStickerToSet(int $user_id, string $name, InputSticker $sticker): AddStickerToSet
    {
        return new AddStickerToSet($this->_request, $user_id, $name, $sticker);
    }

    /**
     * Use this method to move a sticker in a set created by the bot to a specific position. Returns <em>True</em> on success.
     *
     * @param string $sticker File identifier of the sticker
     * @param int $position New sticker position in the set, zero-based
     * @return SetStickerPositionInSet
     * @link https://core.telegram.org/bots/api#setstickerpositioninset
     */
    public function setStickerPositionInSet(string $sticker, int $position): SetStickerPositionInSet
    {
        return new SetStickerPositionInSet($this->_request, $sticker, $position);
    }

    /**
     * Use this method to delete a sticker from a set created by the bot. Returns <em>True</em> on success.
     *
     * @param string $sticker File identifier of the sticker
     * @return DeleteStickerFromSet
     * @link https://core.telegram.org/bots/api#deletestickerfromset
     */
    public function deleteStickerFromSet(string $sticker): DeleteStickerFromSet
    {
        return new DeleteStickerFromSet($this->_request, $sticker);
    }

    /**
     * Use this method to replace an existing sticker in a sticker set with a new one. The method is equivalent to calling <a href="https://core.telegram.org/bots/api#deletestickerfromset">deleteStickerFromSet</a>, then <a href="https://core.telegram.org/bots/api#addstickertoset">addStickerToSet</a>, then <a href="https://core.telegram.org/bots/api#setstickerpositioninset">setStickerPositionInSet</a>. Returns <em>True</em> on success.
     *
     * @param int $user_id User identifier of the sticker set owner
     * @param string $name Sticker set name
     * @param string $old_sticker File identifier of the replaced sticker
     * @param InputSticker $sticker A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set remains unchanged.
     * @return ReplaceStickerInSet
     * @link https://core.telegram.org/bots/api#replacestickerinset
     */
    public function replaceStickerInSet(int $user_id, string $name, string $old_sticker, InputSticker $sticker): ReplaceStickerInSet
    {
        return new ReplaceStickerInSet($this->_request, $user_id, $name, $old_sticker, $sticker);
    }

    /**
     * Use this method to change the list of emoji assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns <em>True</em> on success.
     *
     * @param string $sticker File identifier of the sticker
     * @param string  $emoji_list A JSON-serialized list of 1-20 emoji associated with the sticker
     * @return SetStickerEmojiList
     * @link https://core.telegram.org/bots/api#setstickeremojilist
     */
    public function setStickerEmojiList(string $sticker, string  $emoji_list): SetStickerEmojiList
    {
        return new SetStickerEmojiList($this->_request, $sticker, $emoji_list);
    }

    /**
     * Use this method to change search keywords assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns <em>True</em> on success.
     *
     * @param string $sticker File identifier of the sticker
     * @return SetStickerKeywords
     * @link https://core.telegram.org/bots/api#setstickerkeywords
     */
    public function setStickerKeywords(string $sticker): SetStickerKeywords
    {
        return new SetStickerKeywords($this->_request, $sticker);
    }

    /**
     * Use this method to change the <a href="https://core.telegram.org/bots/api#maskposition">mask position</a> of a mask sticker. The sticker must belong to a sticker set that was created by the bot. Returns <em>True</em> on success.
     *
     * @param string $sticker File identifier of the sticker
     * @return SetStickerMaskPosition
     * @link https://core.telegram.org/bots/api#setstickermaskposition
     */
    public function setStickerMaskPosition(string $sticker): SetStickerMaskPosition
    {
        return new SetStickerMaskPosition($this->_request, $sticker);
    }

    /**
     * Use this method to set the title of a created sticker set. Returns <em>True</em> on success.
     *
     * @param string $name Sticker set name
     * @param string $title Sticker set title, 1-64 characters
     * @return SetStickerSetTitle
     * @link https://core.telegram.org/bots/api#setstickersettitle
     */
    public function setStickerSetTitle(string $name, string $title): SetStickerSetTitle
    {
        return new SetStickerSetTitle($this->_request, $name, $title);
    }

    /**
     * Use this method to set the thumbnail of a regular or mask sticker set. The format of the thumbnail file must match the format of the stickers in the set. Returns <em>True</em> on success.
     *
     * @param string $name Sticker set name
     * @param int $user_id User identifier of the sticker set owner
     * @param string $format Format of the thumbnail, must be one of “static” for a <strong>.WEBP</strong> or <strong>.PNG</strong> image, “animated” for a <strong>.TGS</strong> animation, or “video” for a <strong>.WEBM</strong> video
     * @return SetStickerSetThumbnail
     * @link https://core.telegram.org/bots/api#setstickersetthumbnail
     */
    public function setStickerSetThumbnail(string $name, int $user_id, string $format): SetStickerSetThumbnail
    {
        return new SetStickerSetThumbnail($this->_request, $name, $user_id, $format);
    }

    /**
     * Use this method to set the thumbnail of a custom emoji sticker set. Returns <em>True</em> on success.
     *
     * @param string $name Sticker set name
     * @return SetCustomEmojiStickerSetThumbnail
     * @link https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail
     */
    public function setCustomEmojiStickerSetThumbnail(string $name): SetCustomEmojiStickerSetThumbnail
    {
        return new SetCustomEmojiStickerSetThumbnail($this->_request, $name);
    }

    /**
     * Use this method to delete a sticker set that was created by the bot. Returns <em>True</em> on success.
     *
     * @param string $name Sticker set name
     * @return DeleteStickerSet
     * @link https://core.telegram.org/bots/api#deletestickerset
     */
    public function deleteStickerSet(string $name): DeleteStickerSet
    {
        return new DeleteStickerSet($this->_request, $name);
    }

    /**
     * Use this method to send answers to an inline query. On success, <em>True</em> is returned.<br>No more than <strong>50</strong> results per query are allowed.
     *
     * @param string $inline_query_id Unique identifier for the answered query
     * @param string  $results A JSON-serialized array of results for the inline query
     * @return AnswerInlineQuery
     * @link https://core.telegram.org/bots/api#answerinlinequery
     */
    public function answerInlineQuery(string $inline_query_id, string  $results): AnswerInlineQuery
    {
        return new AnswerInlineQuery($this->_request, $inline_query_id, $results);
    }

    /**
     * Use this method to send invoices. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
     * @param string $title Product name, 1-32 characters
     * @param string $description Product description, 1-255 characters
     * @param string $payload Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use it for your internal processes.
     * @param string $currency Three-letter ISO 4217 currency code, see <a href="/bots/payments#supported-currencies">more on currencies</a>. Pass “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param string  $prices Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @return SendInvoice
     * @link https://core.telegram.org/bots/api#sendinvoice
     */
    public function sendInvoice(int|string $chat_id, string $title, string $description, string $payload, string $currency, string  $prices): SendInvoice
    {
        return new SendInvoice($this->_request, $chat_id, $title, $description, $payload, $currency, $prices);
    }

    /**
     * Use this method to create a link for an invoice. Returns the created invoice link as <em>String</em> on success.
     *
     * @param string $title Product name, 1-32 characters
     * @param string $description Product description, 1-255 characters
     * @param string $payload Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use it for your internal processes.
     * @param string $currency Three-letter ISO 4217 currency code, see <a href="/bots/payments#supported-currencies">more on currencies</a>. Pass “XTR” for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @param string  $prices Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost, delivery tax, bonus, etc.). Must contain exactly one item for payments in <a href="https://t.me/BotNews/90">Telegram Stars</a>.
     * @return CreateInvoiceLink
     * @link https://core.telegram.org/bots/api#createinvoicelink
     */
    public function createInvoiceLink(string $title, string $description, string $payload, string $currency, string  $prices): CreateInvoiceLink
    {
        return new CreateInvoiceLink($this->_request, $title, $description, $payload, $currency, $prices);
    }

    /**
     * If you sent an invoice requesting a shipping address and the parameter <em>is_flexible</em> was specified, the Bot API will send an <a href="https://core.telegram.org/bots/api#update">Update</a> with a <em>shipping_query</em> field to the bot. Use this method to reply to shipping queries. On success, <em>True</em> is returned.
     *
     * @param string $shipping_query_id Unique identifier for the query to be answered
     * @param bool $ok Pass <em>True</em> if delivery to the specified address is possible and <em>False</em> if there are any problems (for example, if delivery to the specified address is not possible)
     * @return AnswerShippingQuery
     * @link https://core.telegram.org/bots/api#answershippingquery
     */
    public function answerShippingQuery(string $shipping_query_id, bool $ok): AnswerShippingQuery
    {
        return new AnswerShippingQuery($this->_request, $shipping_query_id, $ok);
    }

    /**
     * Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an <a href="https://core.telegram.org/bots/api#update">Update</a> with the field <em>pre_checkout_query</em>. Use this method to respond to such pre-checkout queries. On success, <em>True</em> is returned. <strong>Note:</strong> The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
     *
     * @param string $pre_checkout_query_id Unique identifier for the query to be answered
     * @param bool $ok Specify <em>True</em> if everything is alright (goods are available, etc.) and the bot is ready to proceed with the order. Use <em>False</em> if there are any problems.
     * @return AnswerPreCheckoutQuery
     * @link https://core.telegram.org/bots/api#answerprecheckoutquery
     */
    public function answerPreCheckoutQuery(string $pre_checkout_query_id, bool $ok): AnswerPreCheckoutQuery
    {
        return new AnswerPreCheckoutQuery($this->_request, $pre_checkout_query_id, $ok);
    }

    /**
     * A method to get the current Telegram Stars balance of the bot. Requires no parameters. On success, returns a <a href="https://core.telegram.org/bots/api#staramount">StarAmount</a> object.
     *
     *

     * @return GetMyStarBalance
     * @link https://core.telegram.org/bots/api#getmystarbalance
     */
    public function getMyStarBalance(): GetMyStarBalance
    {
        return new GetMyStarBalance($this->_request);
    }

    /**
     * Returns the bot&#39;s Telegram Star transactions in chronological order. On success, returns a <a href="https://core.telegram.org/bots/api#startransactions">StarTransactions</a> object.
     *
     *

     * @return GetStarTransactions
     * @link https://core.telegram.org/bots/api#getstartransactions
     */
    public function getStarTransactions(): GetStarTransactions
    {
        return new GetStarTransactions($this->_request);
    }

    /**
     * Refunds a successful payment in <a href="https://t.me/BotNews/90">Telegram Stars</a>. Returns <em>True</em> on success.
     *
     * @param int $user_id Identifier of the user whose payment will be refunded
     * @param string $telegram_payment_charge_id Telegram payment identifier
     * @return RefundStarPayment
     * @link https://core.telegram.org/bots/api#refundstarpayment
     */
    public function refundStarPayment(int $user_id, string $telegram_payment_charge_id): RefundStarPayment
    {
        return new RefundStarPayment($this->_request, $user_id, $telegram_payment_charge_id);
    }

    /**
     * Allows the bot to cancel or re-enable extension of a subscription paid in Telegram Stars. Returns <em>True</em> on success.
     *
     * @param int $user_id Identifier of the user whose subscription will be edited
     * @param string $telegram_payment_charge_id Telegram payment identifier for the subscription
     * @param bool $is_canceled Pass <em>True</em> to cancel extension of the user subscription; the subscription must be active up to the end of the current subscription period. Pass <em>False</em> to allow the user to re-enable a subscription that was previously canceled by the bot.
     * @return EditUserStarSubscription
     * @link https://core.telegram.org/bots/api#edituserstarsubscription
     */
    public function editUserStarSubscription(int $user_id, string $telegram_payment_charge_id, bool $is_canceled): EditUserStarSubscription
    {
        return new EditUserStarSubscription($this->_request, $user_id, $telegram_payment_charge_id, $is_canceled);
    }

    /**
     * Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns <em>True</em> on success.
     *
     * @param int $user_id User identifier
     * @param string  $errors A JSON-serialized array describing the errors
     * @return SetPassportDataErrors
     * @link https://core.telegram.org/bots/api#setpassportdataerrors
     */
    public function setPassportDataErrors(int $user_id, string  $errors): SetPassportDataErrors
    {
        return new SetPassportDataErrors($this->_request, $user_id, $errors);
    }

    /**
     * Use this method to send a game. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param int $chat_id Unique identifier for the target chat. Games can&#39;t be sent to channel direct messages chats and channel chats.
     * @param string $game_short_name Short name of the game, serves as the unique identifier for the game. Set up your games via <a href="https://t.me/botfather">@BotFather</a>.
     * @return SendGame
     * @link https://core.telegram.org/bots/api#sendgame
     */
    public function sendGame(int $chat_id, string $game_short_name): SendGame
    {
        return new SendGame($this->_request, $chat_id, $game_short_name);
    }

    /**
     * Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned. Returns an error, if the new score is not greater than the user&#39;s current score in the chat and <em>force</em> is <em>False</em>.
     *
     * @param int $user_id User identifier
     * @param int $score New score, must be non-negative
     * @return SetGameScore
     * @link https://core.telegram.org/bots/api#setgamescore
     */
    public function setGameScore(int $user_id, int $score): SetGameScore
    {
        return new SetGameScore($this->_request, $user_id, $score);
    }

    /**
     * Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. Returns an Array of <a href="https://core.telegram.org/bots/api#gamehighscore">GameHighScore</a> objects.
     *
     * @param int $user_id Target user id
     * @return GetGameHighScores
     * @link https://core.telegram.org/bots/api#getgamehighscores
     */
    public function getGameHighScores(int $user_id): GetGameHighScores
    {
        return new GetGameHighScores($this->_request, $user_id);
    }
}