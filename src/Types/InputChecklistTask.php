<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a task to add to a checklist.
 * @method self id(int $value) Unique identifier of the task; must be positive and unique among all task identifiers currently present in the checklist
 * @method self text(string $value) Text of the task; 1-100 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self text_entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in the text, which can be specified instead of parse_mode. Currently, only <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities are allowed.
 */
class InputChecklistTask
{
    /** @var int */
    public int $id;
    /** @var string */
    public string $text;
    /** @var string */
    public string $parse_mode;
    /** @var MessageEntity[] */
    public array $text_entities;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a task to add to a checklist.
     * @param int|null $id Unique identifier of the task; must be positive and unique among all task identifiers currently present in the checklist
     * @param string|null $text Text of the task; 1-100 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $text_entities <em>Optional</em>. List of special entities that appear in the text, which can be specified instead of parse_mode. Currently, only <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities are allowed.
     */
    public static function make(int $id = null, string $text = null, string $parse_mode = null, array $text_entities = null): self
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