<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes a message that was deleted or is otherwise inaccessible to the bot.
 * @method self chat(Chat $value) Chat the message belonged to
 * @method self message_id(int $value) Unique message identifier inside the chat
 * @method self date(int $value) Always 0. The field can be used to differentiate regular and inaccessible messages.
 */
class InaccessibleMessage
{
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_id;
    /** @var int */
    public int $date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes a message that was deleted or is otherwise inaccessible to the bot.
     * @param Chat|null $chat Chat the message belonged to
     * @param int|null $message_id Unique message identifier inside the chat
     * @param int|null $date Always 0. The field can be used to differentiate regular and inaccessible messages.
     */
    public static function make(Chat $chat = null, int $message_id = null, int $date = null): self
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