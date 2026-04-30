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
    public User $user;
    public User $bot;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
        if (isset($update['bot'])) $this->bot = new User($update['bot']);
    }

    /**
     * This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
     * @param User|null $user User that created the bot
     * @param User|null $bot Information about the bot. Token of the bot can be fetched using the method <a href="https://core.telegram.org/bots/api#getmanagedbottoken">getManagedBotToken</a>.
     */
    public static function make(User $user = null, User $bot = null): self
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