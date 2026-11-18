<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents an issue with a document scan. The error is considered resolved when the file with the document scan changes.
 * @method self source(string $value) Error source, must be <em>file</em>
 * @method self type(string $value) The section of the user's Telegram Passport which has the issue, one of “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”
 * @method self file_hash(string $value) Base64-encoded file hash
 * @method self message(string $value) Error message
 */
class PassportElementErrorFile
{
    /** @var string */
    public string $source;
    /** @var string */
    public string $type;
    /** @var string */
    public string $file_hash;
    /** @var string */
    public string $message;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents an issue with a document scan. The error is considered resolved when the file with the document scan changes.
     * @param string|null $source Error source, must be <em>file</em>
     * @param string|null $type The section of the user's Telegram Passport which has the issue, one of “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”
     * @param string|null $file_hash Base64-encoded file hash
     * @param string|null $message Error message
     */
    public static function make(string $source = null, string $type = null, string $file_hash = null, string $message = null): self
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