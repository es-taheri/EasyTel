<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a <a href="https://core.telegram.org/bots/api#game">Game</a>.
 * @method self type(string $value) Type of the result, must be <em>game</em>
 * @method self id(string $value) Unique identifier for this result, 1-64 bytes
 * @method self game_short_name(string $value) Short name of the game
 * @method self reply_markup(InlineKeyboardMarkup $value) <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
 */
class InlineQueryResultGame
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $id;
    /** @var string */
    public string $game_short_name;
    /** @var InlineKeyboardMarkup */
    public InlineKeyboardMarkup $reply_markup;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a <a href="https://core.telegram.org/bots/api#game">Game</a>.
     * @param string|null $type Type of the result, must be <em>game</em>
     * @param string|null $id Unique identifier for this result, 1-64 bytes
     * @param string|null $game_short_name Short name of the game
     * @param InlineKeyboardMarkup|null $reply_markup <em>Optional</em>. <a href="/bots/features#inline-keyboards">Inline keyboard</a> attached to the message
     */
    public static function make(string $type = null, string $id = null, string $game_short_name = null, InlineKeyboardMarkup $reply_markup = null): self
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