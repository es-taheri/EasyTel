<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\ForceReply;
use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\InputPollMedia;
use EasyTel\Types\ReplyKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardRemove;
use EasyTel\Types\ReplyParameters;

/**
 * Use this method to send a native poll. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
 *
 * @method SendPoll business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message will be sent
 * @method SendPoll message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendPoll question_parse_mode(string $value) Mode for parsing entities in the question. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Currently, only custom emoji entities are allowed.
 * @method SendPoll question_entities(string $value) A JSON-serialized list of special entities that appear in the poll question. It can be specified instead of <em>question_parse_mode</em>.
 * @method SendPoll is_anonymous(bool $value) <em>True</em>, if the poll needs to be anonymous, defaults to <em>True</em>
 * @method SendPoll type(string $value) Poll type, “quiz” or “regular”, defaults to “regular”
 * @method SendPoll allows_multiple_answers(bool $value) Pass <em>True</em> if the poll allows multiple answers, defaults to <em>False</em>
 * @method SendPoll allows_revoting(bool $value) Pass <em>True</em> if the poll allows to change chosen answer options, defaults to <em>False</em> for quizzes and to <em>True</em> for regular polls
 * @method SendPoll shuffle_options(bool $value) Pass <em>True</em> if the poll options must be shown in random order
 * @method SendPoll allow_adding_options(bool $value) Pass <em>True</em> if answer options can be added to the poll after creation; not supported for anonymous polls and quizzes
 * @method SendPoll hide_results_until_closes(bool $value) Pass <em>True</em> if poll results must be shown only after the poll closes
 * @method SendPoll members_only(bool $value) Pass <em>True</em> if voting is limited to users who have been members of the chat where the poll is being sent for more than 24 hours; for channel chats only
 * @method SendPoll country_codes(string $value) A JSON-serialized list of 0-12 two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which users can vote in the poll; for channel chats only. Use “FT” as a country code to allow users with anonymous numbers to vote. If omitted or empty, then users from any country can participate in the poll.
 * @method SendPoll correct_option_ids(string $value) A JSON-serialized list of monotonically increasing 0-based identifiers of the correct answer options, required for polls in quiz mode
 * @method SendPoll explanation(string $value) Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters with at most 2 line feeds after entities parsing
 * @method SendPoll explanation_parse_mode(string $value) Mode for parsing entities in the explanation. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendPoll explanation_entities(string $value) A JSON-serialized list of special entities that appear in the poll explanation. It can be specified instead of <em>explanation_parse_mode</em>.
 * @method SendPoll explanation_media(InputPollMedia $value) Media added to the quiz explanation
 * @method SendPoll open_period(int $value) Amount of time in seconds the poll will be active after creation, 5-2628000. Can't be used together with <em>close_date</em>.
 * @method SendPoll close_date(int $value) Point in time (Unix timestamp) when the poll will be automatically closed. Must be at least 5 and no more than 2628000 seconds in the future. Can't be used together with <em>open_period</em>.
 * @method SendPoll is_closed(bool $value) Pass <em>True</em> if the poll needs to be immediately closed. This can be useful for poll preview.
 * @method SendPoll description(string $value) Description of the poll to be sent, 0-1024 characters after entities parsing
 * @method SendPoll description_parse_mode(string $value) Mode for parsing entities in the poll description. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendPoll description_entities(string $value) A JSON-serialized list of special entities that appear in the poll description, which can be specified instead of <em>description_parse_mode</em>
 * @method SendPoll media(InputPollMedia $value) Media added to the poll description
 * @method SendPoll disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendPoll protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendPoll allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
 * @method SendPoll message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendPoll reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendPoll reply_markup(InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $value) Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>, <a href="https://core.telegram.org/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
 *
 * @link https://core.telegram.org/bots/api#sendpoll
 */
