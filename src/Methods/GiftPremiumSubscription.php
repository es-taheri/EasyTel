<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Gifts a Telegram Premium subscription to the given user. Returns <em>True</em> on success.
 *
 * @method GiftPremiumSubscription text(string $value) Text that will be shown along with the service message about the subscription; 0-128 characters
 * @method GiftPremiumSubscription text_parse_mode(string $value) Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 * @method GiftPremiumSubscription text_entities(string $value) A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of <em>text_parse_mode</em>. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
 *
 * @link https://core.telegram.org/bots/api#giftpremiumsubscription
 */
class GiftPremiumSubscription
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int Unique identifier of the target user who will receive a Telegram Premium subscription */
    private int $user_id;
    /** @var int Number of months the Telegram Premium subscription will be active for the user; must be one of 3, 6, or 12 */
    private int $month_count;
    /** @var int Number of Telegram Stars to pay for the Telegram Premium subscription; must be 1000 for 3 months, 1500 for 6 months, and 2500 for 12 months */
    private int $star_count;
    /** @var string Text that will be shown along with the service message about the subscription; 0-128 characters */
    private string $text;
    /** @var string Mode for parsing entities in the text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored. */
    private string $text_parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of <em>text_parse_mode</em>. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored. */
    private string $text_entities;

    /**
     * @param Request $request
     * @param int $user_id Unique identifier of the target user who will receive a Telegram Premium subscription
     * @param int $month_count Number of months the Telegram Premium subscription will be active for the user; must be one of 3, 6, or 12
     * @param int $star_count Number of Telegram Stars to pay for the Telegram Premium subscription; must be 1000 for 3 months, 1500 for 6 months, and 2500 for 12 months
     */
    public function __construct(Request $request, int $user_id, int $month_count, int $star_count)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->month_count = $month_count;
        $this->star_count = $star_count;
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
