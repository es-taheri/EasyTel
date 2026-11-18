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
 * @method self is_saved(bool $value) <em>Optional</em>. <em>True</em>, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
 * @method self can_be_transferred(bool $value) <em>Optional</em>. <em>True</em>, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
 * @method self transfer_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method self next_transfer_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
 */
class OwnedGiftUnique
{
    /** @var string */
    public string $type;
    /** @var UniqueGift */
    public UniqueGift $gift;
    /** @var string */
    public string $owned_gift_id;
    /** @var User */
    public User $sender_user;
    /** @var int */
    public int $send_date;
    /** @var bool */
    public bool $is_saved;
    /** @var bool */
    public bool $can_be_transferred;
    /** @var int */
    public int $transfer_star_count;
    /** @var int */
    public int $next_transfer_date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a unique gift received and owned by a user or a chat.
     * @param string|null $type Type of the gift, always “unique”
     * @param UniqueGift|null $gift Information about the unique gift
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the received gift for the bot; for gifts received on behalf of business accounts only
     * @param User|null $sender_user <em>Optional</em>. Sender of the gift if it is a known user
     * @param int|null $send_date Date the gift was sent in Unix time
     * @param bool|null $is_saved <em>Optional</em>. <em>True</em>, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
     * @param bool|null $can_be_transferred <em>Optional</em>. <em>True</em>, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
     * @param int|null $transfer_star_count <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
     * @param int|null $next_transfer_date <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
     */
    public static function make(string $type = null, UniqueGift $gift = null, string $owned_gift_id = null, User $sender_user = null, int $send_date = null, bool $is_saved = null, bool $can_be_transferred = null, int $transfer_star_count = null, int $next_transfer_date = null): self
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