<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the rights of a business bot.
 * @method self can_reply(bool $value) <em>Optional</em>. <em>True</em>, if the bot can send and edit messages in the private chats that had incoming messages in the last 24 hours
 * @method self can_read_messages(bool $value) <em>Optional</em>. <em>True</em>, if the bot can mark incoming private messages as read
 * @method self can_delete_sent_messages(bool $value) <em>Optional</em>. <em>True</em>, if the bot can delete messages sent by the bot
 * @method self can_delete_all_messages(bool $value) <em>Optional</em>. <em>True</em>, if the bot can delete all private messages in managed chats
 * @method self can_edit_name(bool $value) <em>Optional</em>. <em>True</em>, if the bot can edit the first and last name of the business account
 * @method self can_edit_bio(bool $value) <em>Optional</em>. <em>True</em>, if the bot can edit the bio of the business account
 * @method self can_edit_profile_photo(bool $value) <em>Optional</em>. <em>True</em>, if the bot can edit the profile photo of the business account
 * @method self can_edit_username(bool $value) <em>Optional</em>. <em>True</em>, if the bot can edit the username of the business account
 * @method self can_change_gift_settings(bool $value) <em>Optional</em>. <em>True</em>, if the bot can change the privacy settings pertaining to gifts for the business account
 * @method self can_view_gifts_and_stars(bool $value) <em>Optional</em>. <em>True</em>, if the bot can view gifts and the amount of Telegram Stars owned by the business account
 * @method self can_convert_gifts_to_stars(bool $value) <em>Optional</em>. <em>True</em>, if the bot can convert regular gifts owned by the business account to Telegram Stars
 * @method self can_transfer_and_upgrade_gifts(bool $value) <em>Optional</em>. <em>True</em>, if the bot can transfer and upgrade gifts owned by the business account
 * @method self can_transfer_stars(bool $value) <em>Optional</em>. <em>True</em>, if the bot can transfer Telegram Stars received by the business account to its own account, or use them to upgrade and transfer gifts
 * @method self can_manage_stories(bool $value) <em>Optional</em>. <em>True</em>, if the bot can post, edit and delete stories on behalf of the business account
 */
class BusinessBotRights
{
    /** @var bool */
    public bool $can_reply;
    /** @var bool */
    public bool $can_read_messages;
    /** @var bool */
    public bool $can_delete_sent_messages;
    /** @var bool */
    public bool $can_delete_all_messages;
    /** @var bool */
    public bool $can_edit_name;
    /** @var bool */
    public bool $can_edit_bio;
    /** @var bool */
    public bool $can_edit_profile_photo;
    /** @var bool */
    public bool $can_edit_username;
    /** @var bool */
    public bool $can_change_gift_settings;
    /** @var bool */
    public bool $can_view_gifts_and_stars;
    /** @var bool */
    public bool $can_convert_gifts_to_stars;
    /** @var bool */
    public bool $can_transfer_and_upgrade_gifts;
    /** @var bool */
    public bool $can_transfer_stars;
    /** @var bool */
    public bool $can_manage_stories;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents the rights of a business bot.
     * @param bool|null $can_reply <em>Optional</em>. <em>True</em>, if the bot can send and edit messages in the private chats that had incoming messages in the last 24 hours
     * @param bool|null $can_read_messages <em>Optional</em>. <em>True</em>, if the bot can mark incoming private messages as read
     * @param bool|null $can_delete_sent_messages <em>Optional</em>. <em>True</em>, if the bot can delete messages sent by the bot
     * @param bool|null $can_delete_all_messages <em>Optional</em>. <em>True</em>, if the bot can delete all private messages in managed chats
     * @param bool|null $can_edit_name <em>Optional</em>. <em>True</em>, if the bot can edit the first and last name of the business account
     * @param bool|null $can_edit_bio <em>Optional</em>. <em>True</em>, if the bot can edit the bio of the business account
     * @param bool|null $can_edit_profile_photo <em>Optional</em>. <em>True</em>, if the bot can edit the profile photo of the business account
     * @param bool|null $can_edit_username <em>Optional</em>. <em>True</em>, if the bot can edit the username of the business account
     * @param bool|null $can_change_gift_settings <em>Optional</em>. <em>True</em>, if the bot can change the privacy settings pertaining to gifts for the business account
     * @param bool|null $can_view_gifts_and_stars <em>Optional</em>. <em>True</em>, if the bot can view gifts and the amount of Telegram Stars owned by the business account
     * @param bool|null $can_convert_gifts_to_stars <em>Optional</em>. <em>True</em>, if the bot can convert regular gifts owned by the business account to Telegram Stars
     * @param bool|null $can_transfer_and_upgrade_gifts <em>Optional</em>. <em>True</em>, if the bot can transfer and upgrade gifts owned by the business account
     * @param bool|null $can_transfer_stars <em>Optional</em>. <em>True</em>, if the bot can transfer Telegram Stars received by the business account to its own account, or use them to upgrade and transfer gifts
     * @param bool|null $can_manage_stories <em>Optional</em>. <em>True</em>, if the bot can post, edit and delete stories on behalf of the business account
     */
    public static function make(bool $can_reply = null, bool $can_read_messages = null, bool $can_delete_sent_messages = null, bool $can_delete_all_messages = null, bool $can_edit_name = null, bool $can_edit_bio = null, bool $can_edit_profile_photo = null, bool $can_edit_username = null, bool $can_change_gift_settings = null, bool $can_view_gifts_and_stars = null, bool $can_convert_gifts_to_stars = null, bool $can_transfer_and_upgrade_gifts = null, bool $can_transfer_stars = null, bool $can_manage_stories = null): self
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