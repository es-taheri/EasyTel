<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes actions that a non-administrator user is allowed to take in a chat.
 * @method self can_send_messages(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send text messages, contacts, giveaways, giveaway winners, invoices, locations and venues
 * @method self can_send_audios(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send audios
 * @method self can_send_documents(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send documents
 * @method self can_send_photos(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send photos
 * @method self can_send_videos(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send videos
 * @method self can_send_video_notes(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send video notes
 * @method self can_send_voice_notes(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send voice notes
 * @method self can_send_polls(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send polls and checklists
 * @method self can_send_other_messages(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to send animations, games, stickers and use inline bots
 * @method self can_add_web_page_previews(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to add web page previews to their messages
 * @method self can_edit_tag(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to edit their own tag
 * @method self can_change_info(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to change the chat title, photo and other settings. Ignored in public supergroups
 * @method self can_invite_users(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to invite new users to the chat
 * @method self can_pin_messages(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to pin messages. Ignored in public supergroups
 * @method self can_manage_topics(bool $value) <em>Optional</em>. <em>True</em>, if the user is allowed to create forum topics. If omitted defaults to the value of can_pin_messages
 */
class ChatPermissions
{
    public bool $can_send_messages;
    public bool $can_send_audios;
    public bool $can_send_documents;
    public bool $can_send_photos;
    public bool $can_send_videos;
    public bool $can_send_video_notes;
    public bool $can_send_voice_notes;
    public bool $can_send_polls;
    public bool $can_send_other_messages;
    public bool $can_add_web_page_previews;
    public bool $can_edit_tag;
    public bool $can_change_info;
    public bool $can_invite_users;
    public bool $can_pin_messages;
    public bool $can_manage_topics;

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
     * Describes actions that a non-administrator user is allowed to take in a chat.
     * @param bool|null $can_send_messages <em>Optional</em>. <em>True</em>, if the user is allowed to send text messages, contacts, giveaways, giveaway winners, invoices, locations and venues
     * @param bool|null $can_send_audios <em>Optional</em>. <em>True</em>, if the user is allowed to send audios
     * @param bool|null $can_send_documents <em>Optional</em>. <em>True</em>, if the user is allowed to send documents
     * @param bool|null $can_send_photos <em>Optional</em>. <em>True</em>, if the user is allowed to send photos
     * @param bool|null $can_send_videos <em>Optional</em>. <em>True</em>, if the user is allowed to send videos
     * @param bool|null $can_send_video_notes <em>Optional</em>. <em>True</em>, if the user is allowed to send video notes
     * @param bool|null $can_send_voice_notes <em>Optional</em>. <em>True</em>, if the user is allowed to send voice notes
     * @param bool|null $can_send_polls <em>Optional</em>. <em>True</em>, if the user is allowed to send polls and checklists
     * @param bool|null $can_send_other_messages <em>Optional</em>. <em>True</em>, if the user is allowed to send animations, games, stickers and use inline bots
     * @param bool|null $can_add_web_page_previews <em>Optional</em>. <em>True</em>, if the user is allowed to add web page previews to their messages
     * @param bool|null $can_edit_tag <em>Optional</em>. <em>True</em>, if the user is allowed to edit their own tag
     * @param bool|null $can_change_info <em>Optional</em>. <em>True</em>, if the user is allowed to change the chat title, photo and other settings. Ignored in public supergroups
     * @param bool|null $can_invite_users <em>Optional</em>. <em>True</em>, if the user is allowed to invite new users to the chat
     * @param bool|null $can_pin_messages <em>Optional</em>. <em>True</em>, if the user is allowed to pin messages. Ignored in public supergroups
     * @param bool|null $can_manage_topics <em>Optional</em>. <em>True</em>, if the user is allowed to create forum topics. If omitted defaults to the value of can_pin_messages
     */
    public static function make(bool $can_send_messages = null, bool $can_send_audios = null, bool $can_send_documents = null, bool $can_send_photos = null, bool $can_send_videos = null, bool $can_send_video_notes = null, bool $can_send_voice_notes = null, bool $can_send_polls = null, bool $can_send_other_messages = null, bool $can_add_web_page_previews = null, bool $can_edit_tag = null, bool $can_change_info = null, bool $can_invite_users = null, bool $can_pin_messages = null, bool $can_manage_topics = null): self
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