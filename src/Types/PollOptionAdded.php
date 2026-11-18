<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about an option added to a poll.
 * @method self poll_message(MaybeInaccessibleMessage $value) <em>Optional</em>. Message containing the poll to which the option was added, if known. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
 * @method self option_persistent_id(string $value) Unique identifier of the added option
 * @method self option_text(string $value) Option text
 * @method self option_text_entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the <em>option_text</em>
 */
class PollOptionAdded
{
    /** @var MaybeInaccessibleMessage */
    public MaybeInaccessibleMessage $poll_message;
    /** @var string */
    public string $option_persistent_id;
    /** @var string */
    public string $option_text;
    /** @var MessageEntity[] */
    public array $option_text_entities;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a service message about an option added to a poll.
     * @param MaybeInaccessibleMessage|null $poll_message <em>Optional</em>. Message containing the poll to which the option was added, if known. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
     * @param string|null $option_persistent_id Unique identifier of the added option
     * @param string|null $option_text Option text
     * @param MessageEntity[]|null $option_text_entities <em>Optional</em>. Special entities that appear in the <em>option_text</em>
     */
    public static function make(MaybeInaccessibleMessage $poll_message = null, string $option_persistent_id = null, string $option_text = null, array $option_text_entities = null): self
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