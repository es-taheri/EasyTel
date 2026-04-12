<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a regular gift owned by a user or a chat.
 * @method self type(string $value) Type of the gift, always “regular”
 * @method self gift(Gift $value) Information about the regular gift
 * @method self owned_gift_id(string $value) <em>Optional</em>. Unique identifier of the gift for the bot; for gifts received on behalf of business accounts only
 * @method self sender_user(User $value) <em>Optional</em>. Sender of the gift if it is a known user
 * @method self send_date(int $value) Date the gift was sent in Unix time
 * @method self text(string $value) <em>Optional</em>. Text of the message that was added to the gift
 * @method self entities(array $value) <em>Optional</em>. Special entities that appear in the text
 * @method self is_private(True $value) <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method self is_saved(True $value) <em>Optional</em>. <em>True</em>, if the gift is displayed on the account&#39;s profile page; for gifts received on behalf of business accounts only
 * @method self can_be_upgraded(True $value) <em>Optional</em>. <em>True</em>, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
 * @method self was_refunded(True $value) <em>Optional</em>. <em>True</em>, if the gift was refunded and isn&#39;t available anymore
 * @method self convert_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
 * @method self prepaid_upgrade_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that were paid for the ability to upgrade the gift
 * @method self is_upgrade_separate(True $value) <em>Optional</em>. <em>True</em>, if the gift&#39;s upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
 * @method self unique_gift_number(int $value) <em>Optional</em>. Unique number reserved for this gift when upgraded. See the <em>number</em> field in <a href="https://core.telegram.org/bots/api#uniquegift">UniqueGift</a>
 */
class OwnedGiftRegular
{
    public string $type;
    public Gift $gift;
    public string $owned_gift_id;
    public User $sender_user;
    public int $send_date;
    public string $text;
    public array $entities;
    public True $is_private;
    public True $is_saved;
    public True $can_be_upgraded;
    public True $was_refunded;
    public int $convert_star_count;
    public int $prepaid_upgrade_star_count;
    public True $is_upgrade_separate;
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
        if (isset($update['sender_user'])) $this->sender_user = new User($update['sender_user']);
    }

    /**
     * Describes a regular gift owned by a user or a chat.
     * @param string|null $type Type of the gift, always “regular”
     * @param Gift|null $gift Information about the regular gift
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the gift for the bot; for gifts received on behalf of business accounts only
     * @param User|null $sender_user <em>Optional</em>. Sender of the gift if it is a known user
     * @param int|null $send_date Date the gift was sent in Unix time
     * @param string|null $text <em>Optional</em>. Text of the message that was added to the gift
     * @param array|null $entities <em>Optional</em>. Special entities that appear in the text
     * @param True|null $is_private <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
     * @param True|null $is_saved <em>Optional</em>. <em>True</em>, if the gift is displayed on the account&#39;s profile page; for gifts received on behalf of business accounts only
     * @param True|null $can_be_upgraded <em>Optional</em>. <em>True</em>, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
     * @param True|null $was_refunded <em>Optional</em>. <em>True</em>, if the gift was refunded and isn&#39;t available anymore
     * @param int|null $convert_star_count <em>Optional</em>. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
     * @param int|null $prepaid_upgrade_star_count <em>Optional</em>. Number of Telegram Stars that were paid for the ability to upgrade the gift
     * @param True|null $is_upgrade_separate <em>Optional</em>. <em>True</em>, if the gift&#39;s upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
     * @param int|null $unique_gift_number <em>Optional</em>. Unique number reserved for this gift when upgraded. See the <em>number</em> field in <a href="https://core.telegram.org/bots/api#uniquegift">UniqueGift</a>
     */
    public static function make(string $type = null, Gift $gift = null, string $owned_gift_id = null, User $sender_user = null, int $send_date = null, string $text = null, array $entities = null, True $is_private = null, True $is_saved = null, True $can_be_upgraded = null, True $was_refunded = null, int $convert_star_count = null, int $prepaid_upgrade_star_count = null, True $is_upgrade_separate = null, int $unique_gift_number = null): self
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