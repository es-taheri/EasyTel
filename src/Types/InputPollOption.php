<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about one answer option in a poll to be sent.
 * @method self text(string $value) Option text, 1-100 characters
 * @method self text_parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Currently, only custom emoji entities are allowed.
 * @method self text_entities(MessageEntity[] $value) <em>Optional</em>. A JSON-serialized list of special entities that appear in the poll option text. It can be specified instead of <em>text_parse_mode</em>.
 * @method self media(InputPollOptionMedia $value) <em>Optional</em>. Media added to the poll option
 */
class InputPollOption
{
    /** @var string */
    public string $text;
    /** @var string */
    public string $text_parse_mode;
    /** @var MessageEntity[] */
    public array $text_entities;
    /** @var InputPollOptionMedia */
    public InputPollOptionMedia $media;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about one answer option in a poll to be sent.
     * @param string|null $text Option text, 1-100 characters
     * @param string|null $text_parse_mode <em>Optional</em>. Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Currently, only custom emoji entities are allowed.
     * @param MessageEntity[]|null $text_entities <em>Optional</em>. A JSON-serialized list of special entities that appear in the poll option text. It can be specified instead of <em>text_parse_mode</em>.
     * @param InputPollOptionMedia|null $media <em>Optional</em>. Media added to the poll option
     */
    public static function make(string $text = null, string $text_parse_mode = null, array $text_entities = null, InputPollOptionMedia $media = null): self
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