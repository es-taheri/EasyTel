<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an answer of a user in a non-anonymous poll.
 * @method self poll_id(string $value) Unique poll identifier
 * @method self voter_chat(Chat $value) <em>Optional</em>. The chat that changed the answer to the poll, if the voter is anonymous
 * @method self user(User $value) <em>Optional</em>. The user that changed the answer to the poll, if the voter isn&#39;t anonymous
 * @method self option_ids(array $value) 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
 * @method self option_persistent_ids(array $value) Persistent identifiers of the chosen answer options. May be empty if the vote was retracted.
 */
class PollAnswer
{
    public string $poll_id;
    public Chat $voter_chat;
    public User $user;
    public array $option_ids;
    public array $option_persistent_ids;

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
        if (isset($update['voter_chat'])) $this->voter_chat = new Chat($update['voter_chat']);
        if (isset($update['user'])) $this->user = new User($update['user']);
    }

    /**
     * This object represents an answer of a user in a non-anonymous poll.
     * @param string|null $poll_id Unique poll identifier
     * @param Chat|null $voter_chat <em>Optional</em>. The chat that changed the answer to the poll, if the voter is anonymous
     * @param User|null $user <em>Optional</em>. The user that changed the answer to the poll, if the voter isn&#39;t anonymous
     * @param array|null $option_ids 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
     * @param array|null $option_persistent_ids Persistent identifiers of the chosen answer options. May be empty if the vote was retracted.
     */
    public static function make(string $poll_id = null, Chat $voter_chat = null, User $user = null, array $option_ids = null, array $option_persistent_ids = null): self
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