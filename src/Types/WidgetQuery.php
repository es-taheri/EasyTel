<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an incoming query from a widget in a message sent by the bot or via the bot. Use <a href="https://core.telegram.org/bots/api#answerwidgetquery">answerWidgetQuery</a> to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 * @method self id(string $value) Unique identifier for this query
 * @method self from(User $value) Sender
 * @method self chat(Chat $value) <em>Optional</em>. Chat with the message containing the widget; omitted if the widget is in an inline message
 * @method self message_id(int $value) <em>Optional</em>. Identifier of the message containing the widget; omitted if the widget is in an inline message
 * @method self inline_message_id(string $value) <em>Optional</em>. Identifier of the inline message containing the widget
 * @method self chat_instance(string $value) Global identifier, uniquely corresponding to the chat to which the message with the widget was sent
 * @method self chat_type(string $value) <em>Optional</em>. For widgets in inline messages, type of the chat to which the message was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”.
 * @method self type(WidgetQueryType $value) Type of the query
 * @method self data(string $value) Data of the query, 1-64 bytes
 * @method self widget_id(string $value) <em>Optional</em>. Unique identifier of the shared widget, which sent the query; omitted if the widget isn't shared
 */
class WidgetQuery
{
    /** @var string */
    public string $id;
    /** @var User */
    public User $from;
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_id;
    /** @var string */
    public string $inline_message_id;
    /** @var string */
    public string $chat_instance;
    /** @var string */
    public string $chat_type;
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
     * This object represents an incoming query from a widget in a message sent by the bot or via the bot. Use <a href="https://core.telegram.org/bots/api#answerwidgetquery">answerWidgetQuery</a> to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
     * @param string|null $id Unique identifier for this query
     * @param User|null $from Sender
     * @param Chat|null $chat <em>Optional</em>. Chat with the message containing the widget; omitted if the widget is in an inline message
     * @param int|null $message_id <em>Optional</em>. Identifier of the message containing the widget; omitted if the widget is in an inline message
     * @param string|null $inline_message_id <em>Optional</em>. Identifier of the inline message containing the widget
     * @param string|null $chat_instance Global identifier, uniquely corresponding to the chat to which the message with the widget was sent
     * @param string|null $chat_type <em>Optional</em>. For widgets in inline messages, type of the chat to which the message was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”.
     * @param WidgetQueryType|null $type Type of the query
     * @param string|null $data Data of the query, 1-64 bytes
     * @param string|null $widget_id <em>Optional</em>. Unique identifier of the shared widget, which sent the query; omitted if the widget isn't shared
     */
    public static function make(string $id = null, User $from = null, Chat $chat = null, int $message_id = null, string $inline_message_id = null, string $chat_instance = null, string $chat_type = null, WidgetQueryType $type = null, string $data = null, string $widget_id = null): self
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