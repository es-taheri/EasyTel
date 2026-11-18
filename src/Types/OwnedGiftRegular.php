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
 * @method self entities(MessageEntity[] $value) <em>Optional</em>. Special entities that appear in the text
 * @method self is_private(bool $value) <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method self is_saved(bool $value) <em>Optional</em>. <em>True</em>, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
 * @method self can_be_upgraded(bool $value) <em>Optional</em>. <em>True</em>, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
 * @method self was_refunded(bool $value) <em>Optional</em>. <em>True</em>, if the gift was refunded and isn't available anymore
 * @method self convert_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
 * @method self prepaid_upgrade_star_count(int $value) <em>Optional</em>. Number of Telegram Stars that were paid for the ability to upgrade the gift
 * @method self is_upgrade_separate(bool $value) <em>Optional</em>. <em>True</em>, if the gift's upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
 * @method self unique_gift_number(int $value) <em>Optional</em>. Unique number reserved for this gift when upgraded. See the <em>number</em> field in <a href="https://core.telegram.org/bots/api#uniquegift">UniqueGift</a>.
 */
class OwnedGiftRegular
{
    /** @var string */
    public string $type;
    /** @var Gift */
    public Gift $gift;
    /** @var string */
    public string $owned_gift_id;
    /** @var User */
    public User $sender_user;
    /** @var int */
    public int $send_date;
    /** @var string */
    public string $text;
    /** @var MessageEntity[] */
    public array $entities;
    /** @var bool */
    public bool $is_private;
    /** @var bool */
    public bool $is_saved;
    /** @var bool */
    public bool $can_be_upgraded;
    /** @var bool */
    public bool $was_refunded;
    /** @var int */
    public int $convert_star_count;
    /** @var int */
    public int $prepaid_upgrade_star_count;
    /** @var bool */
    public bool $is_upgrade_separate;
    /** @var int */
    public int $unique_gift_number;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a regular gift owned by a user or a chat.
     * @param string|null $type Type of the gift, always “regular”
     * @param Gift|null $gift Information about the regular gift
     * @param string|null $owned_gift_id <em>Optional</em>. Unique identifier of the gift for the bot; for gifts received on behalf of business accounts only
     * @param User|null $sender_user <em>Optional</em>. Sender of the gift if it is a known user
     * @param int|null $send_date Date the gift was sent in Unix time
     * @param string|null $text <em>Optional</em>. Text of the message that was added to the gift
     * @param MessageEntity[]|null $entities <em>Optional</em>. Special entities that appear in the text
     * @param bool|null $is_private <em>Optional</em>. <em>True</em>, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
     * @param bool|null $is_saved <em>Optional</em>. <em>True</em>, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
     * @param bool|null $can_be_upgraded <em>Optional</em>. <em>True</em>, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
     * @param bool|null $was_refunded <em>Optional</em>. <em>True</em>, if the gift was refunded and isn't available anymore
     * @param int|null $convert_star_count <em>Optional</em>. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
     * @param int|null $prepaid_upgrade_star_count <em>Optional</em>. Number of Telegram Stars that were paid for the ability to upgrade the gift
     * @param bool|null $is_upgrade_separate <em>Optional</em>. <em>True</em>, if the gift's upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
     * @param int|null $unique_gift_number <em>Optional</em>. Unique number reserved for this gift when upgraded. See the <em>number</em> field in <a href="https://core.telegram.org/bots/api#uniquegift">UniqueGift</a>.
     */
    public static function make(string $type = null, Gift $gift = null, string $owned_gift_id = null, User $sender_user = null, int $send_date = null, string $text = null, array $entities = null, bool $is_private = null, bool $is_saved = null, bool $can_be_upgraded = null, bool $was_refunded = null, int $convert_star_count = null, int $prepaid_upgrade_star_count = null, bool $is_upgrade_separate = null, int $unique_gift_number = null): self
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