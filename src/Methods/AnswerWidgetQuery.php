<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to send an answer to a query from a widget. The query must be answered within 15 seconds. The answer is received only by the widget that sent the query and doesn't change data of the widget; use <a href="https://core.telegram.org/bots/api#editmessagewidgetdata">editMessageWidgetData</a> or <a href="https://core.telegram.org/bots/api#editsharedwidgetdata">editSharedWidgetData</a> to change it. On success, <em>True</em> is returned.
 *
 * @method AnswerWidgetQuery data(string $value) Data to be returned to the widget, 0-1024 bytes
 *
 * @link https://core.telegram.org/bots/api#answerwidgetquery
 */
class AnswerWidgetQuery
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier for the query to be answered */
    private string $widget_query_id;
    /** @var string Data to be returned to the widget, 0-1024 bytes */
    private string $data;

    /**
     * @param Request $request
     * @param string $widget_query_id Unique identifier for the query to be answered
     */
    public function __construct(Request $request, string $widget_query_id)
    {
        $this->_request = $request;
        $this->widget_query_id = $widget_query_id;
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
