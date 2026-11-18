<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object is received when messages are deleted from a connected business account.
 * @method self business_connection_id(string $value) Unique identifier of the business connection
 * @method self chat(Chat $value) Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
 * @method self message_ids(int[] $value) The list of identifiers of deleted messages in the chat of the business account
 */
class BusinessMessagesDeleted
{
    /** @var string */
    public string $business_connection_id;
    /** @var Chat */
    public Chat $chat;
    /** @var int[] */
    public array $message_ids;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object is received when messages are deleted from a connected business account.
     * @param string|null $business_connection_id Unique identifier of the business connection
     * @param Chat|null $chat Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
     * @param int[]|null $message_ids The list of identifiers of deleted messages in the chat of the business account
     */
    public static function make(string $business_connection_id = null, Chat $chat = null, array $message_ids = null): self
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