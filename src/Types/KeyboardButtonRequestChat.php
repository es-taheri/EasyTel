<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object defines the criteria used to request a suitable chat. Information about the selected chat will be shared with the bot when the corresponding button is pressed. The bot will be granted requested rights in the chat if appropriate. <a href="/bots/features#chat-and-user-selection">More about requesting chats »</a>.
 * @method self request_id(int $value) Signed 32-bit identifier of the request, which will be received back in the <a href="https://core.telegram.org/bots/api#chatshared">ChatShared</a> object. Must be unique within the message
 * @method self chat_is_channel(bool $value) Pass <em>True</em> to request a channel chat, pass <em>False</em> to request a group or a supergroup chat.
 * @method self chat_is_forum(bool $value) <em>Optional</em>. Pass <em>True</em> to request a forum supergroup, pass <em>False</em> to request a non-forum chat. If not specified, no additional restrictions are applied.
 * @method self chat_has_username(bool $value) <em>Optional</em>. Pass <em>True</em> to request a supergroup or a channel with a username, pass <em>False</em> to request a chat without a username. If not specified, no additional restrictions are applied.
 * @method self chat_is_created(bool $value) <em>Optional</em>. Pass <em>True</em> to request a chat owned by the user. Otherwise, no additional restrictions are applied.
 * @method self user_administrator_rights(ChatAdministratorRights $value) <em>Optional</em>. A JSON-serialized object listing the required administrator rights of the user in the chat. The rights must be a superset of <em>bot_administrator_rights</em>. If not specified, no additional restrictions are applied.
 * @method self bot_administrator_rights(ChatAdministratorRights $value) <em>Optional</em>. A JSON-serialized object listing the required administrator rights of the bot in the chat. The rights must be a subset of <em>user_administrator_rights</em>. If not specified, no additional restrictions are applied.
 * @method self bot_is_member(bool $value) <em>Optional</em>. Pass <em>True</em> to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
 * @method self request_title(bool $value) <em>Optional</em>. Pass <em>True</em> to request the chat&#39;s title
 * @method self request_username(bool $value) <em>Optional</em>. Pass <em>True</em> to request the chat&#39;s username
 * @method self request_photo(bool $value) <em>Optional</em>. Pass <em>True</em> to request the chat&#39;s photo
 */
class KeyboardButtonRequestChat
{
    public int $request_id;
    public bool $chat_is_channel;
    public bool $chat_is_forum;
    public bool $chat_has_username;
    public bool $chat_is_created;
    public ChatAdministratorRights $user_administrator_rights;
    public ChatAdministratorRights $bot_administrator_rights;
    public bool $bot_is_member;
    public bool $request_title;
    public bool $request_username;
    public bool $request_photo;

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
        if (isset($update['user_administrator_rights'])) $this->user_administrator_rights = new ChatAdministratorRights($update['user_administrator_rights']);
        if (isset($update['bot_administrator_rights'])) $this->bot_administrator_rights = new ChatAdministratorRights($update['bot_administrator_rights']);
    }

    /**
     * This object defines the criteria used to request a suitable chat. Information about the selected chat will be shared with the bot when the corresponding button is pressed. The bot will be granted requested rights in the chat if appropriate. <a href="/bots/features#chat-and-user-selection">More about requesting chats »</a>.
     * @param int|null $request_id Signed 32-bit identifier of the request, which will be received back in the <a href="https://core.telegram.org/bots/api#chatshared">ChatShared</a> object. Must be unique within the message
     * @param bool|null $chat_is_channel Pass <em>True</em> to request a channel chat, pass <em>False</em> to request a group or a supergroup chat.
     * @param bool|null $chat_is_forum <em>Optional</em>. Pass <em>True</em> to request a forum supergroup, pass <em>False</em> to request a non-forum chat. If not specified, no additional restrictions are applied.
     * @param bool|null $chat_has_username <em>Optional</em>. Pass <em>True</em> to request a supergroup or a channel with a username, pass <em>False</em> to request a chat without a username. If not specified, no additional restrictions are applied.
     * @param bool|null $chat_is_created <em>Optional</em>. Pass <em>True</em> to request a chat owned by the user. Otherwise, no additional restrictions are applied.
     * @param ChatAdministratorRights|null $user_administrator_rights <em>Optional</em>. A JSON-serialized object listing the required administrator rights of the user in the chat. The rights must be a superset of <em>bot_administrator_rights</em>. If not specified, no additional restrictions are applied.
     * @param ChatAdministratorRights|null $bot_administrator_rights <em>Optional</em>. A JSON-serialized object listing the required administrator rights of the bot in the chat. The rights must be a subset of <em>user_administrator_rights</em>. If not specified, no additional restrictions are applied.
     * @param bool|null $bot_is_member <em>Optional</em>. Pass <em>True</em> to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
     * @param bool|null $request_title <em>Optional</em>. Pass <em>True</em> to request the chat&#39;s title
     * @param bool|null $request_username <em>Optional</em>. Pass <em>True</em> to request the chat&#39;s username
     * @param bool|null $request_photo <em>Optional</em>. Pass <em>True</em> to request the chat&#39;s photo
     */
    public static function make(int $request_id = null, bool $chat_is_channel = null, bool $chat_is_forum = null, bool $chat_has_username = null, bool $chat_is_created = null, ChatAdministratorRights $user_administrator_rights = null, ChatAdministratorRights $bot_administrator_rights = null, bool $bot_is_member = null, bool $request_title = null, bool $request_username = null, bool $request_photo = null): self
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