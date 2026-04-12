<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a venue.
 * @method self location(Location $value) Venue location. Can&#39;t be a live location
 * @method self title(string $value) Name of the venue
 * @method self address(string $value) Address of the venue
 * @method self foursquare_id(string $value) <em>Optional</em>. Foursquare identifier of the venue
 * @method self foursquare_type(string $value) <em>Optional</em>. Foursquare type of the venue. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
 * @method self google_place_id(string $value) <em>Optional</em>. Google Places identifier of the venue
 * @method self google_place_type(string $value) <em>Optional</em>. Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
 */
class Venue
{
    public Location $location;
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
        if (isset($update['location'])) $this->location = new Location($update['location']);
    }

    /**
     * This object represents a venue.
     * @param Location|null $location Venue location. Can&#39;t be a live location
     * @param string|null $title Name of the venue
     * @param string|null $address Address of the venue
     * @param string|null $foursquare_id <em>Optional</em>. Foursquare identifier of the venue
     * @param string|null $foursquare_type <em>Optional</em>. Foursquare type of the venue. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
     * @param string|null $google_place_id <em>Optional</em>. Google Places identifier of the venue
     * @param string|null $google_place_type <em>Optional</em>. Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
     */
    public static function make(Location $location = null, string $title = null, string $address = null, string $foursquare_id = null, string $foursquare_type = null, string $google_place_id = null, string $google_place_type = null): self
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