<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents one button of the reply keyboard. At most one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button. For simple text buttons, <em>String</em> can be used instead of this object to specify the button text.
 * @method self text(string $value) Text of the button. If none of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> are used, it will be sent as a message when the button is pressed.
 * @method self icon_custom_emoji_id(string $value) <em>Optional</em>. Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on <a href="https://fragment.com">Fragment</a> or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
 * @method self style(string $value) <em>Optional</em>. Style of the button. Must be one of “danger” (red), “success” (green) or “primary” (blue). If omitted, then an app-specific style is used.
 * @method self request_users(KeyboardButtonRequestUsers $value) <em>Optional</em>. If specified, pressing the button will open a list of suitable users. Identifiers of selected users will be sent to the bot in a “users_shared” service message. Available in private chats only.
 * @method self request_chat(KeyboardButtonRequestChat $value) <em>Optional</em>. If specified, pressing the button will open a list of suitable chats. Tapping on a chat will send its identifier to the bot in a “chat_shared” service message. Available in private chats only.
 * @method self request_managed_bot(KeyboardButtonRequestManagedBot $value) <em>Optional</em>. If specified, pressing the button will ask the user to create and share a bot that will be managed by the current bot. Available for bots that enabled management of other bots in the <a href="https://t.me/BotFather">@BotFather</a> Mini App. Available in private chats only.
 * @method self request_contact(bool $value) <em>Optional</em>. If <em>True</em>, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only.
 * @method self request_location(bool $value) <em>Optional</em>. If <em>True</em>, the user's current location will be sent when the button is pressed. Available in private chats only.
 * @method self request_poll(KeyboardButtonPollType $value) <em>Optional</em>. If specified, the user will be asked to create a poll and send it to the bot when the button is pressed. Available in private chats only.
 * @method self web_app(WebAppInfo $value) <em>Optional</em>. If specified, the described <a href="/bots/webapps">Web App</a> will be launched when the button is pressed. The Web App will be able to send a “web_app_data” service message. Available in private chats only.
 */
class KeyboardButton
{
    /** @var string */
    public string $text;
    /** @var string */
    public string $icon_custom_emoji_id;
    /** @var string */
    public string $style;
    /** @var KeyboardButtonRequestUsers */
    public KeyboardButtonRequestUsers $request_users;
    /** @var KeyboardButtonRequestChat */
    public KeyboardButtonRequestChat $request_chat;
    /** @var KeyboardButtonRequestManagedBot */
    public KeyboardButtonRequestManagedBot $request_managed_bot;
    /** @var bool */
    public bool $request_contact;
    /** @var bool */
    public bool $request_location;
    /** @var KeyboardButtonPollType */
    public KeyboardButtonPollType $request_poll;
    /** @var WebAppInfo */
    public WebAppInfo $web_app;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents one button of the reply keyboard. At most one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button. For simple text buttons, <em>String</em> can be used instead of this object to specify the button text.
     * @param string|null $text Text of the button. If none of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> are used, it will be sent as a message when the button is pressed.
     * @param string|null $icon_custom_emoji_id <em>Optional</em>. Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on <a href="https://fragment.com">Fragment</a> or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     * @param string|null $style <em>Optional</em>. Style of the button. Must be one of “danger” (red), “success” (green) or “primary” (blue). If omitted, then an app-specific style is used.
     * @param KeyboardButtonRequestUsers|null $request_users <em>Optional</em>. If specified, pressing the button will open a list of suitable users. Identifiers of selected users will be sent to the bot in a “users_shared” service message. Available in private chats only.
     * @param KeyboardButtonRequestChat|null $request_chat <em>Optional</em>. If specified, pressing the button will open a list of suitable chats. Tapping on a chat will send its identifier to the bot in a “chat_shared” service message. Available in private chats only.
     * @param KeyboardButtonRequestManagedBot|null $request_managed_bot <em>Optional</em>. If specified, pressing the button will ask the user to create and share a bot that will be managed by the current bot. Available for bots that enabled management of other bots in the <a href="https://t.me/BotFather">@BotFather</a> Mini App. Available in private chats only.
     * @param bool|null $request_contact <em>Optional</em>. If <em>True</em>, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only.
     * @param bool|null $request_location <em>Optional</em>. If <em>True</em>, the user's current location will be sent when the button is pressed. Available in private chats only.
     * @param KeyboardButtonPollType|null $request_poll <em>Optional</em>. If specified, the user will be asked to create a poll and send it to the bot when the button is pressed. Available in private chats only.
     * @param WebAppInfo|null $web_app <em>Optional</em>. If specified, the described <a href="/bots/webapps">Web App</a> will be launched when the button is pressed. The Web App will be able to send a “web_app_data” service message. Available in private chats only.
     */
    public static function make(string $text = null, string $icon_custom_emoji_id = null, string $style = null, KeyboardButtonRequestUsers $request_users = null, KeyboardButtonRequestChat $request_chat = null, KeyboardButtonRequestManagedBot $request_managed_bot = null, bool $request_contact = null, bool $request_location = null, KeyboardButtonPollType $request_poll = null, WebAppInfo $web_app = null): self
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