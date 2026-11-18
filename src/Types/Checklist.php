<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a checklist.
 * @method self title(string $value) Title of the checklist
 * @method self title_entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the checklist title
 * @method self tasks(ChecklistTask[] $value) List of tasks in the checklist
 * @method self others_can_add_tasks(bool $value) <em>Optional</em>. <em>True</em>, if users other than the creator of the list can add tasks to the list
 * @method self others_can_mark_tasks_as_done(bool $value) <em>Optional</em>. <em>True</em>, if users other than the creator of the list can mark tasks as done or not done
 */
class Checklist
{
    /** @var string */
    public string $title;
    /** @var MessageEntity[] */
    public array $title_entities;
    /** @var ChecklistTask[] */
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
     * Describes a checklist.
     * @param string|null $title Title of the checklist
     * @param MessageEntity[]|null $title_entities <em>Optional</em>. Special entities that appear in the checklist title
     * @param ChecklistTask[]|null $tasks List of tasks in the checklist
     * @param bool|null $others_can_add_tasks <em>Optional</em>. <em>True</em>, if users other than the creator of the list can add tasks to the list
     * @param bool|null $others_can_mark_tasks_as_done <em>Optional</em>. <em>True</em>, if users other than the creator of the list can mark tasks as done or not done
     */
    public static function make(string $title = null, array $title_entities = null, array $tasks = null, bool $others_can_add_tasks = null, bool $others_can_mark_tasks_as_done = null): self
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