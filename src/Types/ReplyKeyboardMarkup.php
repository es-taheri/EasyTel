<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a <a href="/bots/features#keyboards">custom keyboard</a> with reply options (see <a href="/bots/features#keyboards">Introduction to bots</a> for details and examples). Not supported in channels and for messages sent on behalf of a business account.
 * @method self keyboard(KeyboardButton[][] $value) Array of button rows, each represented by an Array of <a href="https://core.telegram.org/bots/api#keyboardbutton">KeyboardButton</a> objects
 * @method self is_persistent(bool $value) <em>Optional</em>. Requests clients to always show the keyboard when the regular keyboard is hidden. Defaults to <em>False</em>, in which case the custom keyboard can be hidden and opened with a keyboard icon.
 * @method self resize_keyboard(bool $value) <em>Optional</em>. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to <em>False</em>, in which case the custom keyboard is always of the same height as the app's standard keyboard.
 * @method self one_time_keyboard(bool $value) <em>Optional</em>. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat - the user can press a special button in the input field to see the custom keyboard again. Defaults to <em>False</em>.
 * @method self input_field_placeholder(string $value) <em>Optional</em>. The placeholder to be shown in the input field when the keyboard is active; 1-64 characters
 * @method self selective(bool $value) <em>Optional</em>. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the <em>text</em> of the <a href="https://core.telegram.org/bots/api#message">Message</a> object; 2) if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.<br><br><em>Example:</em> A user requests to change the bot's language, bot replies to the request with a keyboard to select the new language. Other users in the group don't see the keyboard.
 * @method self force_reply(bool $value) <em>Optional</em>. Pass <em>True</em> if the reply interface must be shown to the user, as if they had manually selected the bot's message and tapped 'Reply'
 */
class ReplyKeyboardMarkup
{
    /** @var KeyboardButton[][] */
    public array $keyboard;
    /** @var bool */
    public bool $is_persistent;
    /** @var bool */
    public bool $resize_keyboard;
    /** @var bool */
    public bool $one_time_keyboard;
    /** @var string */
    public string $input_field_placeholder;
    /** @var bool */
    public bool $selective;
    /** @var bool */
    public bool $force_reply;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a <a href="/bots/features#keyboards">custom keyboard</a> with reply options (see <a href="/bots/features#keyboards">Introduction to bots</a> for details and examples). Not supported in channels and for messages sent on behalf of a business account.
     * @param KeyboardButton[][]|null $keyboard Array of button rows, each represented by an Array of <a href="https://core.telegram.org/bots/api#keyboardbutton">KeyboardButton</a> objects
     * @param bool|null $is_persistent <em>Optional</em>. Requests clients to always show the keyboard when the regular keyboard is hidden. Defaults to <em>False</em>, in which case the custom keyboard can be hidden and opened with a keyboard icon.
     * @param bool|null $resize_keyboard <em>Optional</em>. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to <em>False</em>, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     * @param bool|null $one_time_keyboard <em>Optional</em>. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat - the user can press a special button in the input field to see the custom keyboard again. Defaults to <em>False</em>.
     * @param string|null $input_field_placeholder <em>Optional</em>. The placeholder to be shown in the input field when the keyboard is active; 1-64 characters
     * @param bool|null $selective <em>Optional</em>. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the <em>text</em> of the <a href="https://core.telegram.org/bots/api#message">Message</a> object; 2) if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.<br><br><em>Example:</em> A user requests to change the bot's language, bot replies to the request with a keyboard to select the new language. Other users in the group don't see the keyboard.
     * @param bool|null $force_reply <em>Optional</em>. Pass <em>True</em> if the reply interface must be shown to the user, as if they had manually selected the bot's message and tapped 'Reply'
     */
    public static function make(array $keyboard = null, bool $is_persistent = null, bool $resize_keyboard = null, bool $one_time_keyboard = null, string $input_field_placeholder = null, bool $selective = null, bool $force_reply = null): self
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