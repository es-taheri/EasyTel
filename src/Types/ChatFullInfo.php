<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains full information about a chat.
 * @method self id(int $value) Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self type(string $value) Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
 * @method self title(string $value) <em>Optional</em>. Title, for supergroups, channels and group chats
 * @method self username(string $value) <em>Optional</em>. Username, for private chats, supergroups and channels if available
 * @method self first_name(string $value) <em>Optional</em>. First name of the other party in a private chat
 * @method self last_name(string $value) <em>Optional</em>. Last name of the other party in a private chat
 * @method self is_forum(True $value) <em>Optional</em>. <em>True</em>, if the supergroup chat is a forum (has <a href="https://telegram.org/blog/topics-in-groups-collectible-usernames#topics-in-groups">topics</a> enabled)
 * @method self is_direct_messages(True $value) <em>Optional</em>. <em>True</em>, if the chat is the direct messages chat of a channel
 * @method self accent_color_id(int $value) Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header, and link preview. See <a href="https://core.telegram.org/bots/api#accent-colors">accent colors</a> for more details.
 * @method self max_reaction_count(int $value) The maximum number of reactions that can be set on a message in the chat
 * @method self photo(ChatPhoto $value) <em>Optional</em>. Chat photo
 * @method self active_usernames(array $value) <em>Optional</em>. If non-empty, the list of all <a href="https://telegram.org/blog/topics-in-groups-collectible-usernames#collectible-usernames">active chat usernames</a>; for private chats, supergroups and channels
 * @method self birthdate(Birthdate $value) <em>Optional</em>. For private chats, the date of birth of the user
 * @method self business_intro(BusinessIntro $value) <em>Optional</em>. For private chats with business accounts, the intro of the business
 * @method self business_location(BusinessLocation $value) <em>Optional</em>. For private chats with business accounts, the location of the business
 * @method self business_opening_hours(BusinessOpeningHours $value) <em>Optional</em>. For private chats with business accounts, the opening hours of the business
 * @method self personal_chat(Chat $value) <em>Optional</em>. For private chats, the personal channel of the user
 * @method self parent_chat(Chat $value) <em>Optional</em>. Information about the corresponding channel chat; for direct messages chats only
 * @method self available_reactions(array $value) <em>Optional</em>. List of available reactions allowed in the chat. If omitted, then all <a href="https://core.telegram.org/bots/api#reactiontypeemoji">emoji reactions</a> are allowed.
 * @method self background_custom_emoji_id(string $value) <em>Optional</em>. Custom emoji identifier of the emoji chosen by the chat for the reply header and link preview background
 * @method self profile_accent_color_id(int $value) <em>Optional</em>. Identifier of the accent color for the chat&#39;s profile background. See <a href="https://core.telegram.org/bots/api#profile-accent-colors">profile accent colors</a> for more details.
 * @method self profile_background_custom_emoji_id(string $value) <em>Optional</em>. Custom emoji identifier of the emoji chosen by the chat for its profile background
 * @method self emoji_status_custom_emoji_id(string $value) <em>Optional</em>. Custom emoji identifier of the emoji status of the chat or the other party in a private chat
 * @method self emoji_status_expiration_date(int $value) <em>Optional</em>. Expiration date of the emoji status of the chat or the other party in a private chat, in Unix time, if any
 * @method self bio(string $value) <em>Optional</em>. Bio of the other party in a private chat
 * @method self has_private_forwards(True $value) <em>Optional</em>. <em>True</em>, if privacy settings of the other party in the private chat allows to use <code>tg://user?id=&lt;user_id&gt;</code> links only in chats with the user
 * @method self has_restricted_voice_and_video_messages(True $value) <em>Optional</em>. <em>True</em>, if the privacy settings of the other party restrict sending voice and video note messages in the private chat
 * @method self join_to_send_messages(True $value) <em>Optional</em>. <em>True</em>, if users need to join the supergroup before they can send messages
 * @method self join_by_request(True $value) <em>Optional</em>. <em>True</em>, if all users directly joining the supergroup without using an invite link need to be approved by supergroup administrators
 * @method self description(string $value) <em>Optional</em>. Description, for groups, supergroups and channel chats
 * @method self invite_link(string $value) <em>Optional</em>. Primary invite link, for groups, supergroups and channel chats
 * @method self pinned_message(Message $value) <em>Optional</em>. The most recent pinned message (by sending date)
 * @method self permissions(ChatPermissions $value) <em>Optional</em>. Default chat member permissions, for groups and supergroups
 * @method self accepted_gift_types(AcceptedGiftTypes $value) Information about types of gifts that are accepted by the chat or by the corresponding user for private chats
 * @method self can_send_paid_media(True $value) <em>Optional</em>. <em>True</em>, if paid media messages can be sent or forwarded to the channel chat. The field is available only for channel chats.
 * @method self slow_mode_delay(int $value) <em>Optional</em>. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged user; in seconds
 * @method self unrestrict_boost_count(int $value) <em>Optional</em>. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to ignore slow mode and chat permissions
 * @method self message_auto_delete_time(int $value) <em>Optional</em>. The time after which all messages sent to the chat will be automatically deleted; in seconds
 * @method self has_aggressive_anti_spam_enabled(True $value) <em>Optional</em>. <em>True</em>, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to chat administrators.
 * @method self has_hidden_members(True $value) <em>Optional</em>. <em>True</em>, if non-administrators can only get the list of bots and administrators in the chat
 * @method self has_protected_content(True $value) <em>Optional</em>. <em>True</em>, if messages from the chat can&#39;t be forwarded to other chats
 * @method self has_visible_history(True $value) <em>Optional</em>. <em>True</em>, if new chat members will have access to old messages; available only to chat administrators
 * @method self sticker_set_name(string $value) <em>Optional</em>. For supergroups, name of the group sticker set
 * @method self can_set_sticker_set(True $value) <em>Optional</em>. <em>True</em>, if the bot can change the group sticker set
 * @method self custom_emoji_sticker_set_name(string $value) <em>Optional</em>. For supergroups, the name of the group&#39;s custom emoji sticker set. Custom emoji from this set can be used by all users and bots in the group.
 * @method self linked_chat_id(int $value) <em>Optional</em>. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method self location(ChatLocation $value) <em>Optional</em>. For supergroups, the location to which the supergroup is connected
 * @method self rating(UserRating $value) <em>Optional</em>. For private chats, the rating of the user if any
 * @method self first_profile_audio(Audio $value) <em>Optional</em>. For private chats, the first audio added to the profile of the user
 * @method self unique_gift_colors(UniqueGiftColors $value) <em>Optional</em>. The color scheme based on a unique gift that must be used for the chat&#39;s name, message replies and link previews
 * @method self paid_message_star_count(int $value) <em>Optional</em>. The number of Telegram Stars a general user have to pay to send a message to the chat
 */
