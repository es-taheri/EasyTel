<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes Telegram Passport data shared with the bot by the user.
 * @method self data(array $value) Array with information about documents and other Telegram Passport elements that was shared with the bot
 * @method self credentials(EncryptedCredentials $value) Encrypted credentials required to decrypt the data
 */
class PassportData
{
    public array $data;
    public EncryptedCredentials $credentials;

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
        if (isset($update['credentials'])) $this->credentials = new EncryptedCredentials($update['credentials']);
    }

    /**
     * Describes Telegram Passport data shared with the bot by the user.
     * @param array|null $data Array with information about documents and other Telegram Passport elements that was shared with the bot
     * @param EncryptedCredentials|null $credentials Encrypted credentials required to decrypt the data
     */
    public static function make(array $data = null, EncryptedCredentials $credentials = null): self
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