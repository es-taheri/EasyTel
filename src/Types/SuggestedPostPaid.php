<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about a successful payment for a suggested post.
 * @method self suggested_post_message(Message $value) <em>Optional</em>. Message containing the suggested post. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
 * @method self currency(string $value) Currency in which the payment was made. Currently, one of “XTR” for Telegram Stars or “TON” for toncoins
 * @method self amount(int $value) <em>Optional</em>. The amount of the currency that was received by the channel in nanotoncoins; for payments in toncoins only
 * @method self star_amount(StarAmount $value) <em>Optional</em>. The amount of Telegram Stars that was received by the channel; for payments in Telegram Stars only
 */
class SuggestedPostPaid
{
    public Message $suggested_post_message;
    public string $currency;
    public int $amount;
    public StarAmount $star_amount;

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
        if (isset($update['suggested_post_message'])) $this->suggested_post_message = new Message($update['suggested_post_message']);
        if (isset($update['star_amount'])) $this->star_amount = new StarAmount($update['star_amount']);
    }

    /**
     * Describes a service message about a successful payment for a suggested post.
     * @param Message|null $suggested_post_message <em>Optional</em>. Message containing the suggested post. Note that the <a href="https://core.telegram.org/bots/api#message">Message</a> object in this field will not contain the <em>reply_to_message</em> field even if it itself is a reply.
     * @param string|null $currency Currency in which the payment was made. Currently, one of “XTR” for Telegram Stars or “TON” for toncoins
     * @param int|null $amount <em>Optional</em>. The amount of the currency that was received by the channel in nanotoncoins; for payments in toncoins only
     * @param StarAmount|null $star_amount <em>Optional</em>. The amount of Telegram Stars that was received by the channel; for payments in Telegram Stars only
     */
    public static function make(Message $suggested_post_message = null, string $currency = null, int $amount = null, StarAmount $star_amount = null): self
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