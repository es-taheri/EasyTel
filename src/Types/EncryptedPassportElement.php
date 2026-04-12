<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes documents or other Telegram Passport elements shared with the bot by the user.
 * @method self type(string $value) Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
 * @method self data(string $value) <em>Optional</em>. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and “address” types. Can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self phone_number(string $value) <em>Optional</em>. User&#39;s verified phone number; available only for “phone_number” type
 * @method self email(string $value) <em>Optional</em>. User&#39;s verified email address; available only for “email” type
 * @method self files(array $value) <em>Optional</em>. Array of encrypted files with documents provided by the user; available only for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self front_side(PassportFile $value) <em>Optional</em>. Encrypted file with the front side of the document, provided by the user; available only for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self reverse_side(PassportFile $value) <em>Optional</em>. Encrypted file with the reverse side of the document, provided by the user; available only for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self selfie(PassportFile $value) <em>Optional</em>. Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self translation(array $value) <em>Optional</em>. Array of encrypted files with translated versions of documents provided by the user; available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self hash(string $value) Base64-encoded element hash for using in <a href="https://core.telegram.org/bots/api#passportelementerrorunspecified">PassportElementErrorUnspecified</a>
 */
class EncryptedPassportElement
{
    public string $type;
    public string $data;
    public string $phone_number;
    public string $email;
    public array $files;
    public PassportFile $front_side;
    public PassportFile $reverse_side;
    public PassportFile $selfie;
    public array $translation;
    public string $hash;

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
        if (isset($update['front_side'])) $this->front_side = new PassportFile($update['front_side']);
        if (isset($update['reverse_side'])) $this->reverse_side = new PassportFile($update['reverse_side']);
        if (isset($update['selfie'])) $this->selfie = new PassportFile($update['selfie']);
    }

    /**
     * Describes documents or other Telegram Passport elements shared with the bot by the user.
     * @param string|null $type Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
     * @param string|null $data <em>Optional</em>. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and “address” types. Can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param string|null $phone_number <em>Optional</em>. User&#39;s verified phone number; available only for “phone_number” type
     * @param string|null $email <em>Optional</em>. User&#39;s verified email address; available only for “email” type
     * @param array|null $files <em>Optional</em>. Array of encrypted files with documents provided by the user; available only for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile|null $front_side <em>Optional</em>. Encrypted file with the front side of the document, provided by the user; available only for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile|null $reverse_side <em>Optional</em>. Encrypted file with the reverse side of the document, provided by the user; available only for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile|null $selfie <em>Optional</em>. Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param array|null $translation <em>Optional</em>. Array of encrypted files with translated versions of documents provided by the user; available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param string|null $hash Base64-encoded element hash for using in <a href="https://core.telegram.org/bots/api#passportelementerrorunspecified">PassportElementErrorUnspecified</a>
     */
    public static function make(string $type = null, string $data = null, string $phone_number = null, string $email = null, array $files = null, PassportFile $front_side = null, PassportFile $reverse_side = null, PassportFile $selfie = null, array $translation = null, string $hash = null): self
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