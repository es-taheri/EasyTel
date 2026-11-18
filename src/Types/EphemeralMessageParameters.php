<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * 
 * @method self receiver_user_id(int $value) <em>Optional</em>. Identifier of the user who will receive the message; required unless the message is sent as an ephemeral reply to a callback query. If omitted, then the parameter <em>chat_id</em> of the method is ignored. If specified together with <em>callback_query_id</em>, then it must be the identifier of the user who pressed the button. Must be omitted for messages sent in chats where the bot isn't a member, unless the message is a reply to an ephemeral message. It is not guaranteed that the user will receive the message, especially if they are offline. See <a href="https://core.telegram.org/bots/api#ephemeral-messages-and-commands">here</a> for more details.
 * @method self callback_query_id(string $value) <em>Optional</em>. Identifier of the callback query which triggered the message, if any. If specified and <em>receiver_user_id</em> is omitted, then the message is sent as an ephemeral reply to the callback query and will be shown only to the user who pressed the button. Ephemeral replies can't be sent to callback queries from messages in channel direct messages chats. The bot must still answer the callback query using <a href="https://core.telegram.org/bots/api#answercallbackquery">answerCallbackQuery</a>.
 * @method self replace_callback_query_message(bool $value) <em>Optional</em>. Pass <em>True</em> if the ephemeral message must be shown in place of the original message. Must be <em>False</em> for callback queries from ephemeral messages, which must be edited using regular <em>editEphemeralMessage…</em> methods.
 */
class EphemeralMessageParameters
{
    /** @var int */
    public int $receiver_user_id;
    /** @var string */
    public string $callback_query_id;
    /** @var bool */
    public bool $replace_callback_query_message;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * 
     * @param int|null $receiver_user_id <em>Optional</em>. Identifier of the user who will receive the message; required unless the message is sent as an ephemeral reply to a callback query. If omitted, then the parameter <em>chat_id</em> of the method is ignored. If specified together with <em>callback_query_id</em>, then it must be the identifier of the user who pressed the button. Must be omitted for messages sent in chats where the bot isn't a member, unless the message is a reply to an ephemeral message. It is not guaranteed that the user will receive the message, especially if they are offline. See <a href="https://core.telegram.org/bots/api#ephemeral-messages-and-commands">here</a> for more details.
     * @param string|null $callback_query_id <em>Optional</em>. Identifier of the callback query which triggered the message, if any. If specified and <em>receiver_user_id</em> is omitted, then the message is sent as an ephemeral reply to the callback query and will be shown only to the user who pressed the button. Ephemeral replies can't be sent to callback queries from messages in channel direct messages chats. The bot must still answer the callback query using <a href="https://core.telegram.org/bots/api#answercallbackquery">answerCallbackQuery</a>.
     * @param bool|null $replace_callback_query_message <em>Optional</em>. Pass <em>True</em> if the ephemeral message must be shown in place of the original message. Must be <em>False</em> for callback queries from ephemeral messages, which must be edited using regular <em>editEphemeralMessage…</em> methods.
     */
    public static function make(int $receiver_user_id = null, string $callback_query_id = null, bool $replace_callback_query_message = null): self
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