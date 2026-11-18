<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about checklist tasks marked as done or not done.
 * @method self checklist_message(Message $value) <em>Optional</em>. Message containing the checklist whose tasks were marked as done or not done. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
 * @method self marked_as_done_task_ids(int[] $value) <em>Optional</em>. Identifiers of the tasks that were marked as done
 * @method self marked_as_not_done_task_ids(int[] $value) <em>Optional</em>. Identifiers of the tasks that were marked as not done
 */
class ChecklistTasksDone
{
    /** @var Message */
    public Message $checklist_message;
    /** @var int[] */
    public array $marked_as_done_task_ids;
    /** @var int[] */
    public array $marked_as_not_done_task_ids;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a service message about checklist tasks marked as done or not done.
     * @param Message|null $checklist_message <em>Optional</em>. Message containing the checklist whose tasks were marked as done or not done. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
     * @param int[]|null $marked_as_done_task_ids <em>Optional</em>. Identifiers of the tasks that were marked as done
     * @param int[]|null $marked_as_not_done_task_ids <em>Optional</em>. Identifiers of the tasks that were marked as not done
     */
    public static function make(Message $checklist_message = null, array $marked_as_done_task_ids = null, array $marked_as_not_done_task_ids = null): self
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