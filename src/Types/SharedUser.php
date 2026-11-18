<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about a user that was shared with the bot using a <a href="https://core.telegram.org/bots/api#keyboardbuttonrequestusers">KeyboardButtonRequestUsers</a> button.
 * @method self user_id(int $value) Identifier of the shared user. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing these identifiers. The bot may not have access to the user and could be unable to use this identifier, unless the user is already known to the bot by some other means.
 * @method self first_name(string $value) <em>Optional</em>. First name of the user, if the name was requested by the bot
 * @method self last_name(string $value) <em>Optional</em>. Last name of the user, if the name was requested by the bot
 * @method self username(string $value) <em>Optional</em>. Username of the user, if the username was requested by the bot
 * @method self photo(PhotoSize[] $value) <em>Optional</em>. Available sizes of the chat photo, if the photo was requested by the bot
 */
class SharedUser
{
    /** @var int */
    public int $user_id;
    /** @var string */
    public string $first_name;
    /** @var string */
    public string $last_name;
    /** @var string */
    public string $username;
    /** @var PhotoSize[] */
    public array $photo;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about a user that was shared with the bot using a <a href="https://core.telegram.org/bots/api#keyboardbuttonrequestusers">KeyboardButtonRequestUsers</a> button.
     * @param int|null $user_id Identifier of the shared user. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing these identifiers. The bot may not have access to the user and could be unable to use this identifier, unless the user is already known to the bot by some other means.
     * @param string|null $first_name <em>Optional</em>. First name of the user, if the name was requested by the bot
     * @param string|null $last_name <em>Optional</em>. Last name of the user, if the name was requested by the bot
     * @param string|null $username <em>Optional</em>. Username of the user, if the username was requested by the bot
     * @param PhotoSize[]|null $photo <em>Optional</em>. Available sizes of the chat photo, if the photo was requested by the bot
     */
    public static function make(int $user_id = null, string $first_name = null, string $last_name = null, string $username = null, array $photo = null): self
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