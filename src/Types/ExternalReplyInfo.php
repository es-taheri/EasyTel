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
 * @method self paid_media(PaidMediaInfo $value) <em>Optional</em>. Message contains paid media; information about the paid media
 * @method self photo(array $value) <em>Optional</em>. Message is a photo, available sizes of the photo
 * @method self sticker(Sticker $value) <em>Optional</em>. Message is a sticker, information about the sticker
 * @method self story(Story $value) <em>Optional</em>. Message is a forwarded story
 * @method self video(Video $value) <em>Optional</em>. Message is a video, information about the video
 * @method self video_note(VideoNote $value) <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
 * @method self voice(Voice $value) <em>Optional</em>. Message is a voice message, information about the file
 * @method self has_media_spoiler(True $value) <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
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
    public MessageOrigin $origin;
    public Chat $chat;
    public int $message_id;
    public LinkPreviewOptions $link_preview_options;
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
    public True $has_media_spoiler;
    public Checklist $checklist;
    public Contact $contact;
    public Dice $dice;
    public Game $game;
    public Giveaway $giveaway;
    public GiveawayWinners $giveaway_winners;
    public Invoice $invoice;
    public Location $location;
    public Poll $poll;
    public Venue $venue;

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
        if (isset($update['origin'])) $this->origin = new MessageOrigin($update['origin']);
        if (isset($update['chat'])) $this->chat = new Chat($update['chat']);
        if (isset($update['link_preview_options'])) $this->link_preview_options = new LinkPreviewOptions($update['link_preview_options']);
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
        if (isset($update['giveaway'])) $this->giveaway = new Giveaway($update['giveaway']);
        if (isset($update['giveaway_winners'])) $this->giveaway_winners = new GiveawayWinners($update['giveaway_winners']);
        if (isset($update['invoice'])) $this->invoice = new Invoice($update['invoice']);
        if (isset($update['location'])) $this->location = new Location($update['location']);
        if (isset($update['poll'])) $this->poll = new Poll($update['poll']);
        if (isset($update['venue'])) $this->venue = new Venue($update['venue']);
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
     * @param PaidMediaInfo|null $paid_media <em>Optional</em>. Message contains paid media; information about the paid media
     * @param array|null $photo <em>Optional</em>. Message is a photo, available sizes of the photo
     * @param Sticker|null $sticker <em>Optional</em>. Message is a sticker, information about the sticker
     * @param Story|null $story <em>Optional</em>. Message is a forwarded story
     * @param Video|null $video <em>Optional</em>. Message is a video, information about the video
     * @param VideoNote|null $video_note <em>Optional</em>. Message is a <a href="https://telegram.org/blog/video-messages-and-telescope">video note</a>, information about the video message
     * @param Voice|null $voice <em>Optional</em>. Message is a voice message, information about the file
     * @param True|null $has_media_spoiler <em>Optional</em>. <em>True</em>, if the message media is covered by a spoiler animation
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
    public static function make(MessageOrigin $origin = null, Chat $chat = null, int $message_id = null, LinkPreviewOptions $link_preview_options = null, Animation $animation = null, Audio $audio = null, Document $document = null, PaidMediaInfo $paid_media = null, array $photo = null, Sticker $sticker = null, Story $story = null, Video $video = null, VideoNote $video_note = null, Voice $voice = null, True $has_media_spoiler = null, Checklist $checklist = null, Contact $contact = null, Dice $dice = null, Game $game = null, Giveaway $giveaway = null, GiveawayWinners $giveaway_winners = null, Invoice $invoice = null, Location $location = null, Poll $poll = null, Venue $venue = null): self
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