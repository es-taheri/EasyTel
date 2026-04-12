<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a checklist.
 * @method self title(string $value) Title of the checklist
 * @method self title_entities(array $value) <em>Optional</em>. Special entities that appear in the checklist title
 * @method self tasks(array $value) List of tasks in the checklist
 * @method self others_can_add_tasks(True $value) <em>Optional</em>. <em>True</em>, if users other than the creator of the list can add tasks to the list
 * @method self others_can_mark_tasks_as_done(True $value) <em>Optional</em>. <em>True</em>, if users other than the creator of the list can mark tasks as done or not done
 */
class Checklist
{
    public string $title;
    public array $title_entities;
    public array $tasks;
    public True $others_can_add_tasks;
    public True $others_can_mark_tasks_as_done;

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
        
    }

    /**
     * Describes a checklist.
     * @param string|null $title Title of the checklist
     * @param array|null $title_entities <em>Optional</em>. Special entities that appear in the checklist title
     * @param array|null $tasks List of tasks in the checklist
     * @param True|null $others_can_add_tasks <em>Optional</em>. <em>True</em>, if users other than the creator of the list can add tasks to the list
     * @param True|null $others_can_mark_tasks_as_done <em>Optional</em>. <em>True</em>, if users other than the creator of the list can mark tasks as done or not done
     */
    public static function make(string $title = null, array $title_entities = null, array $tasks = null, True $others_can_add_tasks = null, True $others_can_mark_tasks_as_done = null): self
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