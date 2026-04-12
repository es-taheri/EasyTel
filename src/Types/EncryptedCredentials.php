<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes data required for decrypting and authenticating <a href="https://core.telegram.org/bots/api#encryptedpassportelement">EncryptedPassportElement</a>. See the <a href="/passport#receiving-information">Telegram Passport Documentation</a> for a complete description of the data decryption and authentication processes.
 * @method self data(string $value) Base64-encoded encrypted JSON-serialized data with unique user&#39;s payload, data hashes and secrets required for <a href="https://core.telegram.org/bots/api#encryptedpassportelement">EncryptedPassportElement</a> decryption and authentication
 * @method self hash(string $value) Base64-encoded data hash for data authentication
 * @method self secret(string $value) Base64-encoded secret, encrypted with the bot&#39;s public RSA key, required for data decryption
 */
class EncryptedCredentials
{
    public string $data;
    public string $hash;
    public string $secret;

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
     * Describes data required for decrypting and authenticating <a href="https://core.telegram.org/bots/api#encryptedpassportelement">EncryptedPassportElement</a>. See the <a href="/passport#receiving-information">Telegram Passport Documentation</a> for a complete description of the data decryption and authentication processes.
     * @param string|null $data Base64-encoded encrypted JSON-serialized data with unique user&#39;s payload, data hashes and secrets required for <a href="https://core.telegram.org/bots/api#encryptedpassportelement">EncryptedPassportElement</a> decryption and authentication
     * @param string|null $hash Base64-encoded data hash for data authentication
     * @param string|null $secret Base64-encoded secret, encrypted with the bot&#39;s public RSA key, required for data decryption
     */
    public static function make(string $data = null, string $hash = null, string $secret = null): self
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