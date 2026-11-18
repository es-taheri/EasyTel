<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about a unique gift that was sent or received.
 * @method self gift(UniqueGift $value) Information about the gift
 * @method self origin(string $value) Origin of the gift. Currently, either “upgrade” for gifts upgraded from regular gifts, “transfer” for gifts transferred from other users or channels, “resale” for gifts bought from other users, “gifted_upgrade” for upgrades purchased after the gift was sent, or “offer” for gifts bought or sold through gift purchase offers.
 * @method self text(string $value) <em>Optional</em>. Text of the message that was added to the gift
 * @method self entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the text
 * @method self is_private(bool $value) <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method self last_resale_currency(string $value) <em>Optional</em>. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of “XTR” for Telegram Stars or “TON” for TON grams.
 * @method self last_resale_amount(int $value) <em>Optional</em>. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanograms
 * @method self owned_gift_id(string $value) <em>Optional</em>. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method self transfer_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method self next_transfer_date(int $value) <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
 */
class UniqueGiftInfo
{
    /** @var UniqueGift */
    public UniqueGift $gift;
    /** @var string */
    public string $origin;
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $entities;
    /** @var bool */
    public bool $is_private;
    /** @var string */
    public string $last_resale_currency;
    /** @var int */
    public int $last_resale_amount;
    /** @var string */
    public string $owned_gift_id;
    /** @var int */
    public int $transfer_star_count;
    /** @var int */
    public int $next_transfer_date;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a service message about a unique gift that was sent or received.
     * @param UniqueGift|null $gift Information about the gift
     * @param string|null $origin Origin of the gift. Currently, either “upgrade” for gifts upgraded from regular gifts, “transfer” for gifts transferred from other users or channels, “resale” for gifts bought from other users, “gifted_upgrade” for upgrades purchased after the gift was sent, or “offer” for gifts bought or sold through gift purchase offers.
     * @param string|null $text <em>Optional</em>. Text of the message that was added to the gift
     * @param MessageEntity[]|null $entities <em>Optional</em>. Special entities that appear in the text
     * @param bool|null $is_private <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
     * @param string|null $last_resale_currency <em>Optional</em>. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of “XTR” for Telegram Stars or “TON” for TON grams.
     * @param int|null $last_resale_amount <em>Optional</em>. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanograms
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
     * @param int|null $transfer_star_count <em>Optional</em>. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
     * @param int|null $next_transfer_date <em>Optional</em>. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
     */
    public static function make(UniqueGift $gift = null, string $origin = null, string $text = null, array $entities = null, bool $is_private = null, string $last_resale_currency = null, int $last_resale_amount = null, string $owned_gift_id = null, int $transfer_star_count = null, int $next_transfer_date = null): self
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