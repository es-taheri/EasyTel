<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about one answer option in a poll.
 * @method self persistent_id(string $value) Unique identifier of the option, persistent on option addition and deletion
 * @method self text(string $value) Option text, 1-100 characters
 * @method self text_entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the option <em>text</em>. Currently, only custom emoji entities are allowed in poll option texts
 * @method self media(PollMedia $value) <em>Optional</em>. Media added to the poll option
 * @method self voter_count(int $value) Number of users who voted for this option; may be 0 if unknown
 * @method self added_by_user(User $value) <em>Optional</em>. User who added the option; omitted if the option wasn't added by a user after poll creation
 * @method self added_by_chat(Chat $value) <em>Optional</em>. Chat that added the option; omitted if the option wasn't added by a chat after poll creation
 * @method self addition_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the option was added; omitted if the option existed in the original poll
 */
class PollOption
{
    /** @var string */
    public string $persistent_id;
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $text_entities;
    /** @var PollMedia */
    public PollMedia $media;
    /** @var int */
    public int $voter_count;
    /** @var User */
    public User $added_by_user;
    /** @var Chat */
    public Chat $added_by_chat;
    /** @var int */
    public int $addition_date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about one answer option in a poll.
     * @param string|null $persistent_id Unique identifier of the option, persistent on option addition and deletion
     * @param string|null $text Option text, 1-100 characters
     * @param MessageEntity[]|null $text_entities <em>Optional</em>. Special entities that appear in the option <em>text</em>. Currently, only custom emoji entities are allowed in poll option texts
     * @param PollMedia|null $media <em>Optional</em>. Media added to the poll option
     * @param int|null $voter_count Number of users who voted for this option; may be 0 if unknown
     * @param User|null $added_by_user <em>Optional</em>. User who added the option; omitted if the option wasn't added by a user after poll creation
     * @param Chat|null $added_by_chat <em>Optional</em>. Chat that added the option; omitted if the option wasn't added by a chat after poll creation
     * @param int|null $addition_date <em>Optional</em>. Point in time (Unix timestamp) when the option was added; omitted if the option existed in the original poll
     */
    public static function make(string $persistent_id = null, string $text = null, array $text_entities = null, PollMedia $media = null, int $voter_count = null, User $added_by_user = null, Chat $added_by_chat = null, int $addition_date = null): self
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