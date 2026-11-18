<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to edit public data of a shared widget created by the bot. A shared widget can be edited at most once per second. On success, <em>True</em> is returned.
 *
 * @method EditSharedWidgetData cache_time(int $value) The maximum amount of time in seconds that the public data of the widget may be cached client-side; 5-3600. If omitted, then the current value is kept, which is 30 by default.
 *
 * @link https://core.telegram.org/bots/api#editsharedwidgetdata
 */
class EditSharedWidgetData
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the shared widget */
    private string $widget_id;
    /** @var string New public data of the widget, 0-1024 bytes; pass an empty string to remove the public data */
    private string $public_data;
    /** @var int The maximum amount of time in seconds that the public data of the widget may be cached client-side; 5-3600. If omitted, then the current value is kept, which is 30 by default. */
    private int $cache_time;

    /**
     * @param Request $request
     * @param string $widget_id Unique identifier of the shared widget
     * @param string $public_data New public data of the widget, 0-1024 bytes; pass an empty string to remove the public data
     */
    public function __construct(Request $request, string $widget_id, string $public_data)
    {
        $this->_request = $request;
        $this->widget_id = $widget_id;
        $this->public_data = $public_data;
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
