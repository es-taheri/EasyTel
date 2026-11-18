<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the scope to which bot commands are applied. Currently, the following 7 scopes are supported:
 * @method self botcommandscopedefault(BotCommandScopeDefault $value)
 * @method self botcommandscopeallprivatechats(BotCommandScopeAllPrivateChats $value)
 * @method self botcommandscopeallgroupchats(BotCommandScopeAllGroupChats $value)
 * @method self botcommandscopeallchatadministrators(BotCommandScopeAllChatAdministrators $value)
 * @method self botcommandscopechat(BotCommandScopeChat $value)
 * @method self botcommandscopechatadministrators(BotCommandScopeChatAdministrators $value)
 * @method self botcommandscopechatmember(BotCommandScopeChatMember $value)
 */
class BotCommandScope
{
    protected const _UNION_BASE = true;
    /** @var BotCommandScopeDefault */
    public BotCommandScopeDefault $botcommandscopedefault;
    /** @var BotCommandScopeAllPrivateChats */
    public BotCommandScopeAllPrivateChats $botcommandscopeallprivatechats;
    /** @var BotCommandScopeAllGroupChats */
    public BotCommandScopeAllGroupChats $botcommandscopeallgroupchats;
    /** @var BotCommandScopeAllChatAdministrators */
    public BotCommandScopeAllChatAdministrators $botcommandscopeallchatadministrators;
    /** @var BotCommandScopeChat */
    public BotCommandScopeChat $botcommandscopechat;
    /** @var BotCommandScopeChatAdministrators */
    public BotCommandScopeChatAdministrators $botcommandscopechatadministrators;
    /** @var BotCommandScopeChatMember */
    public BotCommandScopeChatMember $botcommandscopechatmember;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents the scope to which bot commands are applied. Currently, the following 7 scopes are supported:
     * @param BotCommandScopeDefault|null $botcommandscopedefault
     * @param BotCommandScopeAllPrivateChats|null $botcommandscopeallprivatechats
     * @param BotCommandScopeAllGroupChats|null $botcommandscopeallgroupchats
     * @param BotCommandScopeAllChatAdministrators|null $botcommandscopeallchatadministrators
     * @param BotCommandScopeChat|null $botcommandscopechat
     * @param BotCommandScopeChatAdministrators|null $botcommandscopechatadministrators
     * @param BotCommandScopeChatMember|null $botcommandscopechatmember
     */
    public static function make(BotCommandScopeDefault $botcommandscopedefault = null, BotCommandScopeAllPrivateChats $botcommandscopeallprivatechats = null, BotCommandScopeAllGroupChats $botcommandscopeallgroupchats = null, BotCommandScopeAllChatAdministrators $botcommandscopeallchatadministrators = null, BotCommandScopeChat $botcommandscopechat = null, BotCommandScopeChatAdministrators $botcommandscopechatadministrators = null, BotCommandScopeChatMember $botcommandscopechatmember = null): self
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