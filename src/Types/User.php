<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a Telegram user or bot.
 * @method self id(int $value) Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self is_bot(bool $value) <em>True</em>, if this user is a bot
 * @method self first_name(string $value) User's or bot's first name
 * @method self last_name(string $value) <em>Optional</em>. User's or bot's last name
 * @method self username(string $value) <em>Optional</em>. User's or bot's username
 * @method self language_code(string $value) <em>Optional</em>. <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a> of the user's language
 * @method self is_premium(bool $value) <em>Optional</em>. <em>True</em>, if this user is a Telegram Premium user
 * @method self added_to_attachment_menu(bool $value) <em>Optional</em>. <em>True</em>, if this user added the bot to the attachment menu
 * @method self can_join_groups(bool $value) <em>Optional</em>. <em>True</em>, if the bot can be invited to groups. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self can_read_all_group_messages(bool $value) <em>Optional</em>. <em>True</em>, if <a href="/bots/features#privacy-mode">privacy mode</a> is disabled for the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self supports_guest_queries(bool $value) <em>Optional</em>. <em>True</em>, if the bot supports guest queries from chats it is not a member of. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self supports_inline_queries(bool $value) <em>Optional</em>. <em>True</em>, if the bot supports inline queries. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self can_connect_to_business(bool $value) <em>Optional</em>. <em>True</em>, if the bot can be connected to a user account to manage it. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self has_main_web_app(bool $value) <em>Optional</em>. <em>True</em>, if the bot has a main Web App. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self has_topics_enabled(bool $value) <em>Optional</em>. <em>True</em>, if the bot has forum topic mode enabled in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self allows_users_to_create_topics(bool $value) <em>Optional</em>. <em>True</em>, if the bot allows users to create and delete topics in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self can_manage_bots(bool $value) <em>Optional</em>. <em>True</em>, if other bots can be created to be controlled by the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 * @method self supports_join_request_queries(bool $value) <em>Optional</em>. <em>True</em>, if the bot supports join request queries and can be assigned to process them. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
 */
class User
{
    /** @var int */
    public int $id;
    /** @var bool */
    public bool $is_bot;
    /** @var string */
    public string $first_name;
    /** @var string */
    public string $last_name;
    /** @var string */
    public string $username;
    /** @var string */
    public string $language_code;
    /** @var bool */
    public bool $is_premium;
    /** @var bool */
    public bool $added_to_attachment_menu;
    /** @var bool */
    public bool $can_join_groups;
    /** @var bool */
    public bool $can_read_all_group_messages;
    /** @var bool */
    public bool $supports_guest_queries;
    /** @var bool */
    public bool $supports_inline_queries;
    /** @var bool */
    public bool $can_connect_to_business;
    /** @var bool */
    public bool $has_main_web_app;
    /** @var bool */
    public bool $has_topics_enabled;
    /** @var bool */
    public bool $allows_users_to_create_topics;
    /** @var bool */
    public bool $can_manage_bots;
    /** @var bool */
    public bool $supports_join_request_queries;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a Telegram user or bot.
     * @param int|null $id Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param bool|null $is_bot <em>True</em>, if this user is a bot
     * @param string|null $first_name User's or bot's first name
     * @param string|null $last_name <em>Optional</em>. User's or bot's last name
     * @param string|null $username <em>Optional</em>. User's or bot's username
     * @param string|null $language_code <em>Optional</em>. <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a> of the user's language
     * @param bool|null $is_premium <em>Optional</em>. <em>True</em>, if this user is a Telegram Premium user
     * @param bool|null $added_to_attachment_menu <em>Optional</em>. <em>True</em>, if this user added the bot to the attachment menu
     * @param bool|null $can_join_groups <em>Optional</em>. <em>True</em>, if the bot can be invited to groups. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $can_read_all_group_messages <em>Optional</em>. <em>True</em>, if <a href="/bots/features#privacy-mode">privacy mode</a> is disabled for the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $supports_guest_queries <em>Optional</em>. <em>True</em>, if the bot supports guest queries from chats it is not a member of. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $supports_inline_queries <em>Optional</em>. <em>True</em>, if the bot supports inline queries. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $can_connect_to_business <em>Optional</em>. <em>True</em>, if the bot can be connected to a user account to manage it. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $has_main_web_app <em>Optional</em>. <em>True</em>, if the bot has a main Web App. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $has_topics_enabled <em>Optional</em>. <em>True</em>, if the bot has forum topic mode enabled in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $allows_users_to_create_topics <em>Optional</em>. <em>True</em>, if the bot allows users to create and delete topics in private chats. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $can_manage_bots <em>Optional</em>. <em>True</em>, if other bots can be created to be controlled by the bot. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     * @param bool|null $supports_join_request_queries <em>Optional</em>. <em>True</em>, if the bot supports join request queries and can be assigned to process them. Returned only in <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     */
    public static function make(int $id = null, bool $is_bot = null, string $first_name = null, string $last_name = null, string $username = null, string $language_code = null, bool $is_premium = null, bool $added_to_attachment_menu = null, bool $can_join_groups = null, bool $can_read_all_group_messages = null, bool $supports_guest_queries = null, bool $supports_inline_queries = null, bool $can_connect_to_business = null, bool $has_main_web_app = null, bool $has_topics_enabled = null, bool $allows_users_to_create_topics = null, bool $can_manage_bots = null, bool $supports_join_request_queries = null): self
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