class SendPoll
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection on behalf of which the message will be sent */
    private string $business_connection_id;
    /** @var int|string Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>. Polls can't be sent to channel direct messages chats. */
    private int|string $chat_id;
    /** @var int Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only */
    private int $message_thread_id;
    /** @var string Poll question, 1-300 characters */
    private string $question;
    /** @var string Mode for parsing entities in the question. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Currently, only custom emoji entities are allowed. */
    private string $question_parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the poll question. It can be specified instead of <em>question_parse_mode</em>. */
    private string $question_entities;
    /** @var string A JSON-serialized list of 1-12 answer options */
    private string $options;
    /** @var bool <em>True</em>, if the poll needs to be anonymous, defaults to <em>True</em> */
    private bool $is_anonymous;
    /** @var string Poll type, “quiz” or “regular”, defaults to “regular” */
    private string $type;
    /** @var bool Pass <em>True</em> if the poll allows multiple answers, defaults to <em>False</em> */
    private bool $allows_multiple_answers;
    /** @var bool Pass <em>True</em> if the poll allows to change chosen answer options, defaults to <em>False</em> for quizzes and to <em>True</em> for regular polls */
    private bool $allows_revoting;
    /** @var bool Pass <em>True</em> if the poll options must be shown in random order */
    private bool $shuffle_options;
    /** @var bool Pass <em>True</em> if answer options can be added to the poll after creation; not supported for anonymous polls and quizzes */
    private bool $allow_adding_options;
    /** @var bool Pass <em>True</em> if poll results must be shown only after the poll closes */
    private bool $hide_results_until_closes;
    /** @var bool Pass <em>True</em> if voting is limited to users who have been members of the chat where the poll is being sent for more than 24 hours; for channel chats only */
    private bool $members_only;
    /** @var string A JSON-serialized list of 0-12 two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which users can vote in the poll; for channel chats only. Use “FT” as a country code to allow users with anonymous numbers to vote. If omitted or empty, then users from any country can participate in the poll. */
    private string $country_codes;
    /** @var string A JSON-serialized list of monotonically increasing 0-based identifiers of the correct answer options, required for polls in quiz mode */
    private string $correct_option_ids;
    /** @var string Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters with at most 2 line feeds after entities parsing */
    private string $explanation;
    /** @var string Mode for parsing entities in the explanation. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $explanation_parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the poll explanation. It can be specified instead of <em>explanation_parse_mode</em>. */
    private string $explanation_entities;
    /** @var InputPollMedia Media added to the quiz explanation */
    private InputPollMedia $explanation_media;
    /** @var int Amount of time in seconds the poll will be active after creation, 5-2628000. Can't be used together with <em>close_date</em>. */
    private int $open_period;
    /** @var int Point in time (Unix timestamp) when the poll will be automatically closed. Must be at least 5 and no more than 2628000 seconds in the future. Can't be used together with <em>open_period</em>. */
    private int $close_date;
    /** @var bool Pass <em>True</em> if the poll needs to be immediately closed. This can be useful for poll preview. */
    private bool $is_closed;
    /** @var string Description of the poll to be sent, 0-1024 characters after entities parsing */
    private string $description;
    /** @var string Mode for parsing entities in the poll description. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $description_parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the poll description, which can be specified instead of <em>description_parse_mode</em> */
    private string $description_entities;
    /** @var InputPollMedia Media added to the poll description */
    private InputPollMedia $media;
    /** @var bool Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the sent message from forwarding and saving */
    private bool $protect_content;
    /** @var bool Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance. */
    private bool $allow_paid_broadcast;
    /** @var string Unique identifier of the message effect to be added to the message; for private chats only */
    private string $message_effect_id;
    /** @var ReplyParameters Description of the message to reply to */
    private ReplyParameters $reply_parameters;
    /** @var InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply Additional interface options. A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>, <a href="https://core.telegram.org/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user. */
    private InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $reply_markup;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format <code>@username</code>. Polls can't be sent to channel direct messages chats.
     * @param string $question Poll question, 1-300 characters
     * @param string $options A JSON-serialized list of 1-12 answer options
     */
    public function __construct(Request $request, int|string $chat_id, string $question, string $options)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->question = $question;
        $this->options = $options;
    }

    public function __call(string $name, array $arguments)
    {
        if (!property_exists($this, $name)) {
            $this->_sent = true; // never send a half built request from __destruct()
            throw new \BadMethodCallException(
                sprintf('Unknown parameter "%s" for %s.', $name, static::class)
            );
        }

        if ($arguments === []) {
            $this->_sent = true;
            throw new \InvalidArgumentException(
                sprintf('Parameter "%s" of %s requires a value.', $name, static::class)
            );
        }

        try {
            $this->{$name} = array_shift($arguments);
        } catch (\Throwable $e) {
            $this->_sent = true; // the value did not fit: the request is invalid
            throw $e;
        }

        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];

        foreach ($this as $key => $value) {
            if (str_starts_with($key, '_') || !isset($this->{$key})) {
                continue;
            }

            if (is_object($value)) {
                $parameters[$key] = method_exists($value, '_output')
                    ? (fn() => $this->_output())->bindTo($value, $value)()
                    : $value;
            } else {
                $parameters[$key] = $value;
            }
        }

        $reflection = new \ReflectionClass($this);
        $this->_sent = true;

        return $this->_request->send(lcfirst($reflection->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if ($this->_sent)
            return;

        // Fire-and-forget call from the destructor must never raise inside
        // shutdown; the explicit _result() call is the error-reporting path.
        try {
            $this->_result();
        } catch (\Throwable) {
            // Ignored on purpose.
        }
    }
}
