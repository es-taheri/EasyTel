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
    public string $invite_link;
    public User $creator;
    public bool $creates_join_request;
    public bool $is_primary;
    public bool $is_revoked;
    public string $name;
    public int $expire_date;
    public int $member_limit;
    public int $pending_join_request_count;
    public int $subscription_period;
    public int $subscription_price;

    public function __construct(array $update = [])
    {
        $objects = array_keys($update);
        $r = new \ReflectionClass(static::class);
        foreach ($objects as $object):
            if ($r->hasProperty($object)):
                $prop = $r->getProperty($object);
                $type = $prop->getType();
                if (in_array(strtolower(trim($type)), ['string', 'true', 'false', 'bool', 'int', 'float', 'array', 'mixed']) || str_contains($type, '|'))
                    $this->{$object} = $update[$object];
            endif;
        endforeach;
        if (isset($update['creator'])) $this->creator = new User($update['creator']);
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
        $updates = array_filter($args, fn($v) => isset($v));
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
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (isset($this->{$name})):
                $value = $property->getValue($this);
                $property_type = $property->getType();
                if ($property_type == 'object')
                    $output[$name] = (fn() => ($this->_output($type)))->bindTo($value, $value)();
                else
                    $output[$name] = $value;
            endif;
        endforeach;
        return Statics::output($output, $type);
    }
}