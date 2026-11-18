<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes an update about a user stopping message generation.
 * @method self chat(Chat $value) Chat in which the message is generated
 * @method self message_thread_id(int $value) <em>Optional</em>. Unique identifier of the message thread in which the message is generated
 * @method self draft_id(int $value) Unique identifier of the message draft which was stopped
 */
class MessageGenerationStopped
{
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_thread_id;
    /** @var int */
    public int $draft_id;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes an update about a user stopping message generation.
     * @param Chat|null $chat Chat in which the message is generated
     * @param int|null $message_thread_id <em>Optional</em>. Unique identifier of the message thread in which the message is generated
     * @param int|null $draft_id Unique identifier of the message draft which was stopped
     */
    public static function make(Chat $chat = null, int $message_thread_id = null, int $draft_id = null): self
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