class ChatFullInfo
{
    public int $id;
    public string $type;
    public string $title;
    public string $username;
    public string $first_name;
    public string $last_name;
    public True $is_forum;
    public True $is_direct_messages;
    public int $accent_color_id;
    public int $max_reaction_count;
    public ChatPhoto $photo;
    public array $active_usernames;
    public Birthdate $birthdate;
    public BusinessIntro $business_intro;
    public BusinessLocation $business_location;
    public BusinessOpeningHours $business_opening_hours;
    public Chat $personal_chat;
    public Chat $parent_chat;
    public array $available_reactions;
    public string $background_custom_emoji_id;
    public int $profile_accent_color_id;
    public string $profile_background_custom_emoji_id;
    public string $emoji_status_custom_emoji_id;
    public int $emoji_status_expiration_date;
    public string $bio;
    public True $has_private_forwards;
    public True $has_restricted_voice_and_video_messages;
    public True $join_to_send_messages;
    public True $join_by_request;
    public string $description;
    public string $invite_link;
    public Message $pinned_message;
    public ChatPermissions $permissions;
    public AcceptedGiftTypes $accepted_gift_types;
    public True $can_send_paid_media;
    public int $slow_mode_delay;
    public int $unrestrict_boost_count;
    public int $message_auto_delete_time;
    public True $has_aggressive_anti_spam_enabled;
    public True $has_hidden_members;
    public True $has_protected_content;
    public True $has_visible_history;
    public string $sticker_set_name;
    public True $can_set_sticker_set;
    public string $custom_emoji_sticker_set_name;
    public int $linked_chat_id;
    public ChatLocation $location;
    public UserRating $rating;
    public Audio $first_profile_audio;
    public UniqueGiftColors $unique_gift_colors;
    public int $paid_message_star_count;

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
        if (isset($update['photo'])) $this->photo = new ChatPhoto($update['photo']);
        if (isset($update['birthdate'])) $this->birthdate = new Birthdate($update['birthdate']);
        if (isset($update['business_intro'])) $this->business_intro = new BusinessIntro($update['business_intro']);
        if (isset($update['business_location'])) $this->business_location = new BusinessLocation($update['business_location']);
        if (isset($update['business_opening_hours'])) $this->business_opening_hours = new BusinessOpeningHours($update['business_opening_hours']);
        if (isset($update['personal_chat'])) $this->personal_chat = new Chat($update['personal_chat']);
        if (isset($update['parent_chat'])) $this->parent_chat = new Chat($update['parent_chat']);
        if (isset($update['pinned_message'])) $this->pinned_message = new Message($update['pinned_message']);
        if (isset($update['permissions'])) $this->permissions = new ChatPermissions($update['permissions']);
        if (isset($update['accepted_gift_types'])) $this->accepted_gift_types = new AcceptedGiftTypes($update['accepted_gift_types']);
        if (isset($update['location'])) $this->location = new ChatLocation($update['location']);
        if (isset($update['rating'])) $this->rating = new UserRating($update['rating']);
        if (isset($update['first_profile_audio'])) $this->first_profile_audio = new Audio($update['first_profile_audio']);
        if (isset($update['unique_gift_colors'])) $this->unique_gift_colors = new UniqueGiftColors($update['unique_gift_colors']);
    }

    /**
     * This object contains full information about a chat.
     * @param int|null $id Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param string|null $type Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
     * @param string|null $title <em>Optional</em>. Title, for supergroups, channels and group chats
     * @param string|null $username <em>Optional</em>. Username, for private chats, supergroups and channels if available
     * @param string|null $first_name <em>Optional</em>. First name of the other party in a private chat
     * @param string|null $last_name <em>Optional</em>. Last name of the other party in a private chat
     * @param True|null $is_forum <em>Optional</em>. <em>True</em>, if the supergroup chat is a forum (has <a href="https://telegram.org/blog/topics-in-groups-collectible-usernames#topics-in-groups">topics</a> enabled)
     * @param True|null $is_direct_messages <em>Optional</em>. <em>True</em>, if the chat is the direct messages chat of a channel
     * @param int|null $accent_color_id Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header, and link preview. See <a href="https://core.telegram.org/bots/api#accent-colors">accent colors</a> for more details.
     * @param int|null $max_reaction_count The maximum number of reactions that can be set on a message in the chat
     * @param ChatPhoto|null $photo <em>Optional</em>. Chat photo
     * @param array|null $active_usernames <em>Optional</em>. If non-empty, the list of all <a href="https://telegram.org/blog/topics-in-groups-collectible-usernames#collectible-usernames">active chat usernames</a>; for private chats, supergroups and channels
     * @param Birthdate|null $birthdate <em>Optional</em>. For private chats, the date of birth of the user
     * @param BusinessIntro|null $business_intro <em>Optional</em>. For private chats with business accounts, the intro of the business
     * @param BusinessLocation|null $business_location <em>Optional</em>. For private chats with business accounts, the location of the business
     * @param BusinessOpeningHours|null $business_opening_hours <em>Optional</em>. For private chats with business accounts, the opening hours of the business
     * @param Chat|null $personal_chat <em>Optional</em>. For private chats, the personal channel of the user
     * @param Chat|null $parent_chat <em>Optional</em>. Information about the corresponding channel chat; for direct messages chats only
     * @param array|null $available_reactions <em>Optional</em>. List of available reactions allowed in the chat. If omitted, then all <a href="https://core.telegram.org/bots/api#reactiontypeemoji">emoji reactions</a> are allowed.
     * @param string|null $background_custom_emoji_id <em>Optional</em>. Custom emoji identifier of the emoji chosen by the chat for the reply header and link preview background
     * @param int|null $profile_accent_color_id <em>Optional</em>. Identifier of the accent color for the chat&#39;s profile background. See <a href="https://core.telegram.org/bots/api#profile-accent-colors">profile accent colors</a> for more details.
     * @param string|null $profile_background_custom_emoji_id <em>Optional</em>. Custom emoji identifier of the emoji chosen by the chat for its profile background
     * @param string|null $emoji_status_custom_emoji_id <em>Optional</em>. Custom emoji identifier of the emoji status of the chat or the other party in a private chat
     * @param int|null $emoji_status_expiration_date <em>Optional</em>. Expiration date of the emoji status of the chat or the other party in a private chat, in Unix time, if any
     * @param string|null $bio <em>Optional</em>. Bio of the other party in a private chat
     * @param True|null $has_private_forwards <em>Optional</em>. <em>True</em>, if privacy settings of the other party in the private chat allows to use <code>tg://user?id=&lt;user_id&gt;</code> links only in chats with the user
     * @param True|null $has_restricted_voice_and_video_messages <em>Optional</em>. <em>True</em>, if the privacy settings of the other party restrict sending voice and video note messages in the private chat
     * @param True|null $join_to_send_messages <em>Optional</em>. <em>True</em>, if users need to join the supergroup before they can send messages
     * @param True|null $join_by_request <em>Optional</em>. <em>True</em>, if all users directly joining the supergroup without using an invite link need to be approved by supergroup administrators
     * @param string|null $description <em>Optional</em>. Description, for groups, supergroups and channel chats
     * @param string|null $invite_link <em>Optional</em>. Primary invite link, for groups, supergroups and channel chats
     * @param Message|null $pinned_message <em>Optional</em>. The most recent pinned message (by sending date)
     * @param ChatPermissions|null $permissions <em>Optional</em>. Default chat member permissions, for groups and supergroups
     * @param AcceptedGiftTypes|null $accepted_gift_types Information about types of gifts that are accepted by the chat or by the corresponding user for private chats
     * @param True|null $can_send_paid_media <em>Optional</em>. <em>True</em>, if paid media messages can be sent or forwarded to the channel chat. The field is available only for channel chats.
     * @param int|null $slow_mode_delay <em>Optional</em>. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged user; in seconds
     * @param int|null $unrestrict_boost_count <em>Optional</em>. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to ignore slow mode and chat permissions
     * @param int|null $message_auto_delete_time <em>Optional</em>. The time after which all messages sent to the chat will be automatically deleted; in seconds
     * @param True|null $has_aggressive_anti_spam_enabled <em>Optional</em>. <em>True</em>, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to chat administrators.
     * @param True|null $has_hidden_members <em>Optional</em>. <em>True</em>, if non-administrators can only get the list of bots and administrators in the chat
     * @param True|null $has_protected_content <em>Optional</em>. <em>True</em>, if messages from the chat can&#39;t be forwarded to other chats
     * @param True|null $has_visible_history <em>Optional</em>. <em>True</em>, if new chat members will have access to old messages; available only to chat administrators
     * @param string|null $sticker_set_name <em>Optional</em>. For supergroups, name of the group sticker set
     * @param True|null $can_set_sticker_set <em>Optional</em>. <em>True</em>, if the bot can change the group sticker set
     * @param string|null $custom_emoji_sticker_set_name <em>Optional</em>. For supergroups, the name of the group&#39;s custom emoji sticker set. Custom emoji from this set can be used by all users and bots in the group.
     * @param int|null $linked_chat_id <em>Optional</em>. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
     * @param ChatLocation|null $location <em>Optional</em>. For supergroups, the location to which the supergroup is connected
     * @param UserRating|null $rating <em>Optional</em>. For private chats, the rating of the user if any
     * @param Audio|null $first_profile_audio <em>Optional</em>. For private chats, the first audio added to the profile of the user
     * @param UniqueGiftColors|null $unique_gift_colors <em>Optional</em>. The color scheme based on a unique gift that must be used for the chat&#39;s name, message replies and link previews
     * @param int|null $paid_message_star_count <em>Optional</em>. The number of Telegram Stars a general user have to pay to send a message to the chat
     */
    public static function make(int $id = null, string $type = null, string $title = null, string $username = null, string $first_name = null, string $last_name = null, True $is_forum = null, True $is_direct_messages = null, int $accent_color_id = null, int $max_reaction_count = null, ChatPhoto $photo = null, array $active_usernames = null, Birthdate $birthdate = null, BusinessIntro $business_intro = null, BusinessLocation $business_location = null, BusinessOpeningHours $business_opening_hours = null, Chat $personal_chat = null, Chat $parent_chat = null, array $available_reactions = null, string $background_custom_emoji_id = null, int $profile_accent_color_id = null, string $profile_background_custom_emoji_id = null, string $emoji_status_custom_emoji_id = null, int $emoji_status_expiration_date = null, string $bio = null, True $has_private_forwards = null, True $has_restricted_voice_and_video_messages = null, True $join_to_send_messages = null, True $join_by_request = null, string $description = null, string $invite_link = null, Message $pinned_message = null, ChatPermissions $permissions = null, AcceptedGiftTypes $accepted_gift_types = null, True $can_send_paid_media = null, int $slow_mode_delay = null, int $unrestrict_boost_count = null, int $message_auto_delete_time = null, True $has_aggressive_anti_spam_enabled = null, True $has_hidden_members = null, True $has_protected_content = null, True $has_visible_history = null, string $sticker_set_name = null, True $can_set_sticker_set = null, string $custom_emoji_sticker_set_name = null, int $linked_chat_id = null, ChatLocation $location = null, UserRating $rating = null, Audio $first_profile_audio = null, UniqueGiftColors $unique_gift_colors = null, int $paid_message_star_count = null): self
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