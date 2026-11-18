<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an <a href="https://core.telegram.org/bots/api#update">Update</a> with the field <em>pre_checkout_query</em>. Use this method to respond to such pre-checkout queries. On success, <em>True</em> is returned. <strong>Note:</strong> The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 *
 * @method AnswerPreCheckoutQuery error_message(string $value) Required if <em>ok</em> is <em>False</em>. Error message in human readable form that explains the reason for failure to proceed with the checkout (e.g. "Sorry, somebody just bought the last of our amazing black T-shirts while you were busy filling out your payment details. Please choose a different color or garment!"). Telegram will display this message to the user.
 *
 * @link https://core.telegram.org/bots/api#answerprecheckoutquery
 */
class AnswerPreCheckoutQuery
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier for the query to be answered */
    private string $pre_checkout_query_id;
    /** @var bool Specify <em>True</em> if everything is alright (goods are available, etc.) and the bot is ready to proceed with the order. Use <em>False</em> if there are any problems. */
    private bool $ok;
    /** @var string Required if <em>ok</em> is <em>False</em>. Error message in human readable form that explains the reason for failure to proceed with the checkout (e.g. "Sorry, somebody just bought the last of our amazing black T-shirts while you were busy filling out your payment details. Please choose a different color or garment!"). Telegram will display this message to the user. */
    private string $error_message;

    /**
     * @param Request $request
     * @param string $pre_checkout_query_id Unique identifier for the query to be answered
     * @param bool $ok Specify <em>True</em> if everything is alright (goods are available, etc.) and the bot is ready to proceed with the order. Use <em>False</em> if there are any problems.
     */
    public function __construct(Request $request, string $pre_checkout_query_id, bool $ok)
    {
        $this->_request = $request;
        $this->pre_checkout_query_id = $pre_checkout_query_id;
        $this->ok = $ok;
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
