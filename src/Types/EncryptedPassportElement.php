<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes documents or other Telegram Passport elements shared with the bot by the user.
 * @method self type(string $value) Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
 * @method self data(string $value) <em>Optional</em>. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and “address” types. Can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self phone_number(string $value) <em>Optional</em>. User's verified phone number; available only for “phone_number” type
 * @method self email(string $value) <em>Optional</em>. User's verified email address; available only for “email” type
 * @method self files(PassportFile[] $value) <em>Optional</em>. Array of encrypted files with documents provided by the user; available only for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self front_side(PassportFile $value) <em>Optional</em>. Encrypted file with the front side of the document, provided by the user; available only for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self reverse_side(PassportFile $value) <em>Optional</em>. Encrypted file with the reverse side of the document, provided by the user; available only for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self selfie(PassportFile $value) <em>Optional</em>. Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self translation(PassportFile[] $value) <em>Optional</em>. Array of encrypted files with translated versions of documents provided by the user; available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
 * @method self hash(string $value) Base64-encoded element hash for using in <a href="https://core.telegram.org/bots/api#passportelementerrorunspecified">PassportElementErrorUnspecified</a>
 */
class EncryptedPassportElement
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $data;
    /** @var string */
    public string $phone_number;
    /** @var string */
    public string $email;
    /** @var PassportFile[] */
    public array $files;
    /** @var PassportFile */
    public PassportFile $front_side;
    /** @var PassportFile */
    public PassportFile $reverse_side;
    /** @var PassportFile */
    public PassportFile $selfie;
    /** @var PassportFile[] */
    public array $translation;
    /** @var string */
    public string $hash;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes documents or other Telegram Passport elements shared with the bot by the user.
     * @param string|null $type Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
     * @param string|null $data <em>Optional</em>. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and “address” types. Can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param string|null $phone_number <em>Optional</em>. User's verified phone number; available only for “phone_number” type
     * @param string|null $email <em>Optional</em>. User's verified email address; available only for “email” type
     * @param PassportFile[]|null $files <em>Optional</em>. Array of encrypted files with documents provided by the user; available only for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile|null $front_side <em>Optional</em>. Encrypted file with the front side of the document, provided by the user; available only for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile|null $reverse_side <em>Optional</em>. Encrypted file with the reverse side of the document, provided by the user; available only for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile|null $selfie <em>Optional</em>. Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param PassportFile[]|null $translation <em>Optional</em>. Array of encrypted files with translated versions of documents provided by the user; available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying <a href="https://core.telegram.org/bots/api#encryptedcredentials">EncryptedCredentials</a>.
     * @param string|null $hash Base64-encoded element hash for using in <a href="https://core.telegram.org/bots/api#passportelementerrorunspecified">PassportElementErrorUnspecified</a>
     */
    public static function make(string $type = null, string $data = null, string $phone_number = null, string $email = null, array $files = null, PassportFile $front_side = null, PassportFile $reverse_side = null, PassportFile $selfie = null, array $translation = null, string $hash = null): self
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