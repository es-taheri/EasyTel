<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the types of gifts that can be gifted to a user or a chat.
 * @method self unlimited_gifts(bool $value) <em>True</em>, if unlimited regular gifts are accepted
 * @method self limited_gifts(bool $value) <em>True</em>, if limited regular gifts are accepted
 * @method self unique_gifts(bool $value) <em>True</em>, if unique gifts or gifts that can be upgraded to unique for free are accepted
 * @method self premium_subscription(bool $value) <em>True</em>, if a Telegram Premium subscription is accepted
 * @method self gifts_from_channels(bool $value) <em>True</em>, if transfers of unique gifts from channels are accepted
 */
class AcceptedGiftTypes
{
    /** @var bool */
    public bool $unlimited_gifts;
    /** @var bool */
    public bool $limited_gifts;
    /** @var bool */
    public bool $unique_gifts;
    /** @var bool */
    public bool $premium_subscription;
    /** @var bool */
    public bool $gifts_from_channels;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes the types of gifts that can be gifted to a user or a chat.
     * @param bool|null $unlimited_gifts <em>True</em>, if unlimited regular gifts are accepted
     * @param bool|null $limited_gifts <em>True</em>, if limited regular gifts are accepted
     * @param bool|null $unique_gifts <em>True</em>, if unique gifts or gifts that can be upgraded to unique for free are accepted
     * @param bool|null $premium_subscription <em>True</em>, if a Telegram Premium subscription is accepted
     * @param bool|null $gifts_from_channels <em>True</em>, if transfers of unique gifts from channels are accepted
     */
    public static function make(bool $unlimited_gifts = null, bool $limited_gifts = null, bool $unique_gifts = null, bool $premium_subscription = null, bool $gifts_from_channels = null): self
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