<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about an option deleted from a poll.
 * @method self poll_message(MaybeInaccessibleMessage $value) <em>Optional</em>. Message containing the poll from which the option was deleted, if known. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
 * @method self option_persistent_id(string $value) Unique identifier of the deleted option
 * @method self option_text(string $value) Option text
 * @method self option_text_entities(array $value) <em>Optional</em>. Special entities that appear in the <em>option_text</em>
 */
class PollOptionDeleted
{
    public MaybeInaccessibleMessage $poll_message;
    public string $option_persistent_id;
    public string $option_text;
    public array $option_text_entities;

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
        if (isset($update['poll_message'])) $this->poll_message = new MaybeInaccessibleMessage($update['poll_message']);
    }

    /**
     * Describes a service message about an option deleted from a poll.
     * @param MaybeInaccessibleMessage|null $poll_message <em>Optional</em>. Message containing the poll from which the option was deleted, if known. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
     * @param string|null $option_persistent_id Unique identifier of the deleted option
     * @param string|null $option_text Option text
     * @param array|null $option_text_entities <em>Optional</em>. Special entities that appear in the <em>option_text</em>
     */
    public static function make(MaybeInaccessibleMessage $poll_message = null, string $option_persistent_id = null, string $option_text = null, array $option_text_entities = null): self
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