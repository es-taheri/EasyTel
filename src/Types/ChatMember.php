<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:
 * @method self chatmemberowner(ChatMemberOwner $value)
 * @method self chatmemberadministrator(ChatMemberAdministrator $value)
 * @method self chatmembermember(ChatMemberMember $value)
 * @method self chatmemberrestricted(ChatMemberRestricted $value)
 * @method self chatmemberleft(ChatMemberLeft $value)
 * @method self chatmemberbanned(ChatMemberBanned $value)
 */
class ChatMember
{
    protected const _UNION_BASE = true;
    /** @var ChatMemberOwner */
    public ChatMemberOwner $chatmemberowner;
    /** @var ChatMemberAdministrator */
    public ChatMemberAdministrator $chatmemberadministrator;
    /** @var ChatMemberMember */
    public ChatMemberMember $chatmembermember;
    /** @var ChatMemberRestricted */
    public ChatMemberRestricted $chatmemberrestricted;
    /** @var ChatMemberLeft */
    public ChatMemberLeft $chatmemberleft;
    /** @var ChatMemberBanned */
    public ChatMemberBanned $chatmemberbanned;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:
     * @param ChatMemberOwner|null $chatmemberowner
     * @param ChatMemberAdministrator|null $chatmemberadministrator
     * @param ChatMemberMember|null $chatmembermember
     * @param ChatMemberRestricted|null $chatmemberrestricted
     * @param ChatMemberLeft|null $chatmemberleft
     * @param ChatMemberBanned|null $chatmemberbanned
     */
    public static function make(ChatMemberOwner $chatmemberowner = null, ChatMemberAdministrator $chatmemberadministrator = null, ChatMemberMember $chatmembermember = null, ChatMemberRestricted $chatmemberrestricted = null, ChatMemberLeft $chatmemberleft = null, ChatMemberBanned $chatmemberbanned = null): self
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