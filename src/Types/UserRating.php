<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the rating of a user based on their Telegram Star spendings.
 * @method self level(int $value) Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
 * @method self rating(int $value) Numerical value of the user's rating; the higher the rating, the better
 * @method self current_level_rating(int $value) The rating value required to get the current level
 * @method self next_level_rating(int $value) <em>Optional</em>. The rating value required to get to the next level; omitted if the maximum level was reached
 */
class UserRating
{
    /** @var int */
    public int $level;
    /** @var int */
    public int $rating;
    /** @var int */
    public int $current_level_rating;
    /** @var int */
    public int $next_level_rating;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes the rating of a user based on their Telegram Star spendings.
     * @param int|null $level Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
     * @param int|null $rating Numerical value of the user's rating; the higher the rating, the better
     * @param int|null $current_level_rating The rating value required to get the current level
     * @param int|null $next_level_rating <em>Optional</em>. The rating value required to get to the next level; omitted if the maximum level was reached
     */
    public static function make(int $level = null, int $rating = null, int $current_level_rating = null, int $next_level_rating = null): self
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