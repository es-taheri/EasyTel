<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to send answers to callback queries sent from <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboards</a>. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, <em>True</em> is returned.
 *
 * <p>Alternatively, the user can be redirected to the specified Game URL. For this option to work, you must first create a game for your bot via <a href="https://t.me/botfather">@BotFather</a> and accept the terms. Otherwise, you may use links like <code>t.me/your_bot?start=XXXX</code> that open your bot with a parameter.</p>
 *
 * @method AnswerCallbackQuery text(string $value) Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters.
 * @method AnswerCallbackQuery show_alert(bool $value) If <em>True</em>, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to <em>False</em>.
 * @method AnswerCallbackQuery url(string $value) URL that will be opened by the user's client. If you have created a <a href="https://core.telegram.org/bots/api#game">Game</a> and accepted the conditions via <a href="https://t.me/botfather">@BotFather</a>, specify the URL that opens your game - note that this will only work if the query comes from a <a href="https://core.telegram.org/bots/api#inlinekeyboardbutton"><em>callback_game</em></a> button.<br><br>Otherwise, you may use links like <code>t.me/your_bot?start=XXXX</code> that open your bot with a parameter.
 * @method AnswerCallbackQuery cache_time(int $value) The maximum amount of time in seconds that the result of the callback query may be cached client-side. Defaults to 0.
 *
 * @link https://core.telegram.org/bots/api#answercallbackquery
 */
class AnswerCallbackQuery
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier for the query to be answered */
    private string $callback_query_id;
    /** @var string Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters. */
    private string $text;
    /** @var bool If <em>True</em>, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to <em>False</em>. */
    private bool $show_alert;
    /** @var string URL that will be opened by the user's client. If you have created a <a href="https://core.telegram.org/bots/api#game">Game</a> and accepted the conditions via <a href="https://t.me/botfather">@BotFather</a>, specify the URL that opens your game - note that this will only work if the query comes from a <a href="https://core.telegram.org/bots/api#inlinekeyboardbutton"><em>callback_game</em></a> button.<br><br>Otherwise, you may use links like <code>t.me/your_bot?start=XXXX</code> that open your bot with a parameter. */
    private string $url;
    /** @var int The maximum amount of time in seconds that the result of the callback query may be cached client-side. Defaults to 0. */
    private int $cache_time;

    /**
     * @param Request $request
     * @param string $callback_query_id Unique identifier for the query to be answered
     */
    public function __construct(Request $request, string $callback_query_id)
    {
        $this->_request = $request;
        $this->callback_query_id = $callback_query_id;
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
