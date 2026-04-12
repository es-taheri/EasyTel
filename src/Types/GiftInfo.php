<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a service message about a regular gift that was sent or received.
 * @method self gift(Gift $value) Information about the gift
 * @method self owned_gift_id(string $value) <em>Optional</em>. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method self convert_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that can be claimed by the receiver by converting the gift; omitted if conversion to Telegram Stars is impossible
 * @method self prepaid_upgrade_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that were prepaid for the ability to upgrade the gift
 * @method self is_upgrade_separate(True $value) <em>Optional</em>. <em>True</em>, if the gift&#39;s upgrade was purchased after the gift was sent
 * @method self can_be_upgraded(True $value) <em>Optional</em>. <em>True</em>, if the gift can be upgraded to a unique gift
 * @method self text(string $value) <em>Optional</em>. Text of the message that was added to the gift
 * @method self entities(array $value) <em>Optional</em>. Special entities that appear in the text
 * @method self is_private(True $value) <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method self unique_gift_number(int $value) <em>Optional</em>. Unique number reserved for this gift when upgraded. See the <em>number</em> field in <a href="https://core.telegram.org/bots/api#uniquegift">UniqueGift</a>
 */
class GiftInfo
{
    public Gift $gift;
    public string $owned_gift_id;
    public int $convert_star_count;
    public int $prepaid_upgrade_star_count;
    public True $is_upgrade_separate;
    public True $can_be_upgraded;
    public string $text;
    public array $entities;
    public True $is_private;
    public int $unique_gift_number;

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
        if (isset($update['gift'])) $this->gift = new Gift($update['gift']);
    }

    /**
     * Describes a service message about a regular gift that was sent or received.
     * @param Gift|null $gift Information about the gift
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
     * @param int|null $convert_star_count <em>Optional</em>. Number of Telegram Stars that can be claimed by the receiver by converting the gift; omitted if conversion to Telegram Stars is impossible
     * @param int|null $prepaid_upgrade_star_count <em>Optional</em>. Number of Telegram Stars that were prepaid for the ability to upgrade the gift
     * @param True|null $is_upgrade_separate <em>Optional</em>. <em>True</em>, if the gift&#39;s upgrade was purchased after the gift was sent
     * @param True|null $can_be_upgraded <em>Optional</em>. <em>True</em>, if the gift can be upgraded to a unique gift
     * @param string|null $text <em>Optional</em>. Text of the message that was added to the gift
     * @param array|null $entities <em>Optional</em>. Special entities that appear in the text
     * @param True|null $is_private <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
     * @param int|null $unique_gift_number <em>Optional</em>. Unique number reserved for this gift when upgraded. See the <em>number</em> field in <a href="https://core.telegram.org/bots/api#uniquegift">UniqueGift</a>
     */
    public static function make(Gift $gift = null, string $owned_gift_id = null, int $convert_star_count = null, int $prepaid_upgrade_star_count = null, True $is_upgrade_separate = null, True $can_be_upgraded = null, string $text = null, array $entities = null, True $is_private = null, int $unique_gift_number = null): self
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