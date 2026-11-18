<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a checklist to create.
 * @method self title(string $value) Title of the checklist; 1-255 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the title. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self title_entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in the title, which can be specified instead of parse_mode. Currently, only <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities are allowed.
 * @method self tasks(InputChecklistTask[] $value) List of 1-30 tasks in the checklist
 * @method self others_can_add_tasks(bool $value) <em>Optional</em>. Pass <em>True</em> if other users can add tasks to the checklist
 * @method self others_can_mark_tasks_as_done(bool $value) <em>Optional</em>. Pass <em>True</em> if other users can mark tasks as done or not done in the checklist
 */
class InputChecklist
{
    /** @var string */
    public string $title;
    /** @var string */
    public string $parse_mode;
    /** @var MessageEntity[] */
    public array $title_entities;
    /** @var InputChecklistTask[] */
    public array $tasks;
    /** @var bool */
    public bool $others_can_add_tasks;
    /** @var bool */
    public bool $others_can_mark_tasks_as_done;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a checklist to create.
     * @param string|null $title Title of the checklist; 1-255 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the title. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $title_entities <em>Optional</em>. List of special entities that appear in the title, which can be specified instead of parse_mode. Currently, only <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities are allowed.
     * @param InputChecklistTask[]|null $tasks List of 1-30 tasks in the checklist
     * @param bool|null $others_can_add_tasks <em>Optional</em>. Pass <em>True</em> if other users can add tasks to the checklist
     * @param bool|null $others_can_mark_tasks_as_done <em>Optional</em>. Pass <em>True</em> if other users can mark tasks as done or not done in the checklist
     */
    public static function make(string $title = null, string $parse_mode = null, array $title_entities = null, array $tasks = null, bool $others_can_add_tasks = null, bool $others_can_mark_tasks_as_done = null): self
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