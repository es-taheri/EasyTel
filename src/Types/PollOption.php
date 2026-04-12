<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about one answer option in a poll.
 * @method self persistent_id(string $value) Unique identifier of the option, persistent on option addition and deletion
 * @method self text(string $value) Option text, 1-100 characters
 * @method self text_entities(array $value) <em>Optional</em>. Special entities that appear in the option <em>text</em>. Currently, only custom emoji entities are allowed in poll option texts
 * @method self voter_count(int $value) Number of users who voted for this option; may be 0 if unknown
 * @method self added_by_user(User $value) <em>Optional</em>. User who added the option; omitted if the option wasn&#39;t added by a user after poll creation
 * @method self added_by_chat(Chat $value) <em>Optional</em>. Chat that added the option; omitted if the option wasn&#39;t added by a chat after poll creation
 * @method self addition_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the option was added; omitted if the option existed in the original poll
 */
class PollOption
{
    public string $persistent_id;
    public string $text;
    public array $text_entities;
    public int $voter_count;
    public User $added_by_user;
    public Chat $added_by_chat;
    public int $addition_date;

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
        if (isset($update['added_by_user'])) $this->added_by_user = new User($update['added_by_user']);
        if (isset($update['added_by_chat'])) $this->added_by_chat = new Chat($update['added_by_chat']);
    }

    /**
     * This object contains information about one answer option in a poll.
     * @param string|null $persistent_id Unique identifier of the option, persistent on option addition and deletion
     * @param string|null $text Option text, 1-100 characters
     * @param array|null $text_entities <em>Optional</em>. Special entities that appear in the option <em>text</em>. Currently, only custom emoji entities are allowed in poll option texts
     * @param int|null $voter_count Number of users who voted for this option; may be 0 if unknown
     * @param User|null $added_by_user <em>Optional</em>. User who added the option; omitted if the option wasn&#39;t added by a user after poll creation
     * @param Chat|null $added_by_chat <em>Optional</em>. Chat that added the option; omitted if the option wasn&#39;t added by a chat after poll creation
     * @param int|null $addition_date <em>Optional</em>. Point in time (Unix timestamp) when the option was added; omitted if the option existed in the original poll
     */
    public static function make(string $persistent_id = null, string $text = null, array $text_entities = null, int $voter_count = null, User $added_by_user = null, Chat $added_by_chat = null, int $addition_date = null): self
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