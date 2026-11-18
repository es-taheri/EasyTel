<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;
use EasyTel\Types\InputChecklist;
use EasyTel\Types\ReplyParameters;

/**
 * Use this method to send a checklist on behalf of a connected business account. On success, the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
 *
 * @method SendChecklist disable_notification(bool $value) Sends the message silently. Users will receive a notification with no sound.
 * @method SendChecklist protect_content(bool $value) Protects the contents of the sent message from forwarding and saving
 * @method SendChecklist message_effect_id(string $value) Unique identifier of the message effect to be added to the message
 * @method SendChecklist reply_parameters(ReplyParameters $value) A JSON-serialized object for description of the message to reply to
 * @method SendChecklist reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a>
 *
 * @link https://core.telegram.org/bots/api#sendchecklist
 */
class SendChecklist
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection on behalf of which the message will be sent */
    private string $business_connection_id;
    /** @var int|string Unique identifier for the target chat or username of the target bot in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var InputChecklist A JSON-serialized object for the checklist to send */
    private InputChecklist $checklist;
    /** @var bool Sends the message silently. Users will receive a notification with no sound. */
    private bool $disable_notification;
    /** @var bool Protects the contents of the sent message from forwarding and saving */
    private bool $protect_content;
    /** @var string Unique identifier of the message effect to be added to the message */
    private string $message_effect_id;
    /** @var ReplyParameters A JSON-serialized object for description of the message to reply to */
    private ReplyParameters $reply_parameters;
    /** @var InlineKeyboardMarkup A JSON-serialized object for an <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboard</a> */
    private InlineKeyboardMarkup $reply_markup;

    /**
     * @param Request $request
     * @param string $business_connection_id Unique identifier of the business connection on behalf of which the message will be sent
     * @param int|string $chat_id Unique identifier for the target chat or username of the target bot in the format <code>@username</code>
     * @param InputChecklist $checklist A JSON-serialized object for the checklist to send
     */
    public function __construct(Request $request, string $business_connection_id, int|string $chat_id, InputChecklist $checklist)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->chat_id = $chat_id;
        $this->checklist = $checklist;
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
