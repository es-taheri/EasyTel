<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\AcceptedGiftTypes;

/**
 * Changes the privacy settings pertaining to incoming gifts in a managed business account. Requires the <em>can_change_gift_settings</em> business bot right. Returns <em>True</em> on success.
 *
 * @link https://core.telegram.org/bots/api#setbusinessaccountgiftsettings
 */
class SetBusinessAccountGiftSettings
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection */
    private string $business_connection_id;
    /** @var bool Pass <em>True</em> if a button for sending a gift to the user or by the business account must always be shown in the input field */
    private bool $show_gift_button;
    /** @var AcceptedGiftTypes Types of gifts accepted by the business account */
    private AcceptedGiftTypes $accepted_gift_types;

    /**
     * @param Request $request
     * @param string $business_connection_id Unique identifier of the business connection
     * @param bool $show_gift_button Pass <em>True</em> if a button for sending a gift to the user or by the business account must always be shown in the input field
     * @param AcceptedGiftTypes $accepted_gift_types Types of gifts accepted by the business account
     */
    public function __construct(Request $request, string $business_connection_id, bool $show_gift_button, AcceptedGiftTypes $accepted_gift_types)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->show_gift_button = $show_gift_button;
        $this->accepted_gift_types = $accepted_gift_types;
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
