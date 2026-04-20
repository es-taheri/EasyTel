<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\ReplyParameters;
use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardMarkup;
use EasyTel\Types\ReplyKeyboardRemove;
use EasyTel\Types\ForceReply;
/**
 * @method SendPoll business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message will be sent
 * @method SendPoll message_thread_id(int $value) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
 * @method SendPoll question_parse_mode(string $value) Mode for parsing entities in the question. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Currently, only custom emoji entities are allowed
 * @method SendPoll question_entities(string  $value) A JSON-serialized list of special entities that appear in the poll question. It can be specified instead of <em>question_parse_mode</em>
 * @method SendPoll is_anonymous(bool $value) <em>True</em>, if the poll needs to be anonymous, defaults to <em>True</em>
 * @method SendPoll type(string $value) Poll type, “quiz” or “regular”, defaults to “regular”
 * @method SendPoll allows_multiple_answers(bool $value) Pass <em>True</em>, if the poll allows multiple answers, defaults to <em>False</em>
 * @method SendPoll allows_revoting(bool $value) Pass <em>True</em>, if the poll allows to change chosen answer options, defaults to <em>False</em> for quizzes and to <em>True</em> for regular polls
 * @method SendPoll shuffle_options(bool $value) Pass <em>True</em>, if the poll options must be shown in random order
 * @method SendPoll allow_adding_options(bool $value) Pass <em>True</em>, if answer options can be added to the poll after creation; not supported for anonymous polls and quizzes
 * @method SendPoll hide_results_until_closes(bool $value) Pass <em>True</em>, if poll results must be shown only after the poll closes
 * @method SendPoll correct_option_ids(string  $value) A JSON-serialized list of monotonically increasing 0-based identifiers of the correct answer options, required for polls in quiz mode
 * @method SendPoll explanation(string $value) Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters with at most 2 line feeds after entities parsing
 * @method SendPoll explanation_parse_mode(string $value) Mode for parsing entities in the explanation. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendPoll explanation_entities(string  $value) A JSON-serialized list of special entities that appear in the poll explanation. It can be specified instead of <em>explanation_parse_mode</em>
 * @method SendPoll open_period(int $value) Amount of time in seconds the poll will be active after creation, 5-2628000. Can&#39;t be used together with <em>close_date</em>.
 * @method SendPoll close_date(int $value) Point in time (Unix timestamp) when the poll will be automatically closed. Must be at least 5 and no more than 2628000 seconds in the future. Can&#39;t be used together with <em>open_period</em>.
 * @method SendPoll is_closed(bool $value) Pass <em>True</em> if the poll needs to be immediately closed. This can be useful for poll preview.
 * @method SendPoll description(string $value) Description of the poll to be sent, 0-1024 characters after entities parsing
 * @method SendPoll description_parse_mode(string $value) Mode for parsing entities in the poll description. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method SendPoll description_entities(string  $value) A JSON-serialized list of special entities that appear in the poll description, which can be specified instead of <em>description_parse_mode</em>
 * @method SendPoll disable_notification(bool $value) Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
 * @method SendPoll protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendPoll allow_paid_broadcast(bool $value) Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot&#39;s balance
 * @method SendPoll message_effect_id(string $value) Unique identifier of the message effect to be added to the message; for private chats only
 * @method SendPoll reply_parameters(ReplyParameters $value) Description of the message to reply to
 * @method SendPoll reply_markup(InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $value) Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user
 */
class SendPoll
{
    private Request $_request;
    private bool $_returned = false;
    private bool $_sent = false;
    private int|string $chat_id;
    private string $question;
    private string  $options;
    private string $business_connection_id;
    private int $message_thread_id;
    private string $question_parse_mode;
    private string  $question_entities;
    private bool $is_anonymous;
    private string $type;
    private bool $allows_multiple_answers;
    private bool $allows_revoting;
    private bool $shuffle_options;
    private bool $allow_adding_options;
    private bool $hide_results_until_closes;
    private string  $correct_option_ids;
    private string $explanation;
    private string $explanation_parse_mode;
    private string  $explanation_entities;
    private int $open_period;
    private int $close_date;
    private bool $is_closed;
    private string $description;
    private string $description_parse_mode;
    private string  $description_entities;
    private bool $disable_notification;
    private bool $protect_content;
    private bool $allow_paid_broadcast;
    private string $message_effect_id;
    private ReplyParameters $reply_parameters;
    private InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply $reply_markup;

    public function __construct(Request $request, int|string $chat_id, string $question, string  $options)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->question = $question;
        $this->options = $options;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        $this->_returned = true;
        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];
        foreach ($this as $key => $value):
            if (isset($this->{$key}) && !in_array($key, ['_request', '_result'])):
                if (gettype($value) == 'object')
                    $parameters[$key] = (fn() => ($this->_output()))->bindTo($value, $value)();
                else
                    $parameters[$key] = $value;
            endif;
        endforeach;
        $r = new \ReflectionClass($this);
        $this->_sent = true;
        return $this->_request->send(lcfirst($r->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if (!$this->_returned && !$this->_sent) $this->_result();
    }
}