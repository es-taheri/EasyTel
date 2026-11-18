<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a story area pointing to a suggested reaction. Currently, a story can have up to 5 suggested reaction areas.
 * @method self type(string $value) Type of the area, always “suggested_reaction”
 * @method self reaction_type(ReactionType $value) Type of the reaction
 * @method self is_dark(bool $value) <em>Optional</em>. Pass <em>True</em> if the reaction area has a dark background
 * @method self is_flipped(bool $value) <em>Optional</em>. Pass <em>True</em> if reaction area corner is flipped
 */
class StoryAreaTypeSuggestedReaction
{
    /** @var string */
    public string $type;
    /** @var ReactionType */
    public ReactionType $reaction_type;
    /** @var bool */
    public bool $is_dark;
    /** @var bool */
    public bool $is_flipped;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a story area pointing to a suggested reaction. Currently, a story can have up to 5 suggested reaction areas.
     * @param string|null $type Type of the area, always “suggested_reaction”
     * @param ReactionType|null $reaction_type Type of the reaction
     * @param bool|null $is_dark <em>Optional</em>. Pass <em>True</em> if the reaction area has a dark background
     * @param bool|null $is_flipped <em>Optional</em>. Pass <em>True</em> if reaction area corner is flipped
     */
    public static function make(string $type = null, ReactionType $reaction_type = null, bool $is_dark = null, bool $is_flipped = null): self
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