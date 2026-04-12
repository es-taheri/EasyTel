<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a Telegram user or bot.
 * @method self id(int $value) Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self is_bot(bool $value) <em>True</em>, if this user is a bot
 * @method self first_name(string $value) User&#39;s or bot&#39;s first name
 * @method self last_name(string $value) <em>Optional</em>. User&#39;s or bot&#39;s last name
 * @method self username(string $value) <em>Optional</em>. User&#39;s or bot&#39;s username
 * @method self language_code(string $value) <em>Optional</em>. <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a> of the user&#39;s language
 * @method self is_premium(True $value) <em>Optional</em>. <em>True</em>, if this user is a Telegram Premium user
 * @method self added_to_attachment_menu(True $value) <em>Optional</em>. <em>True</em>, if this user added the bot to the attachment menu
 * @method self can_join_groups(bool $value) <em>Optional</em>. <em>True</em>, if the bot can be invited to groups. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self can_read_all_group_messages(bool $value) <em>Optional</em>. <em>True</em>, if <a href="/bots/features#privacy-mode">privacy mode</a> is disabled for the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self supports_inline_queries(bool $value) <em>Optional</em>. <em>True</em>, if the bot supports inline queries. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self can_connect_to_business(bool $value) <em>Optional</em>. <em>True</em>, if the bot can be connected to a Telegram Business account to receive its messages. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self has_main_web_app(bool $value) <em>Optional</em>. <em>True</em>, if the bot has a main Web App. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self has_topics_enabled(bool $value) <em>Optional</em>. <em>True</em>, if the bot has forum topic mode enabled in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self allows_users_to_create_topics(bool $value) <em>Optional</em>. <em>True</em>, if the bot allows users to create and delete topics in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self can_manage_bots(bool $value) <em>Optional</em>. <em>True</em>, if other bots can be created to be controlled by the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 */
class User
{
    public int $id;
    public bool $is_bot;
    public string $first_name;
    public string $last_name;
    public string $username;
    public string $language_code;
    public True $is_premium;
    public True $added_to_attachment_menu;
    public bool $can_join_groups;
    public bool $can_read_all_group_messages;
    public bool $supports_inline_queries;
    public bool $can_connect_to_business;
    public bool $has_main_web_app;
    public bool $has_topics_enabled;
    public bool $allows_users_to_create_topics;
    public bool $can_manage_bots;

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
     * This object represents a Telegram user or bot.
     * @param int|null $id Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param bool|null $is_bot <em>True</em>, if this user is a bot
     * @param string|null $first_name User&#39;s or bot&#39;s first name
     * @param string|null $last_name <em>Optional</em>. User&#39;s or bot&#39;s last name
     * @param string|null $username <em>Optional</em>. User&#39;s or bot&#39;s username
     * @param string|null $language_code <em>Optional</em>. <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a> of the user&#39;s language
     * @param True|null $is_premium <em>Optional</em>. <em>True</em>, if this user is a Telegram Premium user
     * @param True|null $added_to_attachment_menu <em>Optional</em>. <em>True</em>, if this user added the bot to the attachment menu
     * @param bool|null $can_join_groups <em>Optional</em>. <em>True</em>, if the bot can be invited to groups. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $can_read_all_group_messages <em>Optional</em>. <em>True</em>, if <a href="/bots/features#privacy-mode">privacy mode</a> is disabled for the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $supports_inline_queries <em>Optional</em>. <em>True</em>, if the bot supports inline queries. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $can_connect_to_business <em>Optional</em>. <em>True</em>, if the bot can be connected to a Telegram Business account to receive its messages. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $has_main_web_app <em>Optional</em>. <em>True</em>, if the bot has a main Web App. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $has_topics_enabled <em>Optional</em>. <em>True</em>, if the bot has forum topic mode enabled in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $allows_users_to_create_topics <em>Optional</em>. <em>True</em>, if the bot allows users to create and delete topics in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $can_manage_bots <em>Optional</em>. <em>True</em>, if other bots can be created to be controlled by the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     */
    public static function make(int $id = null, bool $is_bot = null, string $first_name = null, string $last_name = null, string $username = null, string $language_code = null, True $is_premium = null, True $added_to_attachment_menu = null, bool $can_join_groups = null, bool $can_read_all_group_messages = null, bool $supports_inline_queries = null, bool $can_connect_to_business = null, bool $has_main_web_app = null, bool $has_topics_enabled = null, bool $allows_users_to_create_topics = null, bool $can_manage_bots = null): self
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