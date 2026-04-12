<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
 * @method self traveler(User $value) User that triggered the alert
 * @method self watcher(User $value) User that set the alert
 * @method self distance(int $value) The distance between the users
 */
class ProximityAlertTriggered
{
    public User $traveler;
    public User $watcher;
    public int $distance;

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
        if (isset($update['traveler'])) $this->traveler = new User($update['traveler']);
        if (isset($update['watcher'])) $this->watcher = new User($update['watcher']);
    }

    /**
     * This object represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
     * @param User|null $traveler User that triggered the alert
     * @param User|null $watcher User that set the alert
     * @param int|null $distance The distance between the users
     */
    public static function make(User $traveler = null, User $watcher = null, int $distance = null): self
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