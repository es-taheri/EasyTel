<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * A block with a map, corresponding to the custom HTML tag <code><tg-map></code>.
 * @method self type(string $value) Type of the block, always “map”
 * @method self location(Location $value) Location of the center of the map
 * @method self zoom(int $value) Map zoom level
 * @method self width(int $value) Expected width of the map
 * @method self height(int $value) Expected height of the map
 * @method self caption(RichBlockCaption $value) <em>Optional</em>. Caption of the block
 */
class RichBlockMap
{
    /** @var string */
    public string $type;
    /** @var Location */
    public Location $location;
    /** @var int */
    public int $zoom;
    /** @var int */
    public int $width;
    /** @var int */
    public int $height;
    /** @var RichBlockCaption */
    public RichBlockCaption $caption;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * A block with a map, corresponding to the custom HTML tag <code><tg-map></code>.
     * @param string|null $type Type of the block, always “map”
     * @param Location|null $location Location of the center of the map
     * @param int|null $zoom Map zoom level
     * @param int|null $width Expected width of the map
     * @param int|null $height Expected height of the map
     * @param RichBlockCaption|null $caption <em>Optional</em>. Caption of the block
     */
    public static function make(string $type = null, Location $location = null, int $zoom = null, int $width = null, int $height = null, RichBlockCaption $caption = null): self
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