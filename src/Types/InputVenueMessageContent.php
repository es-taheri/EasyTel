<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a venue message to be sent as the result of an inline query.
 * @method self latitude(float $value) Latitude of the venue in degrees
 * @method self longitude(float $value) Longitude of the venue in degrees
 * @method self title(string $value) Name of the venue
 * @method self address(string $value) Address of the venue
 * @method self foursquare_id(string $value) <em>Optional</em>. Foursquare identifier of the venue, if known
 * @method self foursquare_type(string $value) <em>Optional</em>. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
 * @method self google_place_id(string $value) <em>Optional</em>. Google Places identifier of the venue
 * @method self google_place_type(string $value) <em>Optional</em>. Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
 */
class InputVenueMessageContent
{
    public float $latitude;
    public float $longitude;
    public string $title;
    public string $address;
    public string $foursquare_id;
    public string $foursquare_type;
    public string $google_place_id;
    public string $google_place_type;

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
        
    }

    /**
     * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a venue message to be sent as the result of an inline query.
     * @param float|null $latitude Latitude of the venue in degrees
     * @param float|null $longitude Longitude of the venue in degrees
     * @param string|null $title Name of the venue
     * @param string|null $address Address of the venue
     * @param string|null $foursquare_id <em>Optional</em>. Foursquare identifier of the venue, if known
     * @param string|null $foursquare_type <em>Optional</em>. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
     * @param string|null $google_place_id <em>Optional</em>. Google Places identifier of the venue
     * @param string|null $google_place_type <em>Optional</em>. Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
     */
    public static function make(float $latitude = null, float $longitude = null, string $title = null, string $address = null, string $foursquare_id = null, string $foursquare_type = null, string $google_place_id = null, string $google_place_type = null): self
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