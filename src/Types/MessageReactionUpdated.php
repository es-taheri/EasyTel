<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a change of a reaction on a message performed by a user.
 * @method self chat(Chat $value) The chat containing the message the user reacted to
 * @method self message_id(int $value) Unique identifier of the message inside the chat
 * @method self user(User $value) <em>Optional</em>. The user that changed the reaction, if the user isn&#39;t anonymous
 * @method self actor_chat(Chat $value) <em>Optional</em>. The chat on behalf of which the reaction was changed, if the user is anonymous
 * @method self date(int $value) Date of the change in Unix time
 * @method self old_reaction(array $value) Previous list of reaction types that were set by the user
 * @method self new_reaction(array $value) New list of reaction types that have been set by the user
 */
class MessageReactionUpdated
{
    public Chat $chat;
    public int $message_id;
    public User $user;
    public Chat $actor_chat;
    public int $date;
    public array $old_reaction;
    public array $new_reaction;

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
        if (isset($update['chat'])) $this->chat = new Chat($update['chat']);
        if (isset($update['user'])) $this->user = new User($update['user']);
        if (isset($update['actor_chat'])) $this->actor_chat = new Chat($update['actor_chat']);
    }

    /**
     * This object represents a change of a reaction on a message performed by a user.
     * @param Chat|null $chat The chat containing the message the user reacted to
     * @param int|null $message_id Unique identifier of the message inside the chat
     * @param User|null $user <em>Optional</em>. The user that changed the reaction, if the user isn&#39;t anonymous
     * @param Chat|null $actor_chat <em>Optional</em>. The chat on behalf of which the reaction was changed, if the user is anonymous
     * @param int|null $date Date of the change in Unix time
     * @param array|null $old_reaction Previous list of reaction types that were set by the user
     * @param array|null $new_reaction New list of reaction types that have been set by the user
     */
    public static function make(Chat $chat = null, int $message_id = null, User $user = null, Chat $actor_chat = null, int $date = null, array $old_reaction = null, array $new_reaction = null): self
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