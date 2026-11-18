<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a location on a map. By default, the location will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the location.
 * @method self type(string $value) Type of the result, must be <em>location</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 Bytes
 * @method self latitude(float $value) Location latitude in degrees
 * @method self longitude(float $value) Location longitude in degrees
 * @method self title(string $value) Location title
 * @method self horizontal_accuracy(float $value) <em>Optional</em>. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method self live_period(int $value) <em>Optional</em>. Period in seconds during which the location can be updated, must be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely
 * @method self heading(int $value) <em>Optional</em>. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
 * @method self proximity_alert_radius(int $value) <em>Optional</em>. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 * @method self input_message_content(InputMessageContent $value) <em>Optional</em>. Content of the message to be sent instead of the location
 * @method self thumbnail_url(string $value) <em>Optional</em>. Url of the thumbnail for the result
 * @method self thumbnail_width(int $value) <em>Optional</em>. Thumbnail width
 * @method self thumbnail_height(int $value) <em>Optional</em>. Thumbnail height
 */
class InlineQueryResultLocation
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
    /** @var float */
    public float $horizontal_accuracy;
    /** @var int */
    public int $live_period;
    /** @var int */
    public int $heading;
    /** @var int */
    public int $proximity_alert_radius;
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
     * Represents a location on a map. By default, the location will be sent by the user. Alternatively, you can use <em>input_message_content</em> to send a message with the specified content instead of the location.
     * @param string|null $type Type of the result, must be <em>location</em>
     * @param string|null $id Unique identifier for this result, 1-64 Bytes
     * @param float|null $latitude Location latitude in degrees
     * @param float|null $longitude Location longitude in degrees
     * @param string|null $title Location title
     * @param float|null $horizontal_accuracy <em>Optional</em>. The radius of uncertainty for the location, measured in meters; 0-1500
     * @param int|null $live_period <em>Optional</em>. Period in seconds during which the location can be updated, must be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely
     * @param int|null $heading <em>Optional</em>. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
     * @param int|null $proximity_alert_radius <em>Optional</em>. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     * @param InputMessageContent|null $input_message_content <em>Optional</em>. Content of the message to be sent instead of the location
     * @param string|null $thumbnail_url <em>Optional</em>. Url of the thumbnail for the result
     * @param int|null $thumbnail_width <em>Optional</em>. Thumbnail width
     * @param int|null $thumbnail_height <em>Optional</em>. Thumbnail height
     */
    public static function make(string $type = null, string $id = null, float $latitude = null, float $longitude = null, string $title = null, float $horizontal_accuracy = null, int $live_period = null, int $heading = null, int $proximity_alert_radius = null, InlineKeyboardMarkup $reply_markup = null, InputMessageContent $input_message_content = null, string $thumbnail_url = null, int $thumbnail_width = null, int $thumbnail_height = null): self
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