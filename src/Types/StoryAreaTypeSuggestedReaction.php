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
    public string $type;
    public ReactionType $reaction_type;
    public bool $is_dark;
    public bool $is_flipped;

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
        if (isset($update['reaction_type'])) $this->reaction_type = new ReactionType($update['reaction_type']);
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