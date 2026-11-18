<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a join request sent to a chat.
 * @method self chat(Chat $value) Chat to which the request was sent
 * @method self from(User $value) User that sent the join request
 * @method self user_chat_id(int $value) Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
 * @method self date(int $value) Date the request was sent in Unix time
 * @method self bio(string $value) <em>Optional</em>. Bio of the user
 * @method self invite_link(ChatInviteLink $value) <em>Optional</em>. Chat invite link that was used by the user to send the join request
 * @method self query_id(string $value) <em>Optional</em>. Identifier of the join request query; for bots assigned to process join requests only. If present, then the bot must call <a href="https://core.telegram.org/bots/api#sendchatjoinrequestwebapp">sendChatJoinRequestWebApp</a> or directly call <a href="https://core.telegram.org/bots/api#answerchatjoinrequestquery">answerChatJoinRequestQuery</a> within 10 seconds.
 */
class ChatJoinRequest
{
    /** @var Chat */
    public Chat $chat;
    /** @var User */
    public User $from;
    /** @var int */
    public int $user_chat_id;
    /** @var int */
    public int $date;
    /** @var string */
    public string $bio;
    /** @var ChatInviteLink */
    public ChatInviteLink $invite_link;
    /** @var string */
    public string $query_id;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents a join request sent to a chat.
     * @param Chat|null $chat Chat to which the request was sent
     * @param User|null $from User that sent the join request
     * @param int|null $user_chat_id Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
     * @param int|null $date Date the request was sent in Unix time
     * @param string|null $bio <em>Optional</em>. Bio of the user
     * @param ChatInviteLink|null $invite_link <em>Optional</em>. Chat invite link that was used by the user to send the join request
     * @param string|null $query_id <em>Optional</em>. Identifier of the join request query; for bots assigned to process join requests only. If present, then the bot must call <a href="https://core.telegram.org/bots/api#sendchatjoinrequestwebapp">sendChatJoinRequestWebApp</a> or directly call <a href="https://core.telegram.org/bots/api#answerchatjoinrequestquery">answerChatJoinRequestQuery</a> within 10 seconds.
     */
    public static function make(Chat $chat = null, User $from = null, int $user_chat_id = null, int $date = null, string $bio = null, ChatInviteLink $invite_link = null, string $query_id = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => $v !== null);
        return new self($updates);
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    protected function _output(int $type = Telegram::OUTPUT_JSON): object|array|string
    {
        $output = [];
        $r = new \ReflectionClass(static::class);
        $union = $r->hasConstant('_UNION_BASE');

        $normalize = function ($value) use (&$normalize) {
            if (is_object($value) && method_exists($value, '_output'))
                return (fn() => $this->_output(Telegram::OUTPUT_ARRAY))->bindTo($value, $value)();
            if (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item)
                    $items[$key] = $normalize($item);
                return $items;
            }
            return $value;
        };

        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (!isset($this->{$name}))
                continue;

            $raw = $property->getValue($this);
            $value = $normalize($raw);

            // A union base variant property represents the payload itself, so
            // its fields replace the variant key.
            if ($union && is_object($raw) && is_array($value)
                && $name === strtolower((new \ReflectionClass($raw))->getShortName())):
                $output += $value;
                continue;
            endif;

            $output[$name] = $value;
        endforeach;
        return Statics::output($output, $type);
    }
}