<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about a poll.
 * @method self id(string $value) Unique poll identifier
 * @method self question(string $value) Poll question, 1-300 characters
 * @method self question_entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the <em>question</em>. Currently, only custom emoji entities are allowed in poll questions
 * @method self options(PollOption[] $value) List of poll options
 * @method self total_voter_count(int $value) Total number of users that voted in the poll
 * @method self is_closed(bool $value) <em>True</em>, if the poll is closed
 * @method self is_anonymous(bool $value) <em>True</em>, if the poll is anonymous
 * @method self type(string $value) Poll type, currently can be “regular” or “quiz”
 * @method self allows_multiple_answers(bool $value) <em>True</em>, if the poll allows multiple answers
 * @method self allows_revoting(bool $value) <em>True</em>, if the poll allows to change the chosen answer options
 * @method self members_only(bool $value) <em>True</em> if voting is limited to users who have been members of the chat where the poll was originally sent for more than 24 hours
 * @method self country_codes(string[] $value) <em>Optional</em>. A list of two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which users can vote in the poll. The country code “FT” is used for users with anonymous numbers. If omitted, then users from any country can participate in the poll.
 * @method self correct_option_ids(int[] $value) <em>Optional</em>. Array of 0-based identifiers of the correct answer options. Available only for polls in quiz mode which are closed or were sent (not forwarded) by the bot or to the private chat with the bot.
 * @method self explanation(string $value) <em>Optional</em>. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
 * @method self explanation_entities(MessageEntity[] $value) <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the <em>explanation</em>
 * @method self explanation_media(PollMedia $value) <em>Optional</em>. Media added to the quiz explanation
 * @method self open_period(int $value) <em>Optional</em>. Amount of time in seconds the poll will be active after creation
 * @method self close_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the poll will be automatically closed
 * @method self description(string $value) <em>Optional</em>. Description of the poll; for polls inside the <a href="https://core.telegram.org/bots/api#message">Message</a> object only
 * @method self description_entities(MessageEntity[] $value) <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the description
 * @method self media(PollMedia $value) <em>Optional</em>. Media added to the poll description; for polls inside the <a href="https://core.telegram.org/bots/api#message">Message</a> object only
 */
class Poll
{
    /** @var string */
    public string $id;
    /** @var string */
    public string $question;
    /** @var MessageEntity[] */
    public array $question_entities;
    /** @var PollOption[] */
    public array $options;
    /** @var int */
    public int $total_voter_count;
    /** @var bool */
    public bool $is_closed;
    /** @var bool */
    public bool $is_anonymous;
    /** @var string */
    public string $type;
    /** @var bool */
    public bool $allows_multiple_answers;
    /** @var bool */
    public bool $allows_revoting;
    /** @var bool */
    public bool $members_only;
    /** @var string[] */
    public array $country_codes;
    /** @var int[] */
    public array $correct_option_ids;
    /** @var string */
    public string $explanation;
    /** @var MessageEntity[] */
    public array $explanation_entities;
    /** @var PollMedia */
    public PollMedia $explanation_media;
    /** @var int */
    public int $open_period;
    /** @var int */
    public int $close_date;
    /** @var string */
    public string $description;
    /** @var MessageEntity[] */
    public array $description_entities;
    /** @var PollMedia */
    public PollMedia $media;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about a poll.
     * @param string|null $id Unique poll identifier
     * @param string|null $question Poll question, 1-300 characters
     * @param MessageEntity[]|null $question_entities <em>Optional</em>. Special entities that appear in the <em>question</em>. Currently, only custom emoji entities are allowed in poll questions
     * @param PollOption[]|null $options List of poll options
     * @param int|null $total_voter_count Total number of users that voted in the poll
     * @param bool|null $is_closed <em>True</em>, if the poll is closed
     * @param bool|null $is_anonymous <em>True</em>, if the poll is anonymous
     * @param string|null $type Poll type, currently can be “regular” or “quiz”
     * @param bool|null $allows_multiple_answers <em>True</em>, if the poll allows multiple answers
     * @param bool|null $allows_revoting <em>True</em>, if the poll allows to change the chosen answer options
     * @param bool|null $members_only <em>True</em> if voting is limited to users who have been members of the chat where the poll was originally sent for more than 24 hours
     * @param string[]|null $country_codes <em>Optional</em>. A list of two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which users can vote in the poll. The country code “FT” is used for users with anonymous numbers. If omitted, then users from any country can participate in the poll.
     * @param int[]|null $correct_option_ids <em>Optional</em>. Array of 0-based identifiers of the correct answer options. Available only for polls in quiz mode which are closed or were sent (not forwarded) by the bot or to the private chat with the bot.
     * @param string|null $explanation <em>Optional</em>. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
     * @param MessageEntity[]|null $explanation_entities <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the <em>explanation</em>
     * @param PollMedia|null $explanation_media <em>Optional</em>. Media added to the quiz explanation
     * @param int|null $open_period <em>Optional</em>. Amount of time in seconds the poll will be active after creation
     * @param int|null $close_date <em>Optional</em>. Point in time (Unix timestamp) when the poll will be automatically closed
     * @param string|null $description <em>Optional</em>. Description of the poll; for polls inside the <a href="https://core.telegram.org/bots/api#message">Message</a> object only
     * @param MessageEntity[]|null $description_entities <em>Optional</em>. Special entities like usernames, URLs, bot commands, etc. that appear in the description
     * @param PollMedia|null $media <em>Optional</em>. Media added to the poll description; for polls inside the <a href="https://core.telegram.org/bots/api#message">Message</a> object only
     */
    public static function make(string $id = null, string $question = null, array $question_entities = null, array $options = null, int $total_voter_count = null, bool $is_closed = null, bool $is_anonymous = null, string $type = null, bool $allows_multiple_answers = null, bool $allows_revoting = null, bool $members_only = null, array $country_codes = null, array $correct_option_ids = null, string $explanation = null, array $explanation_entities = null, PollMedia $explanation_media = null, int $open_period = null, int $close_date = null, string $description = null, array $description_entities = null, PollMedia $media = null): self
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