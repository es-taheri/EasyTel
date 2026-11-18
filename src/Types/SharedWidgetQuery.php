<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an incoming query from a forwarded message with a shared widget created by the bot. Use <a href="https://core.telegram.org/bots/api#answerwidgetquery">answerWidgetQuery</a> to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 * @method self id(string $value) Unique identifier for this query
 * @method self from(User $value) Sender
 * @method self type(WidgetQueryType $value) Type of the query
 * @method self data(string $value) Data of the query, 1-64 bytes
 * @method self widget_id(string $value) Unique identifier of the shared widget, which sent the query
 */
class SharedWidgetQuery
{
    /** @var string */
    public string $id;
    /** @var User */
    public User $from;
    /** @var WidgetQueryType */
    public WidgetQueryType $type;
    /** @var string */
    public string $data;
    /** @var string */
    public string $widget_id;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents an incoming query from a forwarded message with a shared widget created by the bot. Use <a href="https://core.telegram.org/bots/api#answerwidgetquery">answerWidgetQuery</a> to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
     * @param string|null $id Unique identifier for this query
     * @param User|null $from Sender
     * @param WidgetQueryType|null $type Type of the query
     * @param string|null $data Data of the query, 1-64 bytes
     * @param string|null $widget_id Unique identifier of the shared widget, which sent the query
     */
    public static function make(string $id = null, User $from = null, WidgetQueryType $type = null, string $data = null, string $widget_id = null): self
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