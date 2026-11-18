<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a task in a checklist.
 * @method self id(int $value) Unique identifier of the task
 * @method self text(string $value) Text of the task
 * @method self text_entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the task text
 * @method self completed_by_user(User $value) <em>Optional</em>. User that completed the task; omitted if the task wasn't completed by a user
 * @method self completed_by_chat(Chat $value) <em>Optional</em>. Chat that completed the task; omitted if the task wasn't completed by a chat
 * @method self completion_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn't completed
 */
class ChecklistTask
{
    /** @var int */
    public int $id;
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $text_entities;
    /** @var User */
    public User $completed_by_user;
    /** @var Chat */
    public Chat $completed_by_chat;
    /** @var int */
    public int $completion_date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a task in a checklist.
     * @param int|null $id Unique identifier of the task
     * @param string|null $text Text of the task
     * @param MessageEntity[]|null $text_entities <em>Optional</em>. Special entities that appear in the task text
     * @param User|null $completed_by_user <em>Optional</em>. User that completed the task; omitted if the task wasn't completed by a user
     * @param Chat|null $completed_by_chat <em>Optional</em>. Chat that completed the task; omitted if the task wasn't completed by a chat
     * @param int|null $completion_date <em>Optional</em>. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn't completed
     */
    public static function make(int $id = null, string $text = null, array $text_entities = null, User $completed_by_user = null, Chat $completed_by_chat = null, int $completion_date = null): self
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