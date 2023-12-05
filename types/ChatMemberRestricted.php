<?php

namespace EasyTel\types;

use Nette\Utils\Json;

class ChatMemberRestricted
{
    public string $status;
    public User $user;
    public bool $is_member;
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
    public bool $can_change_info;
    public bool $can_invite_users;
    public bool $can_pin_messages;
    public bool $can_manage_topics;
    public int $until_date;
    
    public function __construct(array $update)
    {
        $objects = array_keys($update);
        foreach ($objects as $object):
            $this->{$object} = $update[$object];
        endforeach;
        if (isset($update['user'])) $this->user = new User($update['user']);
    }
}