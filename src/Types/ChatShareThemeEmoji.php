<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The theme with emoji placed on a background.
 * @method self type(string $value) Type of the theme, always “emoji”
 * @method self emojis(EmojiType[] $value) The emoji; 0-6 regular or custom emoji
 * @method self background_backdrop_id(int $value) <em>Optional</em>. Identifier of the backdrop used as the background; omitted if the default background is used. Use <a href="https://core.telegram.org/bots/api#getchatsharethemebackdrops">getChatShareThemeBackdrops</a> to get details about the backdrop.
 */
class ChatShareThemeEmoji
{
    /** @var string */
    public string $type;
    /** @var EmojiType[] */
    public array $emojis;
    /** @var int */
    public int $background_backdrop_id;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The theme with emoji placed on a background.
     * @param string|null $type Type of the theme, always “emoji”
     * @param EmojiType[]|null $emojis The emoji; 0-6 regular or custom emoji
     * @param int|null $background_backdrop_id <em>Optional</em>. Identifier of the backdrop used as the background; omitted if the default background is used. Use <a href="https://core.telegram.org/bots/api#getchatsharethemebackdrops">getChatShareThemeBackdrops</a> to get details about the backdrop.
     */
    public static function make(string $type = null, array $emojis = null, int $background_backdrop_id = null): self
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