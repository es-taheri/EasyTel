<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes the source of a transaction, or its recipient for outgoing transactions. Currently, it can be one of
 * @method self transactionpartneruser(TransactionPartnerUser $value)
 * @method self transactionpartnerchat(TransactionPartnerChat $value)
 * @method self transactionpartneraffiliateprogram(TransactionPartnerAffiliateProgram $value)
 * @method self transactionpartnerfragment(TransactionPartnerFragment $value)
 * @method self transactionpartnertelegramads(TransactionPartnerTelegramAds $value)
 * @method self transactionpartnertelegramapi(TransactionPartnerTelegramApi $value)
 * @method self transactionpartnerother(TransactionPartnerOther $value)
 */
class TransactionPartner
{
    protected const _UNION_BASE = true;
    /** @var TransactionPartnerUser */
    public TransactionPartnerUser $transactionpartneruser;
    /** @var TransactionPartnerChat */
    public TransactionPartnerChat $transactionpartnerchat;
    /** @var TransactionPartnerAffiliateProgram */
    public TransactionPartnerAffiliateProgram $transactionpartneraffiliateprogram;
    /** @var TransactionPartnerFragment */
    public TransactionPartnerFragment $transactionpartnerfragment;
    /** @var TransactionPartnerTelegramAds */
    public TransactionPartnerTelegramAds $transactionpartnertelegramads;
    /** @var TransactionPartnerTelegramApi */
    public TransactionPartnerTelegramApi $transactionpartnertelegramapi;
    /** @var TransactionPartnerOther */
    public TransactionPartnerOther $transactionpartnerother;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes the source of a transaction, or its recipient for outgoing transactions. Currently, it can be one of
     * @param TransactionPartnerUser|null $transactionpartneruser
     * @param TransactionPartnerChat|null $transactionpartnerchat
     * @param TransactionPartnerAffiliateProgram|null $transactionpartneraffiliateprogram
     * @param TransactionPartnerFragment|null $transactionpartnerfragment
     * @param TransactionPartnerTelegramAds|null $transactionpartnertelegramads
     * @param TransactionPartnerTelegramApi|null $transactionpartnertelegramapi
     * @param TransactionPartnerOther|null $transactionpartnerother
     */
    public static function make(TransactionPartnerUser $transactionpartneruser = null, TransactionPartnerChat $transactionpartnerchat = null, TransactionPartnerAffiliateProgram $transactionpartneraffiliateprogram = null, TransactionPartnerFragment $transactionpartnerfragment = null, TransactionPartnerTelegramAds $transactionpartnertelegramads = null, TransactionPartnerTelegramApi $transactionpartnertelegramapi = null, TransactionPartnerOther $transactionpartnerother = null): self
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