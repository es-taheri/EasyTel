<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a button in a <a href="https://core.telegram.org/bots/api#richmessage">RichMessage</a>. Exactly one of the fields other than <em>text</em> and <em>style</em> must be used to specify the type of the button.
 * @method self text(RichText|string|array $value) Text of the button. May contain only plain text, <a href="https://core.telegram.org/bots/api#richtextcustomemoji">RichTextCustomEmoji</a> and <a href="https://core.telegram.org/bots/api#richtextdatetime">RichTextDateTime</a> entities.
 * @method self style(string $value) <em>Optional</em>. Style of the button. Must be one of “danger”, “success”, “primary”, or “link” (the button is shown as a regular link without borders). Apps may use theme-specific colors for the button background and text based on the style. The style “link” is allowed only for callback buttons.
 * @method self url(string $value) <em>Optional</em>. HTTP or tg:// URL to be opened when the button is pressed. Links <code>tg://user?id=<user_id></code> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
 * @method self callback_data(string $value) <em>Optional</em>. Data to be sent in a <a href="https://core.telegram.org/bots/api#callbackquery">callback query</a> to the bot when the button is pressed, 1-64 bytes
 * @method self web_app(WebAppInfo $value) <em>Optional</em>. Description of the <a href="/bots/webapps">Web App</a> that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method <a href="https://core.telegram.org/bots/api#answerwebappquery">answerWebAppQuery</a>. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a business account.
 * @method self login_url(LoginUrl $value) <em>Optional</em>. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the <a href="/widgets/login">Telegram Login Widget</a>. Not supported for ephemeral messages.
 * @method self switch_inline_query(string $value) <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
 * @method self switch_inline_query_current_chat(string $value) <em>Optional</em>. If set, pressing the button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted. Not supported in channels and for messages sent in channel direct messages chats and on behalf of a business account.
 * @method self switch_inline_query_chosen_chat(SwitchInlineQueryChosenChat $value) <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
 * @method self copy_text(CopyTextButton $value) <em>Optional</em>. A button that copies the specified text to the clipboard
 * @method self disabled(DisabledButton $value) <em>Optional</em>. If set, then the button is disabled and does nothing
 */
class RichMessageButton
{
    /** @var RichText|string|array */
    public RichText|string|array $text;
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
    /** @var DisabledButton */
    public DisabledButton $disabled;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a button in a <a href="https://core.telegram.org/bots/api#richmessage">RichMessage</a>. Exactly one of the fields other than <em>text</em> and <em>style</em> must be used to specify the type of the button.
     * @param RichText|string|array|null $text Text of the button. May contain only plain text, <a href="https://core.telegram.org/bots/api#richtextcustomemoji">RichTextCustomEmoji</a> and <a href="https://core.telegram.org/bots/api#richtextdatetime">RichTextDateTime</a> entities.
     * @param string|null $style <em>Optional</em>. Style of the button. Must be one of “danger”, “success”, “primary”, or “link” (the button is shown as a regular link without borders). Apps may use theme-specific colors for the button background and text based on the style. The style “link” is allowed only for callback buttons.
     * @param string|null $url <em>Optional</em>. HTTP or tg:// URL to be opened when the button is pressed. Links <code>tg://user?id=<user_id></code> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
     * @param string|null $callback_data <em>Optional</em>. Data to be sent in a <a href="https://core.telegram.org/bots/api#callbackquery">callback query</a> to the bot when the button is pressed, 1-64 bytes
     * @param WebAppInfo|null $web_app <em>Optional</em>. Description of the <a href="/bots/webapps">Web App</a> that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method <a href="https://core.telegram.org/bots/api#answerwebappquery">answerWebAppQuery</a>. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a business account.
     * @param LoginUrl|null $login_url <em>Optional</em>. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the <a href="/widgets/login">Telegram Login Widget</a>. Not supported for ephemeral messages.
     * @param string|null $switch_inline_query <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
     * @param string|null $switch_inline_query_current_chat <em>Optional</em>. If set, pressing the button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted. Not supported in channels and for messages sent in channel direct messages chats and on behalf of a business account.
     * @param SwitchInlineQueryChosenChat|null $switch_inline_query_chosen_chat <em>Optional</em>. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
     * @param CopyTextButton|null $copy_text <em>Optional</em>. A button that copies the specified text to the clipboard
     * @param DisabledButton|null $disabled <em>Optional</em>. If set, then the button is disabled and does nothing
     */
    public static function make(RichText|string|array $text = null, string $style = null, string $url = null, string $callback_data = null, WebAppInfo $web_app = null, LoginUrl $login_url = null, string $switch_inline_query = null, string $switch_inline_query_current_chat = null, SwitchInlineQueryChosenChat $switch_inline_query_chosen_chat = null, CopyTextButton $copy_text = null, DisabledButton $disabled = null): self
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