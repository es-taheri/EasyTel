<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a transaction with a user.
 * @method self type(string $value) Type of the transaction partner, always “user”
 * @method self transaction_type(string $value) Type of the transaction, currently one of “invoice_payment” for payments via invoices, “paid_media_payment” for payments for paid media, “gift_purchase” for gifts sent by the bot, “premium_purchase” for Telegram Premium subscriptions gifted by the bot, “business_account_transfer” for direct transfers from managed business accounts
 * @method self user(User $value) Information about the user
 * @method self affiliate(AffiliateInfo $value) <em>Optional</em>. Information about the affiliate that received a commission via this transaction. Can be available only for “invoice_payment” and “paid_media_payment” transactions.
 * @method self invoice_payload(string $value) <em>Optional</em>. Bot-specified invoice payload. Can be available only for “invoice_payment” transactions.
 * @method self subscription_period(int $value) <em>Optional</em>. The duration of the paid subscription. Can be available only for “invoice_payment” transactions.
 * @method self paid_media(array $value) <em>Optional</em>. Information about the paid media bought by the user; for “paid_media_payment” transactions only
 * @method self paid_media_payload(string $value) <em>Optional</em>. Bot-specified paid media payload. Can be available only for “paid_media_payment” transactions.
 * @method self gift(Gift $value) <em>Optional</em>. The gift sent to the user by the bot; for “gift_purchase” transactions only
 * @method self premium_subscription_duration(int $value) <em>Optional</em>. Number of months the gifted Telegram Premium subscription will be active for; for “premium_purchase” transactions only
 */
class TransactionPartnerUser
{
    public string $type;
    public string $transaction_type;
    public User $user;
    public AffiliateInfo $affiliate;
    public string $invoice_payload;
    public int $subscription_period;
    public array $paid_media;
    public string $paid_media_payload;
    public Gift $gift;
    public int $premium_subscription_duration;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
        if (isset($update['affiliate'])) $this->affiliate = new AffiliateInfo($update['affiliate']);
        if (isset($update['gift'])) $this->gift = new Gift($update['gift']);
    }

    /**
     * Describes a transaction with a user.
     * @param string|null $type Type of the transaction partner, always “user”
     * @param string|null $transaction_type Type of the transaction, currently one of “invoice_payment” for payments via invoices, “paid_media_payment” for payments for paid media, “gift_purchase” for gifts sent by the bot, “premium_purchase” for Telegram Premium subscriptions gifted by the bot, “business_account_transfer” for direct transfers from managed business accounts
     * @param User|null $user Information about the user
     * @param AffiliateInfo|null $affiliate <em>Optional</em>. Information about the affiliate that received a commission via this transaction. Can be available only for “invoice_payment” and “paid_media_payment” transactions.
     * @param string|null $invoice_payload <em>Optional</em>. Bot-specified invoice payload. Can be available only for “invoice_payment” transactions.
     * @param int|null $subscription_period <em>Optional</em>. The duration of the paid subscription. Can be available only for “invoice_payment” transactions.
     * @param array|null $paid_media <em>Optional</em>. Information about the paid media bought by the user; for “paid_media_payment” transactions only
     * @param string|null $paid_media_payload <em>Optional</em>. Bot-specified paid media payload. Can be available only for “paid_media_payment” transactions.
     * @param Gift|null $gift <em>Optional</em>. The gift sent to the user by the bot; for “gift_purchase” transactions only
     * @param int|null $premium_subscription_duration <em>Optional</em>. Number of months the gifted Telegram Premium subscription will be active for; for “premium_purchase” transactions only
     */
    public static function make(string $type = null, string $transaction_type = null, User $user = null, AffiliateInfo $affiliate = null, string $invoice_payload = null, int $subscription_period = null, array $paid_media = null, string $paid_media_payload = null, Gift $gift = null, int $premium_subscription_duration = null): self
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