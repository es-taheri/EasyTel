<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
 * @method self query(string $value) <em>Optional</em>. The default inline query to be inserted in the input field. If left empty, only the bot's username will be inserted.
 * @method self allow_user_chats(bool $value) <em>Optional</em>. <em>True</em>, if private chats with users can be chosen
 * @method self allow_bot_chats(bool $value) <em>Optional</em>. <em>True</em>, if private chats with bots can be chosen
 * @method self allow_group_chats(bool $value) <em>Optional</em>. <em>True</em>, if group and supergroup chats can be chosen
 * @method self allow_channel_chats(bool $value) <em>Optional</em>. <em>True</em>, if channel chats can be chosen
 */
class SwitchInlineQueryChosenChat
{
    /** @var string */
    public string $query;
    /** @var bool */
    public bool $allow_user_chats;
    /** @var bool */
    public bool $allow_bot_chats;
    /** @var bool */
    public bool $allow_group_chats;
    /** @var bool */
    public bool $allow_channel_chats;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
     * @param string|null $query <em>Optional</em>. The default inline query to be inserted in the input field. If left empty, only the bot's username will be inserted.
     * @param bool|null $allow_user_chats <em>Optional</em>. <em>True</em>, if private chats with users can be chosen
     * @param bool|null $allow_bot_chats <em>Optional</em>. <em>True</em>, if private chats with bots can be chosen
     * @param bool|null $allow_group_chats <em>Optional</em>. <em>True</em>, if group and supergroup chats can be chosen
     * @param bool|null $allow_channel_chats <em>Optional</em>. <em>True</em>, if channel chats can be chosen
     */
    public static function make(string $query = null, bool $allow_user_chats = null, bool $allow_bot_chats = null, bool $allow_group_chats = null, bool $allow_channel_chats = null): self
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