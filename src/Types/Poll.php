<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about a poll.
 * @method self id(string $value) Unique poll identifier
 * @method self question(string $value) Poll question, 1-300 characters
 * @method self question_entities(array $value) <em>Optional</em>. Special entities that appear in the <em>question</em>. Currently, only custom emoji entities are allowed in poll questions
 * @method self options(array $value) List of poll options
 * @method self total_voter_count(int $value) Total number of users that voted in the poll
 * @method self is_closed(bool $value) <em>True</em>, if the poll is closed
 * @method self is_anonymous(bool $value) <em>True</em>, if the poll is anonymous
 * @method self type(string $value) Poll type, currently can be “regular” or “quiz”
 * @method self allows_multiple_answers(bool $value) <em>True</em>, if the poll allows multiple answers
 * @method self allows_revoting(bool $value) <em>True</em>, if the poll allows to change the chosen answer options
 * @method self correct_option_ids(array $value) <em>Optional</em>. Array of 0-based identifiers of the correct answer options. Available only for polls in quiz mode which are closed or were sent (not forwarded) by the bot or to the private chat with the bot.
 * @method self explanation(string $value) <em>Optional</em>. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
 * @method self explanation_entities(array $value) <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the <em>explanation</em>
 * @method self open_period(int $value) <em>Optional</em>. Amount of time in seconds the poll will be active after creation
 * @method self close_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the poll will be automatically closed
 * @method self description(string $value) <em>Optional</em>. Description of the poll; for polls inside the <a href="https://core.telegram.org/bots/api#message">Message</a> object only
 * @method self description_entities(array $value) <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the description
 */
class Poll
{
    public string $id;
    public string $question;
    public array $question_entities;
    public array $options;
    public int $total_voter_count;
    public bool $is_closed;
    public bool $is_anonymous;
    public string $type;
    public bool $allows_multiple_answers;
    public bool $allows_revoting;
    public array $correct_option_ids;
    public string $explanation;
    public array $explanation_entities;
    public int $open_period;
    public int $close_date;
    public string $description;
    public array $description_entities;

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
     * This object contains information about a poll.
     * @param string|null $id Unique poll identifier
     * @param string|null $question Poll question, 1-300 characters
     * @param array|null $question_entities <em>Optional</em>. Special entities that appear in the <em>question</em>. Currently, only custom emoji entities are allowed in poll questions
     * @param array|null $options List of poll options
     * @param int|null $total_voter_count Total number of users that voted in the poll
     * @param bool|null $is_closed <em>True</em>, if the poll is closed
     * @param bool|null $is_anonymous <em>True</em>, if the poll is anonymous
     * @param string|null $type Poll type, currently can be “regular” or “quiz”
     * @param bool|null $allows_multiple_answers <em>True</em>, if the poll allows multiple answers
     * @param bool|null $allows_revoting <em>True</em>, if the poll allows to change the chosen answer options
     * @param array|null $correct_option_ids <em>Optional</em>. Array of 0-based identifiers of the correct answer options. Available only for polls in quiz mode which are closed or were sent (not forwarded) by the bot or to the private chat with the bot.
     * @param string|null $explanation <em>Optional</em>. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
     * @param array|null $explanation_entities <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the <em>explanation</em>
     * @param int|null $open_period <em>Optional</em>. Amount of time in seconds the poll will be active after creation
     * @param int|null $close_date <em>Optional</em>. Point in time (Unix timestamp) when the poll will be automatically closed
     * @param string|null $description <em>Optional</em>. Description of the poll; for polls inside the <a href="https://core.telegram.org/bots/api#message">Message</a> object only
     * @param array|null $description_entities <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the description
     */
    public static function make(string $id = null, string $question = null, array $question_entities = null, array $options = null, int $total_voter_count = null, bool $is_closed = null, bool $is_anonymous = null, string $type = null, bool $allows_multiple_answers = null, bool $allows_revoting = null, array $correct_option_ids = null, string $explanation = null, array $explanation_entities = null, int $open_period = null, int $close_date = null, string $description = null, array $description_entities = null): self
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