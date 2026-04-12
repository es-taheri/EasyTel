<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about checklist tasks marked as done or not done.
 * @method self checklist_message(Message $value) <em>Optional</em>. Message containing the checklist whose tasks were marked as done or not done. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
 * @method self marked_as_done_task_ids(array $value) <em>Optional</em>. Identifiers of the tasks that were marked as done
 * @method self marked_as_not_done_task_ids(array $value) <em>Optional</em>. Identifiers of the tasks that were marked as not done
 */
class ChecklistTasksDone
{
    public Message $checklist_message;
    public array $marked_as_done_task_ids;
    public array $marked_as_not_done_task_ids;

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
        if (isset($update['checklist_message'])) $this->checklist_message = new Message($update['checklist_message']);
    }

    /**
     * Describes a service message about checklist tasks marked as done or not done.
     * @param Message|null $checklist_message <em>Optional</em>. Message containing the checklist whose tasks were marked as done or not done. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
     * @param array|null $marked_as_done_task_ids <em>Optional</em>. Identifiers of the tasks that were marked as done
     * @param array|null $marked_as_not_done_task_ids <em>Optional</em>. Identifiers of the tasks that were marked as not done
     */
    public static function make(Message $checklist_message = null, array $marked_as_done_task_ids = null, array $marked_as_not_done_task_ids = null): self
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