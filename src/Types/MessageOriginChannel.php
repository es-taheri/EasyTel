<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The message was originally sent to a channel chat.
 * @method self type(string $value) Type of the message origin, always “channel”
 * @method self date(int $value) Date the message was sent originally in Unix time
 * @method self chat(Chat $value) Channel chat to which the message was originally sent
 * @method self message_id(int $value) Unique message identifier inside the chat
 * @method self author_signature(string $value) <em>Optional</em>. Signature of the original post author
 */
class MessageOriginChannel
{
    /** @var string */
    public string $type;
    /** @var int */
    public int $date;
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_id;
    /** @var string */
    public string $author_signature;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The message was originally sent to a channel chat.
     * @param string|null $type Type of the message origin, always “channel”
     * @param int|null $date Date the message was sent originally in Unix time
     * @param Chat|null $chat Channel chat to which the message was originally sent
     * @param int|null $message_id Unique message identifier inside the chat
     * @param string|null $author_signature <em>Optional</em>. Signature of the original post author
     */
    public static function make(string $type = null, int $date = null, Chat $chat = null, int $message_id = null, string $author_signature = null): self
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