<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents a <a href="https://core.telegram.org/bots/api#inlinequeryresult">result</a> of an inline query that was chosen by the user and sent to their chat partner.
 * @method self result_id(string $value) The unique identifier for the result that was chosen
 * @method self from(User $value) The user that chose the result
 * @method self location(Location $value) <em>Optional</em>. Sender location, only for bots that require user location
 * @method self inline_message_id(string $value) <em>Optional</em>. Identifier of the sent inline message. Available only if there is an <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">inline keyboard</a> attached to the message. Will be also received in <a href="https://core.telegram.org/bots/api#callbackquery">callback queries</a> and can be used to <a href="https://core.telegram.org/bots/api#updating-messages">edit</a> the message.
 * @method self query(string $value) The query that was used to obtain the result
 */
class ChosenInlineResult
{
    public string $result_id;
    public User $from;
    public Location $location;
    public string $inline_message_id;
    public string $query;

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
        if (isset($update['from'])) $this->from = new User($update['from']);
        if (isset($update['location'])) $this->location = new Location($update['location']);
    }

    /**
     * Represents a <a href="https://core.telegram.org/bots/api#inlinequeryresult">result</a> of an inline query that was chosen by the user and sent to their chat partner.
     * @param string|null $result_id The unique identifier for the result that was chosen
     * @param User|null $from The user that chose the result
     * @param Location|null $location <em>Optional</em>. Sender location, only for bots that require user location
     * @param string|null $inline_message_id <em>Optional</em>. Identifier of the sent inline message. Available only if there is an <a href="https://core.telegram.org/bots/api#inlinekeyboardmarkup">inline keyboard</a> attached to the message. Will be also received in <a href="https://core.telegram.org/bots/api#callbackquery">callback queries</a> and can be used to <a href="https://core.telegram.org/bots/api#updating-messages">edit</a> the message.
     * @param string|null $query The query that was used to obtain the result
     */
    public static function make(string $result_id = null, User $from = null, Location $location = null, string $inline_message_id = null, string $query = null): self
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