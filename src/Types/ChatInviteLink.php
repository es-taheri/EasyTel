<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents an invite link for a chat.
 * @method self invite_link(string $value) The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”.
 * @method self creator(User $value) Creator of the link
 * @method self creates_join_request(bool $value) <em>True</em>, if users joining the chat via the link need to be approved by chat administrators
 * @method self is_primary(bool $value) <em>True</em>, if the link is primary
 * @method self is_revoked(bool $value) <em>True</em>, if the link is revoked
 * @method self name(string $value) <em>Optional</em>. Invite link name
 * @method self expire_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the link will expire or has been expired
 * @method self member_limit(int $value) <em>Optional</em>. The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 * @method self pending_join_request_count(int $value) <em>Optional</em>. Number of pending join requests created using this link
 * @method self subscription_period(int $value) <em>Optional</em>. The number of seconds the subscription will be active for before the next payment
 * @method self subscription_price(int $value) <em>Optional</em>. The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat using the link
 */
class ChatInviteLink
{
    /** @var string */
    public string $invite_link;
    /** @var User */
    public User $creator;
    /** @var bool */
    public bool $creates_join_request;
    /** @var bool */
    public bool $is_primary;
    /** @var bool */
    public bool $is_revoked;
    /** @var string */
    public string $name;
    /** @var int */
    public int $expire_date;
    /** @var int */
    public int $member_limit;
    /** @var int */
    public int $pending_join_request_count;
    /** @var int */
    public int $subscription_period;
    /** @var int */
    public int $subscription_price;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents an invite link for a chat.
     * @param string|null $invite_link The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”.
     * @param User|null $creator Creator of the link
     * @param bool|null $creates_join_request <em>True</em>, if users joining the chat via the link need to be approved by chat administrators
     * @param bool|null $is_primary <em>True</em>, if the link is primary
     * @param bool|null $is_revoked <em>True</em>, if the link is revoked
     * @param string|null $name <em>Optional</em>. Invite link name
     * @param int|null $expire_date <em>Optional</em>. Point in time (Unix timestamp) when the link will expire or has been expired
     * @param int|null $member_limit <em>Optional</em>. The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
     * @param int|null $pending_join_request_count <em>Optional</em>. Number of pending join requests created using this link
     * @param int|null $subscription_period <em>Optional</em>. The number of seconds the subscription will be active for before the next payment
     * @param int|null $subscription_price <em>Optional</em>. The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat using the link
     */
    public static function make(string $invite_link = null, User $creator = null, bool $creates_join_request = null, bool $is_primary = null, bool $is_revoked = null, string $name = null, int $expire_date = null, int $member_limit = null, int $pending_join_request_count = null, int $subscription_period = null, int $subscription_price = null): self
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