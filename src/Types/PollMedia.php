<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * At most <strong>one</strong> of the optional fields can be present in any given object.
 * @method self animation(Animation $value) <em>Optional</em>. Media is an animation, information about the animation
 * @method self audio(Audio $value) <em>Optional</em>. Media is an audio file, information about the file; currently, can't be received in a poll option
 * @method self document(Document $value) <em>Optional</em>. Media is a general file, information about the file; currently, can't be received in a poll option
 * @method self link(Link $value) <em>Optional</em>. The HTTP link attached to the poll option
 * @method self live_photo(LivePhoto $value) <em>Optional</em>. Media is a live photo, information about the live photo
 * @method self location(Location $value) <em>Optional</em>. Media is a shared location, information about the location
 * @method self photo(PhotoSize[] $value) <em>Optional</em>. Media is a photo, available sizes of the photo
 * @method self sticker(Sticker $value) <em>Optional</em>. Media is a sticker, information about the sticker; currently, for poll options only
 * @method self venue(Venue $value) <em>Optional</em>. Media is a venue, information about the venue
 * @method self video(Video $value) <em>Optional</em>. Media is a video, information about the video
 */
class PollMedia
{
    /** @var Animation */
    public Animation $animation;
    /** @var Audio */
    public Audio $audio;
    /** @var Document */
    public Document $document;
    /** @var Link */
    public Link $link;
    /** @var LivePhoto */
    public LivePhoto $live_photo;
    /** @var Location */
    public Location $location;
    /** @var PhotoSize[] */
    public array $photo;
    /** @var Sticker */
    public Sticker $sticker;
    /** @var Venue */
    public Venue $venue;
    /** @var Video */
    public Video $video;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * At most <strong>one</strong> of the optional fields can be present in any given object.
     * @param Animation|null $animation <em>Optional</em>. Media is an animation, information about the animation
     * @param Audio|null $audio <em>Optional</em>. Media is an audio file, information about the file; currently, can't be received in a poll option
     * @param Document|null $document <em>Optional</em>. Media is a general file, information about the file; currently, can't be received in a poll option
     * @param Link|null $link <em>Optional</em>. The HTTP link attached to the poll option
     * @param LivePhoto|null $live_photo <em>Optional</em>. Media is a live photo, information about the live photo
     * @param Location|null $location <em>Optional</em>. Media is a shared location, information about the location
     * @param PhotoSize[]|null $photo <em>Optional</em>. Media is a photo, available sizes of the photo
     * @param Sticker|null $sticker <em>Optional</em>. Media is a sticker, information about the sticker; currently, for poll options only
     * @param Venue|null $venue <em>Optional</em>. Media is a venue, information about the venue
     * @param Video|null $video <em>Optional</em>. Media is a video, information about the video
     */
    public static function make(Animation $animation = null, Audio $audio = null, Document $document = null, Link $link = null, LivePhoto $live_photo = null, Location $location = null, array $photo = null, Sticker $sticker = null, Venue $venue = null, Video $video = null): self
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