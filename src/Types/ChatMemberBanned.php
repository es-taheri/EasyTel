<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that was banned in the chat and can't return to the chat or view chat messages.
 * @method self status(string $value) The member's status in the chat, always “kicked”
 * @method self user(User $value) Information about the user
 * @method self until_date(int $value) Date when restrictions will be lifted for this user; Unix time. If 0, then the user is banned forever.
 */
class ChatMemberBanned
{
    /** @var string */
    public string $status;
    /** @var User */
    public User $user;
    /** @var int */
    public int $until_date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that was banned in the chat and can't return to the chat or view chat messages.
     * @param string|null $status The member's status in the chat, always “kicked”
     * @param User|null $user Information about the user
     * @param int|null $until_date Date when restrictions will be lifted for this user; Unix time. If 0, then the user is banned forever.
     */
    public static function make(string $status = null, User $user = null, int $until_date = null): self
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