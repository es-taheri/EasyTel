<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to create a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the <em>can_manage_topics</em> administrator right. In the case of a private chat, a link to the topic can be created in the format <code>https://t.me/<bot_username>/<message_thread_id></code>; the link opens the topic only for the user. Returns information about the created topic as a <a href="https://core.telegram.org/bots/api#forumtopic">ForumTopic</a> object.
 *
 * @method CreateForumTopic icon_color(int $value) Color of the topic icon in RGB format. Currently, must be one of 7322096 (0x6FB9F0), 16766590 (0xFFD67E), 13338331 (0xCB86DB), 9367192 (0x8EEE98), 16749490 (0xFF93B2), or 16478047 (0xFB6F5F).
 * @method CreateForumTopic icon_custom_emoji_id(string $value) Unique identifier of the custom emoji shown as the topic icon. Use <a href="https://core.telegram.org/bots/api#getforumtopiciconstickers">getForumTopicIconStickers</a> to get all allowed custom emoji identifiers.
 *
 * @link https://core.telegram.org/bots/api#createforumtopic
 */
class CreateForumTopic
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int|string Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code> */
    private int|string $chat_id;
    /** @var string Topic name, 1-128 characters */
    private string $name;
    /** @var int Color of the topic icon in RGB format. Currently, must be one of 7322096 (0x6FB9F0), 16766590 (0xFFD67E), 13338331 (0xCB86DB), 9367192 (0x8EEE98), 16749490 (0xFF93B2), or 16478047 (0xFB6F5F). */
    private int $icon_color;
    /** @var string Unique identifier of the custom emoji shown as the topic icon. Use <a href="https://core.telegram.org/bots/api#getforumtopiciconstickers">getForumTopicIconStickers</a> to get all allowed custom emoji identifiers. */
    private string $icon_custom_emoji_id;

    /**
     * @param Request $request
     * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format <code>@username</code>
     * @param string $name Topic name, 1-128 characters
     */
    public function __construct(Request $request, int|string $chat_id, string $name)
    {
        $this->_request = $request;
        $this->chat_id = $chat_id;
        $this->name = $name;
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
