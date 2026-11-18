<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
 * @method self title(string $value) Title of the game
 * @method self description(string $value) Description of the game
 * @method self photo(PhotoSize[] $value) Photo that will be displayed in the game message in chats
 * @method self text(string $value) <em>Optional</em>. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls <a href="https://core.telegram.org/bots/api#setgamescore">setGameScore</a>, or manually edited using <a href="https://core.telegram.org/bots/api#editmessagetext">editMessageText</a>. 0-4096 characters.
 * @method self text_entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in <em>text</em>, such as usernames, URLs, bot commands, etc.
 * @method self animation(Animation $value) <em>Optional</em>. Animation that will be displayed in the game message in chats. Upload via <a href="https://t.me/botfather">BotFather</a>.
 */
class Game
{
    /** @var string */
    public string $title;
    /** @var string */
    public string $description;
    /** @var PhotoSize[] */
    public array $photo;
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $text_entities;
    /** @var Animation */
    public Animation $animation;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
     * @param string|null $title Title of the game
     * @param string|null $description Description of the game
     * @param PhotoSize[]|null $photo Photo that will be displayed in the game message in chats
     * @param string|null $text <em>Optional</em>. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls <a href="https://core.telegram.org/bots/api#setgamescore">setGameScore</a>, or manually edited using <a href="https://core.telegram.org/bots/api#editmessagetext">editMessageText</a>. 0-4096 characters.
     * @param MessageEntity[]|null $text_entities <em>Optional</em>. Special entities that appear in <em>text</em>, such as usernames, URLs, bot commands, etc.
     * @param Animation|null $animation <em>Optional</em>. Animation that will be displayed in the game message in chats. Upload via <a href="https://t.me/botfather">BotFather</a>.
     */
    public static function make(string $title = null, string $description = null, array $photo = null, string $text = null, array $text_entities = null, Animation $animation = null): self
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