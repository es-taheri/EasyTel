<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about a unique gift that was sent or received.
 * @method self gift(UniqueGift $value) Information about the gift
 * @method self origin(string $value) Origin of the gift. Currently, either “upgrade” for gifts upgraded from regular gifts, “transfer” for gifts transferred from other users or channels, “resale” for gifts bought from other users, “gifted_upgrade” for upgrades purchased after the gift was sent, or “offer” for gifts bought or sold through gift purchase offers
 * @method self last_resale_currency(string $value) <em>Optional</em>. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of “XTR” for Telegram Stars or “TON” for toncoins.
 * @method self last_resale_amount(int $value) <em>Optional</em>. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanotoncoins
 * @method self owned_gift_id(string $value) <em>Optional</em>. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method self transfer_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method self next_transfer_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now
 */
class UniqueGiftInfo
{
    public UniqueGift $gift;
    public string $origin;
    public string $last_resale_currency;
    public int $last_resale_amount;
    public string $owned_gift_id;
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
    }

    /**
     * Describes a service message about a unique gift that was sent or received.
     * @param UniqueGift|null $gift Information about the gift
     * @param string|null $origin Origin of the gift. Currently, either “upgrade” for gifts upgraded from regular gifts, “transfer” for gifts transferred from other users or channels, “resale” for gifts bought from other users, “gifted_upgrade” for upgrades purchased after the gift was sent, or “offer” for gifts bought or sold through gift purchase offers
     * @param string|null $last_resale_currency <em>Optional</em>. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of “XTR” for Telegram Stars or “TON” for toncoins.
     * @param int|null $last_resale_amount <em>Optional</em>. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanotoncoins
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
     * @param int|null $transfer_star_count <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
     * @param int|null $next_transfer_date <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now
     */
    public static function make(UniqueGift $gift = null, string $origin = null, string $last_resale_currency = null, int $last_resale_amount = null, string $owned_gift_id = null, int $transfer_star_count = null, int $next_transfer_date = null): self
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