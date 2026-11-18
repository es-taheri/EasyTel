<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to create a <a href="https://telegram.org/blog/superchannels-star-reactions-subscriptions#star-subscriptions">subscription invite link</a> for a channel chat. The bot must have the <em>can_invite_users</em> administrator rights. The link can be edited using the method <a href="https://core.telegram.org/bots/api#editchatsubscriptioninvitelink">editChatSubscriptionInviteLink</a> or revoked using the method <a href="https://core.telegram.org/bots/api#revokechatinvitelink">revokeChatInviteLink</a>. Returns the new invite link as a <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
 *
 * @method CreateChatSubscriptionInviteLink name(string $value) Invite link name; 0-32 characters
 *
 * @link https://core.telegram.org/bots/api#createchatsubscriptioninvitelink
 */
class CreateChatSubscriptionInviteLink
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target channel chat or username of the target channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var string Invite link name; 0-32 characters */
    private string $name;
    /** @var int The number of seconds the subscription will be active for before the next payment. Currently, it must always be 2592000 (30 days). */
    private int $subscription_period;
    /** @var int The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat; 1-10000 */
    private int $subscription_price;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target channel chat or username of the target channel in the format <code>@username</code>
     * @param int $subscription_period The number of seconds the subscription will be active for before the next payment. Currently, it must always be 2592000 (30 days).
     * @param int $subscription_price The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat; 1-10000
     */
    public function __construct(Request $request, int|string $chat_id, int $subscription_period, int $subscription_price)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->subscription_period = $subscription_period;
        $this->subscription_price = $subscription_price;
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
