<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineQueryResultsButton;

/**
 * Use this method to send answers to an inline query. On success, <em>True</em> is returned.<br>No more than <strong>50</strong> results per query are allowed.
 *
 * @method AnswerInlineQuery cache_time(int $value) The maximum amount of time in seconds that the result of the inline query may be cached on the server. Defaults to 300.
 * @method AnswerInlineQuery is_personal(bool $value) Pass <em>True</em> if results may be cached on the server side only for the user that sent the query. By default, results may be returned to any user who sends the same query.
 * @method AnswerInlineQuery next_offset(string $value) Pass the offset that a client should send in the next query with the same text to receive more results. Pass an empty string if there are no more results or if you don't support pagination. Offset length can't exceed 64 bytes.
 * @method AnswerInlineQuery button(InlineQueryResultsButton $value) A JSON-serialized object describing a button to be shown above inline query results
 *
 * @link https://core.telegram.org/bots/api#answerinlinequery
 */
class AnswerInlineQuery
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier for the answered query */
    private string $inline_query_id;
    /** @var string A JSON-serialized Array of results for the inline query */
    private string $results;
    /** @var int The maximum amount of time in seconds that the result of the inline query may be cached on the server. Defaults to 300. */
    private int $cache_time;
    /** @var bool Pass <em>True</em> if results may be cached on the server side only for the user that sent the query. By default, results may be returned to any user who sends the same query. */
    private bool $is_personal;
    /** @var string Pass the offset that a client should send in the next query with the same text to receive more results. Pass an empty string if there are no more results or if you don't support pagination. Offset length can't exceed 64 bytes. */
    private string $next_offset;
    /** @var InlineQueryResultsButton A JSON-serialized object describing a button to be shown above inline query results */
    private InlineQueryResultsButton $button;

    /**
     * @param Request $request
     * @param string $inline_query_id Unique identifier for the answered query
     * @param string $results A JSON-serialized Array of results for the inline query
     */
    public function __construct(Request $request, string $inline_query_id, string $results)
    {
        $this->_request = $request;
        $this->inline_query_id = $inline_query_id;
        $this->results = $results;
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
