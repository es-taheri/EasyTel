<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a venue. By default, the venue will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the venue.
 * @method self type(string $value) Type of the result, must be <em>venue</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 Bytes
 * @method self latitude(float $value) Latitude of the venue location in degrees
 * @method self longitude(float $value) Longitude of the venue location in degrees
 * @method self title(string $value) Title of the venue
 * @method self address(string $value) Address of the venue
 * @method self foursquare_id(string $value) <em>Optional</em>. Foursquare identifier of the venue if known
 * @method self foursquare_type(string $value) <em>Optional</em>. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
 * @method self google_place_id(string $value) <em>Optional</em>. Google Places identifier of the venue
 * @method self google_place_type(string $value) <em>Optional</em>. Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self input_message_content(InputMessageContent $value) <em>Optional</em>. Content of the message to be sent instead of the venue
 * @method self thumbnail_url(string $value) <em>Optional</em>. Url of the thumbnail for the result
 * @method self thumbnail_width(int $value) <em>Optional</em>. Thumbnail width
 * @method self thumbnail_height(int $value) <em>Optional</em>. Thumbnail height
 */
class InlineQueryResultVenue
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $id;
    /** @var float */
    public float $latitude;
    /** @var float */
    public float $longitude;
    /** @var string */
    public string $title;
    /** @var string */
    public string $address;
    /** @var string */
    public string $foursquare_id;
    /** @var string */
    public string $foursquare_type;
    /** @var string */
    public string $google_place_id;
    /** @var string */
    public string $google_place_type;
    /** @var InlineKeyboardMarkup */
    public InlineKeyboardMarkup $reply_markup;
    /** @var InputMessageContent */
    public InputMessageContent $input_message_content;
    /** @var string */
    public string $thumbnail_url;
    /** @var int */
    public int $thumbnail_width;
    /** @var int */
    public int $thumbnail_height;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a venue. By default, the venue will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the venue.
     * @param string|null $type Type of the result, must be <em>venue</em>
     * @param string|null $id Unique identifier for this result, 1-64 Bytes
     * @param float|null $latitude Latitude of the venue location in degrees
     * @param float|null $longitude Longitude of the venue location in degrees
     * @param string|null $title Title of the venue
     * @param string|null $address Address of the venue
     * @param string|null $foursquare_id <em>Optional</em>. Foursquare identifier of the venue if known
     * @param string|null $foursquare_type <em>Optional</em>. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
     * @param string|null $google_place_id <em>Optional</em>. Google Places identifier of the venue
     * @param string|null $google_place_type <em>Optional</em>. Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param InputMessageContent|null $input_message_content <em>Optional</em>. Content of the message to be sent instead of the venue
     * @param string|null $thumbnail_url <em>Optional</em>. Url of the thumbnail for the result
     * @param int|null $thumbnail_width <em>Optional</em>. Thumbnail width
     * @param int|null $thumbnail_height <em>Optional</em>. Thumbnail height
     */
    public static function make(string $type = null, string $id = null, float $latitude = null, float $longitude = null, string $title = null, string $address = null, string $foursquare_id = null, string $foursquare_type = null, string $google_place_id = null, string $google_place_type = null, InlineKeyboardMarkup $reply_markup = null, InputMessageContent $input_message_content = null, string $thumbnail_url = null, int $thumbnail_width = null, int $thumbnail_height = null): self
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