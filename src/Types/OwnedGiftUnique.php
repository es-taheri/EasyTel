<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a unique gift received and owned by a user or a chat.
 * @method self type(string $value) Type of the gift, always “unique”
 * @method self gift(UniqueGift $value) Information about the unique gift
 * @method self owned_gift_id(string $value) <em>Optional</em>. Unique identifier of the received gift for the bot; for gifts received on behalf of business accounts only
 * @method self sender_user(User $value) <em>Optional</em>. Sender of the gift if it is a known user
 * @method self send_date(int $value) Date the gift was sent in Unix time
 * @method self is_saved(True $value) <em>Optional</em>. <em>True</em>, if the gift is displayed on the account&#39;s profile page; for gifts received on behalf of business accounts only
 * @method self can_be_transferred(True $value) <em>Optional</em>. <em>True</em>, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
 * @method self transfer_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method self next_transfer_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now
 */
class OwnedGiftUnique
{
    public string $type;
    public UniqueGift $gift;
    public string $owned_gift_id;
    public User $sender_user;
    public int $send_date;
    public True $is_saved;
    public True $can_be_transferred;
    public int $transfer_star_count;
    public int $next_transfer_date;

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
        if (isset($update['gift'])) $this->gift = new UniqueGift($update['gift']);
        if (isset($update['sender_user'])) $this->sender_user = new User($update['sender_user']);
    }

    /**
     * Describes a unique gift received and owned by a user or a chat.
     * @param string|null $type Type of the gift, always “unique”
     * @param UniqueGift|null $gift Information about the unique gift
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the received gift for the bot; for gifts received on behalf of business accounts only
     * @param User|null $sender_user <em>Optional</em>. Sender of the gift if it is a known user
     * @param int|null $send_date Date the gift was sent in Unix time
     * @param True|null $is_saved <em>Optional</em>. <em>True</em>, if the gift is displayed on the account&#39;s profile page; for gifts received on behalf of business accounts only
     * @param True|null $can_be_transferred <em>Optional</em>. <em>True</em>, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
     * @param int|null $transfer_star_count <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
     * @param int|null $next_transfer_date <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now
     */
    public static function make(string $type = null, UniqueGift $gift = null, string $owned_gift_id = null, User $sender_user = null, int $send_date = null, True $is_saved = null, True $can_be_transferred = null, int $transfer_star_count = null, int $next_transfer_date = null): self
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