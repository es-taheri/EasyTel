<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents reaction changes on a message with anonymous reactions.
 * @method self chat(Chat $value) The chat containing the message
 * @method self message_id(int $value) Unique message identifier inside the chat
 * @method self date(int $value) Date of the change in Unix time
 * @method self reactions(ReactionCount[] $value) List of reactions that are present on the message
 */
class MessageReactionCountUpdated
{
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_id;
    /** @var int */
    public int $date;
    /** @var ReactionCount[] */
    public array $reactions;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents reaction changes on a message with anonymous reactions.
     * @param Chat|null $chat The chat containing the message
     * @param int|null $message_id Unique message identifier inside the chat
     * @param int|null $date Date of the change in Unix time
     * @param ReactionCount[]|null $reactions List of reactions that are present on the message
     */
    public static function make(Chat $chat = null, int $message_id = null, int $date = null, array $reactions = null): self
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