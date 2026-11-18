<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update <em>managed_bot</em> and a <a href="https://core.telegram.org/bots/api#message">Message</a> with the field <em>managed_bot_created</em>.
 * @method self request_id(int $value) Signed 32-bit identifier of the request. Must be unique within the message.
 * @method self suggested_name(string $value) <em>Optional</em>. Suggested name for the bot
 * @method self suggested_username(string $value) <em>Optional</em>. Suggested username for the bot. The username must end with “bot”. If the user creating the bot has a Telegram Premium subscription, then the username can also end with “agent”, “ai”, “app”, “game”, or “gay”; in this case, the username will be assigned to the bot as a secondary username, and a regular username ending with “bot” will be assigned to the bot automatically.
 */
class KeyboardButtonRequestManagedBot
{
    /** @var int */
    public int $request_id;
    /** @var string */
    public string $suggested_name;
    /** @var string */
    public string $suggested_username;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object defines the parameters for the creation of a managed bot. Information about the created bot will be shared with the bot using the update <em>managed_bot</em> and a <a href="https://core.telegram.org/bots/api#message">Message</a> with the field <em>managed_bot_created</em>.
     * @param int|null $request_id Signed 32-bit identifier of the request. Must be unique within the message.
     * @param string|null $suggested_name <em>Optional</em>. Suggested name for the bot
     * @param string|null $suggested_username <em>Optional</em>. Suggested username for the bot. The username must end with “bot”. If the user creating the bot has a Telegram Premium subscription, then the username can also end with “agent”, “ai”, “app”, “game”, or “gay”; in this case, the username will be assigned to the bot as a secondary username, and a regular username ending with “bot” will be assigned to the bot automatically.
     */
    public static function make(int $request_id = null, string $suggested_name = null, string $suggested_username = null): self
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