<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a forum topic.
 * @method self message_thread_id(int $value) Unique identifier of the forum topic
 * @method self name(string $value) Name of the topic
 * @method self icon_color(int $value) Color of the topic icon in RGB format
 * @method self icon_custom_emoji_id(string $value) <em>Optional</em>. Unique identifier of the custom emoji shown as the topic icon
 * @method self is_name_implicit(True $value) <em>Optional</em>. <em>True</em>, if the name of the topic wasn&#39;t specified explicitly by its creator and likely needs to be changed by the bot
 */
class ForumTopic
{
    public int $message_thread_id;
    public string $name;
    public int $icon_color;
    public string $icon_custom_emoji_id;
    public True $is_name_implicit;

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
     * This object represents a forum topic.
     * @param int|null $message_thread_id Unique identifier of the forum topic
     * @param string|null $name Name of the topic
     * @param int|null $icon_color Color of the topic icon in RGB format
     * @param string|null $icon_custom_emoji_id <em>Optional</em>. Unique identifier of the custom emoji shown as the topic icon
     * @param True|null $is_name_implicit <em>Optional</em>. <em>True</em>, if the name of the topic wasn&#39;t specified explicitly by its creator and likely needs to be changed by the bot
     */
    public static function make(int $message_thread_id = null, string $name = null, int $icon_color = null, string $icon_custom_emoji_id = null, True $is_name_implicit = null): self
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