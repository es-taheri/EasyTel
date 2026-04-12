<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that owns the chat and has all administrator privileges.
 * @method self status(string $value) The member&#39;s status in the chat, always “creator”
 * @method self user(User $value) Information about the user
 * @method self is_anonymous(bool $value) <em>True</em>, if the user&#39;s presence in the chat is hidden
 * @method self custom_title(string $value) <em>Optional</em>. Custom title for this user
 */
class ChatMemberOwner
{
    public string $status;
    public User $user;
    public bool $is_anonymous;
    public string $custom_title;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
    }

    /**
     * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that owns the chat and has all administrator privileges.
     * @param string|null $status The member&#39;s status in the chat, always “creator”
     * @param User|null $user Information about the user
     * @param bool|null $is_anonymous <em>True</em>, if the user&#39;s presence in the chat is hidden
     * @param string|null $custom_title <em>Optional</em>. Custom title for this user
     */
    public static function make(string $status = null, User $user = null, bool $is_anonymous = null, string $custom_title = null): self
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