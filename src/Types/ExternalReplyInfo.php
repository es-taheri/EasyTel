<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about a message that is being replied to, which may come from another chat or forum topic.
 * @method self origin(MessageOrigin $value) Origin of the message replied to by the given message
 * @method self chat(Chat $value) <em>Optional</em>. Chat the original message belongs to. Available only if the chat is a supergroup or a channel.
 * @method self message_id(int $value) <em>Optional</em>. Unique message identifier inside the original chat. Available only if the original chat is a supergroup or a channel.
 * @method self link_preview_options(LinkPreviewOptions $value) <em>Optional</em>. Options used for link preview generation for the original message, if it is a text message
 * @method self animation(Animation $value) <em>Optional</em>. Message is an animation, information about the animation
 * @method self audio(Audio $value) <em>Optional</em>. Message is an audio file, information about the file
 * @method self document(Document $value) <em>Optional</em>. Message is a general file, information about the file
 * @method self live_photo(LivePhoto $value) <em>Optional</em>. Message is a live photo, information about the live photo
 * @method self paid_media(PaidMediaInfo $value) <em>Optional</em>. Message contains paid media; information about the paid media
 * @method self photo(PhotoSize[] $value) <em>Optional</em>. Message is a photo, available sizes of the photo
 * @method self sticker(Sticker $value) <em>Optional</em>. Message is a sticker, information about the sticker
 * @method self story(Story $value) <em>Optional</em>. Message is a forwarded story
 * @method self video(Video $value) <em>Optional</em>. Message is a video, information about the video
 * @method self video_note(VideoNote $value) <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
 * @method self voice(Voice $value) <em>Optional</em>. Message is a voice message, information about the file
 * @method self widget(Widget $value) <em>Optional</em>. Message is an HTML widget, information about the widget
 * @method self has_media_spoiler(bool $value) <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
 * @method self checklist(Checklist $value) <em>Optional</em>. Message is a checklist
 * @method self contact(Contact $value) <em>Optional</em>. Message is a shared contact, information about the contact
 * @method self dice(Dice $value) <em>Optional</em>. Message is a dice with random value
 * @method self game(Game $value) <em>Optional</em>. Message is a game, information about the game. <a href="https://core.telegram.org/bots/api#games">More about games »</a>
 * @method self giveaway(Giveaway $value) <em>Optional</em>. Message is a scheduled giveaway, information about the giveaway
 * @method self giveaway_winners(GiveawayWinners $value) <em>Optional</em>. A giveaway with public winners was completed
 * @method self invoice(Invoice $value) <em>Optional</em>. Message is an invoice for a <a href="https://core.telegram.org/bots/api#payments">payment</a>, information about the invoice. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
 * @method self location(Location $value) <em>Optional</em>. Message is a shared location, information about the location
 * @method self poll(Poll $value) <em>Optional</em>. Message is a native poll, information about the poll
 * @method self venue(Venue $value) <em>Optional</em>. Message is a venue, information about the venue
 */
class ExternalReplyInfo
{
    /** @var MessageOrigin */
    public MessageOrigin $origin;
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_id;
    /** @var LinkPreviewOptions */
    public LinkPreviewOptions $link_preview_options;
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
    /** @var Giveaway */
    public Giveaway $giveaway;
    /** @var GiveawayWinners */
    public GiveawayWinners $giveaway_winners;
    /** @var Invoice */
    public Invoice $invoice;
    /** @var Location */
    public Location $location;
    /** @var Poll */
    public Poll $poll;
    /** @var Venue */
    public Venue $venue;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about a message that is being replied to, which may come from another chat or forum topic.
     * @param MessageOrigin|null $origin Origin of the message replied to by the given message
     * @param Chat|null $chat <em>Optional</em>. Chat the original message belongs to. Available only if the chat is a supergroup or a channel.
     * @param int|null $message_id <em>Optional</em>. Unique message identifier inside the original chat. Available only if the original chat is a supergroup or a channel.
     * @param LinkPreviewOptions|null $link_preview_options <em>Optional</em>. Options used for link preview generation for the original message, if it is a text message
     * @param Animation|null $animation <em>Optional</em>. Message is an animation, information about the animation
     * @param Audio|null $audio <em>Optional</em>. Message is an audio file, information about the file
     * @param Document|null $document <em>Optional</em>. Message is a general file, information about the file
     * @param LivePhoto|null $live_photo <em>Optional</em>. Message is a live photo, information about the live photo
     * @param PaidMediaInfo|null $paid_media <em>Optional</em>. Message contains paid media; information about the paid media
     * @param PhotoSize[]|null $photo <em>Optional</em>. Message is a photo, available sizes of the photo
     * @param Sticker|null $sticker <em>Optional</em>. Message is a sticker, information about the sticker
     * @param Story|null $story <em>Optional</em>. Message is a forwarded story
     * @param Video|null $video <em>Optional</em>. Message is a video, information about the video
     * @param VideoNote|null $video_note <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
     * @param Voice|null $voice <em>Optional</em>. Message is a voice message, information about the file
     * @param Widget|null $widget <em>Optional</em>. Message is an HTML widget, information about the widget
     * @param bool|null $has_media_spoiler <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
     * @param Checklist|null $checklist <em>Optional</em>. Message is a checklist
     * @param Contact|null $contact <em>Optional</em>. Message is a shared contact, information about the contact
     * @param Dice|null $dice <em>Optional</em>. Message is a dice with random value
     * @param Game|null $game <em>Optional</em>. Message is a game, information about the game. <a href="https://core.telegram.org/bots/api#games">More about games »</a>
     * @param Giveaway|null $giveaway <em>Optional</em>. Message is a scheduled giveaway, information about the giveaway
     * @param GiveawayWinners|null $giveaway_winners <em>Optional</em>. A giveaway with public winners was completed
     * @param Invoice|null $invoice <em>Optional</em>. Message is an invoice for a <a href="https://core.telegram.org/bots/api#payments">payment</a>, information about the invoice. <a href="https://core.telegram.org/bots/api#payments">More about payments »</a>
     * @param Location|null $location <em>Optional</em>. Message is a shared location, information about the location
     * @param Poll|null $poll <em>Optional</em>. Message is a native poll, information about the poll
     * @param Venue|null $venue <em>Optional</em>. Message is a venue, information about the venue
     */
    public static function make(MessageOrigin $origin = null, Chat $chat = null, int $message_id = null, LinkPreviewOptions $link_preview_options = null, Animation $animation = null, Audio $audio = null, Document $document = null, LivePhoto $live_photo = null, PaidMediaInfo $paid_media = null, array $photo = null, Sticker $sticker = null, Story $story = null, Video $video = null, VideoNote $video_note = null, Voice $voice = null, Widget $widget = null, bool $has_media_spoiler = null, Checklist $checklist = null, Contact $contact = null, Dice $dice = null, Game $game = null, Giveaway $giveaway = null, GiveawayWinners $giveaway_winners = null, Invoice $invoice = null, Location $location = null, Poll $poll = null, Venue $venue = null): self
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