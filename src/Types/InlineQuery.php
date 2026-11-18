<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an incoming inline query. When the user sends an empty query, your bot could return some default or trending results.
 * @method self id(string $value) Unique identifier for this query
 * @method self from(User $value) Sender
 * @method self query(string $value) Text of the query (up to 256 characters)
 * @method self offset(string $value) Offset of the results to be returned, can be controlled by the bot
 * @method self chat_type(string $value) <em>Optional</em>. Type of the chat from which the inline query was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat.
 * @method self location(Location $value) <em>Optional</em>. Sender location, only for bots that request user location
 */
class InlineQuery
{
    /** @var string */
    public string $id;
    /** @var User */
    public User $from;
    /** @var string */
    public string $query;
    /** @var string */
    public string $offset;
    /** @var string */
    public string $chat_type;
    /** @var Location */
    public Location $location;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents an incoming inline query. When the user sends an empty query, your bot could return some default or trending results.
     * @param string|null $id Unique identifier for this query
     * @param User|null $from Sender
     * @param string|null $query Text of the query (up to 256 characters)
     * @param string|null $offset Offset of the results to be returned, can be controlled by the bot
     * @param string|null $chat_type <em>Optional</em>. Type of the chat from which the inline query was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat.
     * @param Location|null $location <em>Optional</em>. Sender location, only for bots that request user location
     */
    public static function make(string $id = null, User $from = null, string $query = null, string $offset = null, string $chat_type = null, Location $location = null): self
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