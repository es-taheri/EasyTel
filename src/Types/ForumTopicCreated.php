<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a service message about a new forum topic created in the chat.
 * @method self name(string $value) Name of the topic
 * @method self icon_color(int $value) Color of the topic icon in RGB format
 * @method self icon_custom_emoji_id(string $value) <em>Optional</em>. Unique identifier of the custom emoji shown as the topic icon
 * @method self is_name_implicit(bool $value) <em>Optional</em>. <em>True</em>, if the name of the topic wasn't specified explicitly by its creator and likely needs to be changed by the bot
 */
class ForumTopicCreated
{
    /** @var string */
    public string $name;
    /** @var int */
    public int $icon_color;
    /** @var string */
    public string $icon_custom_emoji_id;
    /** @var bool */
    public bool $is_name_implicit;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a service message about a new forum topic created in the chat.
     * @param string|null $name Name of the topic
     * @param int|null $icon_color Color of the topic icon in RGB format
     * @param string|null $icon_custom_emoji_id <em>Optional</em>. Unique identifier of the custom emoji shown as the topic icon
     * @param bool|null $is_name_implicit <em>Optional</em>. <em>True</em>, if the name of the topic wasn't specified explicitly by its creator and likely needs to be changed by the bot
     */
    public static function make(string $name = null, int $icon_color = null, string $icon_custom_emoji_id = null, bool $is_name_implicit = null): self
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