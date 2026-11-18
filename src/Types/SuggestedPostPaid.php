<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about a successful payment for a suggested post.
 * @method self suggested_post_message(Message $value) <em>Optional</em>. Message containing the suggested post. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
 * @method self currency(string $value) Currency in which the payment was made. Currently, one of “XTR” for Telegram Stars or “TON” for TON grams.
 * @method self amount(int $value) <em>Optional</em>. The amount of the currency that was received by the channel in nanograms; for payments in TON grams only
 * @method self star_amount(StarAmount $value) <em>Optional</em>. The amount of Telegram Stars that was received by the channel; for payments in Telegram Stars only
 */
class SuggestedPostPaid
{
    /** @var Message */
    public Message $suggested_post_message;
    /** @var string */
    public string $currency;
    /** @var int */
    public int $amount;
    /** @var StarAmount */
    public StarAmount $star_amount;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a service message about a successful payment for a suggested post.
     * @param Message|null $suggested_post_message <em>Optional</em>. Message containing the suggested post. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
     * @param string|null $currency Currency in which the payment was made. Currently, one of “XTR” for Telegram Stars or “TON” for TON grams.
     * @param int|null $amount <em>Optional</em>. The amount of the currency that was received by the channel in nanograms; for payments in TON grams only
     * @param StarAmount|null $star_amount <em>Optional</em>. The amount of Telegram Stars that was received by the channel; for payments in Telegram Stars only
     */
    public static function make(Message $suggested_post_message = null, string $currency = null, int $amount = null, StarAmount $star_amount = null): self
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