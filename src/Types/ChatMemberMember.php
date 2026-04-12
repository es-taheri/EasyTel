<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has no additional privileges or restrictions.
 * @method self status(string $value) The member&#39;s status in the chat, always “member”
 * @method self tag(string $value) <em>Optional</em>. Tag of the member
 * @method self user(User $value) Information about the user
 * @method self until_date(int $value) <em>Optional</em>. Date when the user&#39;s subscription will expire; Unix time
 */
class ChatMemberMember
{
    public string $status;
    public string $tag;
    public User $user;
    public int $until_date;

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
     * Represents a <a href="https://core.telegram.org/bots/api#chatmember">chat member</a> that has no additional privileges or restrictions.
     * @param string|null $status The member&#39;s status in the chat, always “member”
     * @param string|null $tag <em>Optional</em>. Tag of the member
     * @param User|null $user Information about the user
     * @param int|null $until_date <em>Optional</em>. Date when the user&#39;s subscription will expire; Unix time
     */
    public static function make(string $status = null, string $tag = null, User $user = null, int $until_date = null): self
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