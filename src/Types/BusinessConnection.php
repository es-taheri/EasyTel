<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the connection of the bot with a business account.
 * @method self id(string $value) Unique identifier of the business connection
 * @method self user(User $value) Business account user that created the business connection
 * @method self user_chat_id(int $value) Identifier of a private chat with the user who created the business connection. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method self date(int $value) Date the connection was established in Unix time
 * @method self rights(BusinessBotRights $value) <em>Optional</em>. Rights of the business bot
 * @method self is_enabled(bool $value) <em>True</em>, if the connection is active
 */
class BusinessConnection
{
    public string $id;
    public User $user;
    public int $user_chat_id;
    public int $date;
    public BusinessBotRights $rights;
    public bool $is_enabled;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
        if (isset($update['rights'])) $this->rights = new BusinessBotRights($update['rights']);
    }

    /**
     * Describes the connection of the bot with a business account.
     * @param string|null $id Unique identifier of the business connection
     * @param User|null $user Business account user that created the business connection
     * @param int|null $user_chat_id Identifier of a private chat with the user who created the business connection. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     * @param int|null $date Date the connection was established in Unix time
     * @param BusinessBotRights|null $rights <em>Optional</em>. Rights of the business bot
     * @param bool|null $is_enabled <em>True</em>, if the connection is active
     */
    public static function make(string $id = null, User $user = null, int $user_chat_id = null, int $date = null, BusinessBotRights $rights = null, bool $is_enabled = null): self
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