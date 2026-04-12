<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the rights of a business bot.
 * @method self can_reply(True $value) <em>Optional</em>. <em>True</em>, if the bot can send and edit messages in the private chats that had incoming messages in the last 24 hours
 * @method self can_read_messages(True $value) <em>Optional</em>. <em>True</em>, if the bot can mark incoming private messages as read
 * @method self can_delete_sent_messages(True $value) <em>Optional</em>. <em>True</em>, if the bot can delete messages sent by the bot
 * @method self can_delete_all_messages(True $value) <em>Optional</em>. <em>True</em>, if the bot can delete all private messages in managed chats
 * @method self can_edit_name(True $value) <em>Optional</em>. <em>True</em>, if the bot can edit the first and last name of the business account
 * @method self can_edit_bio(True $value) <em>Optional</em>. <em>True</em>, if the bot can edit the bio of the business account
 * @method self can_edit_profile_photo(True $value) <em>Optional</em>. <em>True</em>, if the bot can edit the profile photo of the business account
 * @method self can_edit_username(True $value) <em>Optional</em>. <em>True</em>, if the bot can edit the username of the business account
 * @method self can_change_gift_settings(True $value) <em>Optional</em>. <em>True</em>, if the bot can change the privacy settings pertaining to gifts for the business account
 * @method self can_view_gifts_and_stars(True $value) <em>Optional</em>. <em>True</em>, if the bot can view gifts and the amount of Telegram Stars owned by the business account
 * @method self can_convert_gifts_to_stars(True $value) <em>Optional</em>. <em>True</em>, if the bot can convert regular gifts owned by the business account to Telegram Stars
 * @method self can_transfer_and_upgrade_gifts(True $value) <em>Optional</em>. <em>True</em>, if the bot can transfer and upgrade gifts owned by the business account
 * @method self can_transfer_stars(True $value) <em>Optional</em>. <em>True</em>, if the bot can transfer Telegram Stars received by the business account to its own account, or use them to upgrade and transfer gifts
 * @method self can_manage_stories(True $value) <em>Optional</em>. <em>True</em>, if the bot can post, edit and delete stories on behalf of the business account
 */
class BusinessBotRights
{
    public True $can_reply;
    public True $can_read_messages;
    public True $can_delete_sent_messages;
    public True $can_delete_all_messages;
    public True $can_edit_name;
    public True $can_edit_bio;
    public True $can_edit_profile_photo;
    public True $can_edit_username;
    public True $can_change_gift_settings;
    public True $can_view_gifts_and_stars;
    public True $can_convert_gifts_to_stars;
    public True $can_transfer_and_upgrade_gifts;
    public True $can_transfer_stars;
    public True $can_manage_stories;

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
        
    }

    /**
     * Represents the rights of a business bot.
     * @param True|null $can_reply <em>Optional</em>. <em>True</em>, if the bot can send and edit messages in the private chats that had incoming messages in the last 24 hours
     * @param True|null $can_read_messages <em>Optional</em>. <em>True</em>, if the bot can mark incoming private messages as read
     * @param True|null $can_delete_sent_messages <em>Optional</em>. <em>True</em>, if the bot can delete messages sent by the bot
     * @param True|null $can_delete_all_messages <em>Optional</em>. <em>True</em>, if the bot can delete all private messages in managed chats
     * @param True|null $can_edit_name <em>Optional</em>. <em>True</em>, if the bot can edit the first and last name of the business account
     * @param True|null $can_edit_bio <em>Optional</em>. <em>True</em>, if the bot can edit the bio of the business account
     * @param True|null $can_edit_profile_photo <em>Optional</em>. <em>True</em>, if the bot can edit the profile photo of the business account
     * @param True|null $can_edit_username <em>Optional</em>. <em>True</em>, if the bot can edit the username of the business account
     * @param True|null $can_change_gift_settings <em>Optional</em>. <em>True</em>, if the bot can change the privacy settings pertaining to gifts for the business account
     * @param True|null $can_view_gifts_and_stars <em>Optional</em>. <em>True</em>, if the bot can view gifts and the amount of Telegram Stars owned by the business account
     * @param True|null $can_convert_gifts_to_stars <em>Optional</em>. <em>True</em>, if the bot can convert regular gifts owned by the business account to Telegram Stars
     * @param True|null $can_transfer_and_upgrade_gifts <em>Optional</em>. <em>True</em>, if the bot can transfer and upgrade gifts owned by the business account
     * @param True|null $can_transfer_stars <em>Optional</em>. <em>True</em>, if the bot can transfer Telegram Stars received by the business account to its own account, or use them to upgrade and transfer gifts
     * @param True|null $can_manage_stories <em>Optional</em>. <em>True</em>, if the bot can post, edit and delete stories on behalf of the business account
     */
    public static function make(True $can_reply = null, True $can_read_messages = null, True $can_delete_sent_messages = null, True $can_delete_all_messages = null, True $can_edit_name = null, True $can_edit_bio = null, True $can_edit_profile_photo = null, True $can_edit_username = null, True $can_change_gift_settings = null, True $can_view_gifts_and_stars = null, True $can_convert_gifts_to_stars = null, True $can_transfer_and_upgrade_gifts = null, True $can_transfer_stars = null, True $can_manage_stories = null): self
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