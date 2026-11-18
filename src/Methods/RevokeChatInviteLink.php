<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as <a href="https://core.telegram.org/bots/api#chatinvitelink">ChatInviteLink</a> object.
 *
 * @link https://core.telegram.org/bots/api#revokechatinvitelink
 */
class RevokeChatInviteLink
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier of the target chat or username of the target channel in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var string The invite link to revoke */
    private string $invite_link;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier of the target chat or username of the target channel in the format <code>@username</code>
     * @param string $invite_link The invite link to revoke
     */
    public function __construct(Request $request, int|string $chat_id, string $invite_link)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->invite_link = $invite_link;
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
