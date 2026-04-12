<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
 * @method self query(string $value) <em>Optional</em>. The default inline query to be inserted in the input field. If left empty, only the bot&#39;s username will be inserted
 * @method self allow_user_chats(bool $value) <em>Optional</em>. <em>True</em>, if private chats with users can be chosen
 * @method self allow_bot_chats(bool $value) <em>Optional</em>. <em>True</em>, if private chats with bots can be chosen
 * @method self allow_group_chats(bool $value) <em>Optional</em>. <em>True</em>, if group and supergroup chats can be chosen
 * @method self allow_channel_chats(bool $value) <em>Optional</em>. <em>True</em>, if channel chats can be chosen
 */
class SwitchInlineQueryChosenChat
{
    public string $query;
    public bool $allow_user_chats;
    public bool $allow_bot_chats;
    public bool $allow_group_chats;
    public bool $allow_channel_chats;

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
     * This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
     * @param string|null $query <em>Optional</em>. The default inline query to be inserted in the input field. If left empty, only the bot&#39;s username will be inserted
     * @param bool|null $allow_user_chats <em>Optional</em>. <em>True</em>, if private chats with users can be chosen
     * @param bool|null $allow_bot_chats <em>Optional</em>. <em>True</em>, if private chats with bots can be chosen
     * @param bool|null $allow_group_chats <em>Optional</em>. <em>True</em>, if group and supergroup chats can be chosen
     * @param bool|null $allow_channel_chats <em>Optional</em>. <em>True</em>, if channel chats can be chosen
     */
    public static function make(string $query = null, bool $allow_user_chats = null, bool $allow_bot_chats = null, bool $allow_group_chats = null, bool $allow_channel_chats = null): self
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