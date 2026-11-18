<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Formatted date and time.
 * @method self type(string $value) Type of the rich text, always “date_time”
 * @method self text(RichText|string|array $value) The text
 * @method self unix_time(int $value) The Unix time associated with the entity
 * @method self date_time_format(string $value) The string that defines the formatting of the date and time. See <a href="https://core.telegram.org/bots/api#date-time-entity-formatting">date-time entity formatting</a> for more details.
 */
class RichTextDateTime
{
    /** @var string */
    public string $type;
    /** @var RichText|string|array */
    public RichText|string|array $text;
    /** @var int */
    public int $unix_time;
    /** @var string */
    public string $date_time_format;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Formatted date and time.
     * @param string|null $type Type of the rich text, always “date_time”
     * @param RichText|string|array|null $text The text
     * @param int|null $unix_time The Unix time associated with the entity
     * @param string|null $date_time_format The string that defines the formatting of the date and time. See <a href="https://core.telegram.org/bots/api#date-time-entity-formatting">date-time entity formatting</a> for more details.
     */
    public static function make(string $type = null, RichText|string|array $text = null, int $unix_time = null, string $date_time_format = null): self
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