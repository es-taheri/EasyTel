<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a change of a reaction on a message performed by a user.
 * @method self chat(Chat $value) The chat containing the message the user reacted to
 * @method self message_id(int $value) Unique identifier of the message inside the chat
 * @method self user(User $value) <em>Optional</em>. The user that changed the reaction, if the user isn't anonymous
 * @method self actor_chat(Chat $value) <em>Optional</em>. The chat on behalf of which the reaction was changed, if the user is anonymous
 * @method self date(int $value) Date of the change in Unix time
 * @method self old_reaction(ReactionType[] $value) Previous list of reaction types that were set by the user
 * @method self new_reaction(ReactionType[] $value) New list of reaction types that have been set by the user
 */
class MessageReactionUpdated
{
    /** @var Chat */
    public Chat $chat;
    /** @var int */
    public int $message_id;
    /** @var User */
    public User $user;
    /** @var Chat */
    public Chat $actor_chat;
    /** @var int */
    public int $date;
    /** @var ReactionType[] */
    public array $old_reaction;
    /** @var ReactionType[] */
    public array $new_reaction;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a change of a reaction on a message performed by a user.
     * @param Chat|null $chat The chat containing the message the user reacted to
     * @param int|null $message_id Unique identifier of the message inside the chat
     * @param User|null $user <em>Optional</em>. The user that changed the reaction, if the user isn't anonymous
     * @param Chat|null $actor_chat <em>Optional</em>. The chat on behalf of which the reaction was changed, if the user is anonymous
     * @param int|null $date Date of the change in Unix time
     * @param ReactionType[]|null $old_reaction Previous list of reaction types that were set by the user
     * @param ReactionType[]|null $new_reaction New list of reaction types that have been set by the user
     */
    public static function make(Chat $chat = null, int $message_id = null, User $user = null, Chat $actor_chat = null, int $date = null, array $old_reaction = null, array $new_reaction = null): self
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