<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents one button of an inline keyboard. Exactly one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button.
 * @method self text(string $value) Label text on the button
 * @method self icon_custom_emoji_id(string $value) <em>Optional</em>. Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on <a href="https://fragment.com">Fragment</a> or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
 * @method self style(string $value) <em>Optional</em>. Style of the button. Must be one of “danger” (red), “success” (green) or “primary” (blue). If omitted, then an app-specific style is used.
 * @method self url(string $value) <em>Optional</em>. HTTP or tg:// URL to be opened when the button is pressed. Links <code>tg://user?id=<user_id></code> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
 * @method self callback_data(string $value) <em>Optional</em>. Data to be sent in a <a href="https://core.telegram.org/bots/api#callbackquery">callback query</a> to the bot when the button is pressed, 1-64 bytes
 * @method self web_app(WebAppInfo $value) <em>Optional</em>. Description of the <a href="/bots/webapps">Web App</a> that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method <a href="https://core.telegram.org/bots/api#answerwebappquery">answerWebAppQuery</a>. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a business account and ephemeral messages.
 * @method self login_url(LoginUrl $value) <em>Optional</em>. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the <a href="/widgets/login">Telegram Login Widget</a>. Not supported for ephemeral messages.
 * @method self switch_inline_query(string $value) <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
 * @method self switch_inline_query_current_chat(string $value) <em>Optional</em>. If set, pressing the button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted.<br><br>This offers a quick way for the user to open your bot in inline mode in the same chat - good for selecting something from multiple options. Not supported in channels and for messages sent in channel direct messages chats and on behalf of a business account.
 * @method self switch_inline_query_chosen_chat(SwitchInlineQueryChosenChat $value) <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
 * @method self copy_text(CopyTextButton $value) <em>Optional</em>. Description of the button that copies the specified text to the clipboard
 * @method self callback_game(CallbackGame $value) <em>Optional</em>. Description of the game that will be launched when the user presses the button.<br><br><strong>NOTE:</strong> This type of button <strong>must</strong> always be the first button in the first row.
 * @method self pay(bool $value) <em>Optional</em>. Specify <em>True</em>, to send a <a href="https://core.telegram.org/bots/api#payments">Pay button</a>. Substrings “<img class="emoji" src="//telegram.org/img/emoji/40/E2AD90.png" width="20" height="20" alt="⭐" />” and “XTR” in the buttons's text will be replaced with a Telegram Star icon.<br><br><strong>NOTE:</strong> This type of button <strong>must</strong> always be the first button in the first row and can only be used in invoice messages.
 * @method self disabled(DisabledButton $value) <em>Optional</em>. If set, then the button is disabled and does nothing
 */
class InlineKeyboardButton
{
    /** @var string */
    public string $text;
    /** @var string */
    public string $icon_custom_emoji_id;
    /** @var string */
    public string $style;
    /** @var string */
    public string $url;
    /** @var string */
    public string $callback_data;
    /** @var WebAppInfo */
    public WebAppInfo $web_app;
    /** @var LoginUrl */
    public LoginUrl $login_url;
    /** @var string */
    public string $switch_inline_query;
    /** @var string */
    public string $switch_inline_query_current_chat;
    /** @var SwitchInlineQueryChosenChat */
    public SwitchInlineQueryChosenChat $switch_inline_query_chosen_chat;
    /** @var CopyTextButton */
    public CopyTextButton $copy_text;
    /** @var CallbackGame */
    public CallbackGame $callback_game;
    /** @var bool */
    public bool $pay;
    /** @var DisabledButton */
    public DisabledButton $disabled;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents one button of an inline keyboard. Exactly one of the fields other than <em>text</em>, <em>icon_custom_emoji_id</em>, and <em>style</em> must be used to specify the type of the button.
     * @param string|null $text Label text on the button
     * @param string|null $icon_custom_emoji_id <em>Optional</em>. Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on <a href="https://fragment.com">Fragment</a> or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     * @param string|null $style <em>Optional</em>. Style of the button. Must be one of “danger” (red), “success” (green) or “primary” (blue). If omitted, then an app-specific style is used.
     * @param string|null $url <em>Optional</em>. HTTP or tg:// URL to be opened when the button is pressed. Links <code>tg://user?id=<user_id></code> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
     * @param string|null $callback_data <em>Optional</em>. Data to be sent in a <a href="https://core.telegram.org/bots/api#callbackquery">callback query</a> to the bot when the button is pressed, 1-64 bytes
     * @param WebAppInfo|null $web_app <em>Optional</em>. Description of the <a href="/bots/webapps">Web App</a> that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method <a href="https://core.telegram.org/bots/api#answerwebappquery">answerWebAppQuery</a>. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a business account and ephemeral messages.
     * @param LoginUrl|null $login_url <em>Optional</em>. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the <a href="/widgets/login">Telegram Login Widget</a>. Not supported for ephemeral messages.
     * @param string|null $switch_inline_query <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
     * @param string|null $switch_inline_query_current_chat <em>Optional</em>. If set, pressing the button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted.<br><br>This offers a quick way for the user to open your bot in inline mode in the same chat - good for selecting something from multiple options. Not supported in channels and for messages sent in channel direct messages chats and on behalf of a business account.
     * @param SwitchInlineQueryChosenChat|null $switch_inline_query_chosen_chat <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
     * @param CopyTextButton|null $copy_text <em>Optional</em>. Description of the button that copies the specified text to the clipboard
     * @param CallbackGame|null $callback_game <em>Optional</em>. Description of the game that will be launched when the user presses the button.<br><br><strong>NOTE:</strong> This type of button <strong>must</strong> always be the first button in the first row.
     * @param bool|null $pay <em>Optional</em>. Specify <em>True</em>, to send a <a href="https://core.telegram.org/bots/api#payments">Pay button</a>. Substrings “<img class="emoji" src="//telegram.org/img/emoji/40/E2AD90.png" width="20" height="20" alt="⭐" />” and “XTR” in the buttons's text will be replaced with a Telegram Star icon.<br><br><strong>NOTE:</strong> This type of button <strong>must</strong> always be the first button in the first row and can only be used in invoice messages.
     * @param DisabledButton|null $disabled <em>Optional</em>. If set, then the button is disabled and does nothing
     */
    public static function make(string $text = null, string $icon_custom_emoji_id = null, string $style = null, string $url = null, string $callback_data = null, WebAppInfo $web_app = null, LoginUrl $login_url = null, string $switch_inline_query = null, string $switch_inline_query_current_chat = null, SwitchInlineQueryChosenChat $switch_inline_query_chosen_chat = null, CopyTextButton $copy_text = null, CallbackGame $callback_game = null, bool $pay = null, DisabledButton $disabled = null): self
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