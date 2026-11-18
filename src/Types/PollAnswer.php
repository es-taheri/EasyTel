<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an answer of a user in a non-anonymous poll.
 * @method self poll_id(string $value) Unique poll identifier
 * @method self voter_chat(Chat $value) <em>Optional</em>. The chat that changed the answer to the poll, if the voter is anonymous
 * @method self user(User $value) <em>Optional</em>. The user that changed the answer to the poll, if the voter isn't anonymous
 * @method self option_ids(int[] $value) 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
 * @method self option_persistent_ids(string[] $value) Persistent identifiers of the chosen answer options. May be empty if the vote was retracted.
 */
class PollAnswer
{
    /** @var string */
    public string $poll_id;
    /** @var Chat */
    public Chat $voter_chat;
    /** @var User */
    public User $user;
    /** @var int[] */
    public array $option_ids;
    /** @var string[] */
    public array $option_persistent_ids;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents an answer of a user in a non-anonymous poll.
     * @param string|null $poll_id Unique poll identifier
     * @param Chat|null $voter_chat <em>Optional</em>. The chat that changed the answer to the poll, if the voter is anonymous
     * @param User|null $user <em>Optional</em>. The user that changed the answer to the poll, if the voter isn't anonymous
     * @param int[]|null $option_ids 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
     * @param string[]|null $option_persistent_ids Persistent identifiers of the chosen answer options. May be empty if the vote was retracted.
     */
    public static function make(string $poll_id = null, Chat $voter_chat = null, User $user = null, array $option_ids = null, array $option_persistent_ids = null): self
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