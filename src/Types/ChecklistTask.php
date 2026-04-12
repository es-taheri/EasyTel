<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a task in a checklist.
 * @method self id(int $value) Unique identifier of the task
 * @method self text(string $value) Text of the task
 * @method self text_entities(array $value) <em>Optional</em>. Special entities that appear in the task text
 * @method self completed_by_user(User $value) <em>Optional</em>. User that completed the task; omitted if the task wasn&#39;t completed by a user
 * @method self completed_by_chat(Chat $value) <em>Optional</em>. Chat that completed the task; omitted if the task wasn&#39;t completed by a chat
 * @method self completion_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn&#39;t completed
 */
class ChecklistTask
{
    public int $id;
    public string $text;
    public array $text_entities;
    public User $completed_by_user;
    public Chat $completed_by_chat;
    public int $completion_date;

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
        if (isset($update['completed_by_user'])) $this->completed_by_user = new User($update['completed_by_user']);
        if (isset($update['completed_by_chat'])) $this->completed_by_chat = new Chat($update['completed_by_chat']);
    }

    /**
     * Describes a task in a checklist.
     * @param int|null $id Unique identifier of the task
     * @param string|null $text Text of the task
     * @param array|null $text_entities <em>Optional</em>. Special entities that appear in the task text
     * @param User|null $completed_by_user <em>Optional</em>. User that completed the task; omitted if the task wasn&#39;t completed by a user
     * @param Chat|null $completed_by_chat <em>Optional</em>. Chat that completed the task; omitted if the task wasn&#39;t completed by a chat
     * @param int|null $completion_date <em>Optional</em>. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn&#39;t completed
     */
    public static function make(int $id = null, string $text = null, array $text_entities = null, User $completed_by_user = null, Chat $completed_by_chat = null, int $completion_date = null): self
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