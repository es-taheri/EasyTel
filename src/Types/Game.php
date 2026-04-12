<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
 * @method self title(string $value) Title of the game
 * @method self description(string $value) Description of the game
 * @method self photo(array $value) Photo that will be displayed in the game message in chats.
 * @method self text(string $value) <em>Optional</em>. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls <a href="https://core.telegram.org/bots/api#setgamescore">setGameScore</a>, or manually edited using <a href="https://core.telegram.org/bots/api#editmessagetext">editMessageText</a>. 0-4096 characters.
 * @method self text_entities(array $value) <em>Optional</em>. Special entities that appear in <em>text</em>, such as usernames, URLs, bot commands, etc.
 * @method self animation(Animation $value) <em>Optional</em>. Animation that will be displayed in the game message in chats. Upload via <a href="https://t.me/botfather">BotFather</a>
 */
class Game
{
    public string $title;
    public string $description;
    public array $photo;
    public string $text;
    public array $text_entities;
    public Animation $animation;

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
        if (isset($update['animation'])) $this->animation = new Animation($update['animation']);
    }

    /**
     * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
     * @param string|null $title Title of the game
     * @param string|null $description Description of the game
     * @param array|null $photo Photo that will be displayed in the game message in chats.
     * @param string|null $text <em>Optional</em>. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls <a href="https://core.telegram.org/bots/api#setgamescore">setGameScore</a>, or manually edited using <a href="https://core.telegram.org/bots/api#editmessagetext">editMessageText</a>. 0-4096 characters.
     * @param array|null $text_entities <em>Optional</em>. Special entities that appear in <em>text</em>, such as usernames, URLs, bot commands, etc.
     * @param Animation|null $animation <em>Optional</em>. Animation that will be displayed in the game message in chats. Upload via <a href="https://t.me/botfather">BotFather</a>
     */
    public static function make(string $title = null, string $description = null, array $photo = null, string $text = null, array $text_entities = null, Animation $animation = null): self
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