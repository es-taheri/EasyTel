<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the rating of a user based on their Telegram Star spendings.
 * @method self level(int $value) Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
 * @method self rating(int $value) Numerical value of the user&#39;s rating; the higher the rating, the better
 * @method self current_level_rating(int $value) The rating value required to get the current level
 * @method self next_level_rating(int $value) <em>Optional</em>. The rating value required to get to the next level; omitted if the maximum level was reached
 */
class UserRating
{
    public int $level;
    public int $rating;
    public int $current_level_rating;
    public int $next_level_rating;

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
     * This object describes the rating of a user based on their Telegram Star spendings.
     * @param int|null $level Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
     * @param int|null $rating Numerical value of the user&#39;s rating; the higher the rating, the better
     * @param int|null $current_level_rating The rating value required to get the current level
     * @param int|null $next_level_rating <em>Optional</em>. The rating value required to get to the next level; omitted if the maximum level was reached
     */
    public static function make(int $level = null, int $rating = null, int $current_level_rating = null, int $next_level_rating = null): self
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