<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineQueryResultsButton;
/**
 * @method AnswerInlineQuery cache_time(int $value) The maximum amount of time in seconds that the result of the inline query may be cached on the server. Defaults to 300.
 * @method AnswerInlineQuery is_personal(bool $value) Pass <em>True</em> if results may be cached on the server side only for the user that sent the query. By default, results may be returned to any user who sends the same query.
 * @method AnswerInlineQuery next_offset(string $value) Pass the offset that a client should send in the next query with the same text to receive more results. Pass an empty string if there are no more results or if you don&#39;t support pagination. Offset length can&#39;t exceed 64 bytes.
 * @method AnswerInlineQuery button(InlineQueryResultsButton $value) A JSON-serialized object describing a button to be shown above inline query results
 */
class AnswerInlineQuery
{
    private Request $_request;
    private bool $_sent = false;
    private string $inline_query_id;
    private string  $results;
    private int $cache_time;
    private bool $is_personal;
    private string $next_offset;
    private InlineQueryResultsButton $button;

    public function __construct(Request $request, string $inline_query_id, string  $results)
    {
        $this->_request = $request;
        $this->inline_query_id = $inline_query_id;
        $this->results = $results;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
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
        if (!$this->_sent) $this->_result();
    }
}