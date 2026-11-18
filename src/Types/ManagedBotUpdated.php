<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
 * @method self user(User $value) User that created the bot
 * @method self bot(User $value) Information about the bot. Token of the bot can be fetched using the method <a href="https://core.telegram.org/bots/api#getmanagedbottoken">getManagedBotToken</a>.
 */
class ManagedBotUpdated
{
    /** @var User */
    public User $user;
    /** @var User */
    public User $bot;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
     * @param User|null $user User that created the bot
     * @param User|null $bot Information about the bot. Token of the bot can be fetched using the method <a href="https://core.telegram.org/bots/api#getmanagedbottoken">getManagedBotToken</a>.
     */
    public static function make(User $user = null, User $bot = null): self
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