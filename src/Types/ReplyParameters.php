<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes reply parameters for the message that is being sent.
 * @method self message_id(int $value) Identifier of the message that will be replied to in the current chat, or in the chat <em>chat_id</em> if it is specified
 * @method self chat_id(int|string $value) <em>Optional</em>. If the message to be replied to is from a different chat, unique identifier for the chat or username of the channel (in the format <code>@channelusername</code>). Not supported for messages sent on behalf of a business account and messages from channel direct messages chats.
 * @method self allow_sending_without_reply(bool $value) <em>Optional</em>. Pass <em>True</em> if the message should be sent even if the specified message to be replied to is not found. Always <em>False</em> for replies in another chat or forum topic. Always <em>True</em> for messages sent on behalf of a business account.
 * @method self quote(string $value) <em>Optional</em>. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote must be an exact substring of the message to be replied to, including <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities. The message will fail to send if the quote isn&#39;t found in the original message.
 * @method self quote_parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the quote. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self quote_entities(array $value) <em>Optional</em>. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of <em>quote_parse_mode</em>.
 * @method self quote_position(int $value) <em>Optional</em>. Position of the quote in the original message in UTF-16 code units
 * @method self checklist_task_id(int $value) <em>Optional</em>. Identifier of the specific checklist task to be replied to
 * @method self poll_option_id(string $value) <em>Optional</em>. Persistent identifier of the specific poll option to be replied to
 */
class ReplyParameters
{
    public int $message_id;
    public int|string $chat_id;
    public bool $allow_sending_without_reply;
    public string $quote;
    public string $quote_parse_mode;
    public array $quote_entities;
    public int $quote_position;
    public int $checklist_task_id;
    public string $poll_option_id;

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
     * Describes reply parameters for the message that is being sent.
     * @param int|null $message_id Identifier of the message that will be replied to in the current chat, or in the chat <em>chat_id</em> if it is specified
     * @param int|string|null $chat_id <em>Optional</em>. If the message to be replied to is from a different chat, unique identifier for the chat or username of the channel (in the format <code>@channelusername</code>). Not supported for messages sent on behalf of a business account and messages from channel direct messages chats.
     * @param bool|null $allow_sending_without_reply <em>Optional</em>. Pass <em>True</em> if the message should be sent even if the specified message to be replied to is not found. Always <em>False</em> for replies in another chat or forum topic. Always <em>True</em> for messages sent on behalf of a business account.
     * @param string|null $quote <em>Optional</em>. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote must be an exact substring of the message to be replied to, including <em>bold</em>, <em>italic</em>, <em>underline</em>, <em>strikethrough</em>, <em>spoiler</em>, <em>custom_emoji</em>, and <em>date_time</em> entities. The message will fail to send if the quote isn&#39;t found in the original message.
     * @param string|null $quote_parse_mode <em>Optional</em>. Mode for parsing entities in the quote. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param array|null $quote_entities <em>Optional</em>. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of <em>quote_parse_mode</em>.
     * @param int|null $quote_position <em>Optional</em>. Position of the quote in the original message in UTF-16 code units
     * @param int|null $checklist_task_id <em>Optional</em>. Identifier of the specific checklist task to be replied to
     * @param string|null $poll_option_id <em>Optional</em>. Persistent identifier of the specific poll option to be replied to
     */
    public static function make(int $message_id = null, int|string $chat_id = null, bool $allow_sending_without_reply = null, string $quote = null, string $quote_parse_mode = null, array $quote_entities = null, int $quote_position = null, int $checklist_task_id = null, string $poll_option_id = null): self
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