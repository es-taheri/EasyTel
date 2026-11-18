<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an error in the Telegram Passport element which was submitted that should be resolved by the user. It should be one of:
 * @method self passportelementerrordatafield(PassportElementErrorDataField $value)
 * @method self passportelementerrorfrontside(PassportElementErrorFrontSide $value)
 * @method self passportelementerrorreverseside(PassportElementErrorReverseSide $value)
 * @method self passportelementerrorselfie(PassportElementErrorSelfie $value)
 * @method self passportelementerrorfile(PassportElementErrorFile $value)
 * @method self passportelementerrorfiles(PassportElementErrorFiles $value)
 * @method self passportelementerrortranslationfile(PassportElementErrorTranslationFile $value)
 * @method self passportelementerrortranslationfiles(PassportElementErrorTranslationFiles $value)
 * @method self passportelementerrorunspecified(PassportElementErrorUnspecified $value)
 */
class PassportElementError
{
    protected const _UNION_BASE = true;
    /** @var PassportElementErrorDataField */
    public PassportElementErrorDataField $passportelementerrordatafield;
    /** @var PassportElementErrorFrontSide */
    public PassportElementErrorFrontSide $passportelementerrorfrontside;
    /** @var PassportElementErrorReverseSide */
    public PassportElementErrorReverseSide $passportelementerrorreverseside;
    /** @var PassportElementErrorSelfie */
    public PassportElementErrorSelfie $passportelementerrorselfie;
    /** @var PassportElementErrorFile */
    public PassportElementErrorFile $passportelementerrorfile;
    /** @var PassportElementErrorFiles */
    public PassportElementErrorFiles $passportelementerrorfiles;
    /** @var PassportElementErrorTranslationFile */
    public PassportElementErrorTranslationFile $passportelementerrortranslationfile;
    /** @var PassportElementErrorTranslationFiles */
    public PassportElementErrorTranslationFiles $passportelementerrortranslationfiles;
    /** @var PassportElementErrorUnspecified */
    public PassportElementErrorUnspecified $passportelementerrorunspecified;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents an error in the Telegram Passport element which was submitted that should be resolved by the user. It should be one of:
     * @param PassportElementErrorDataField|null $passportelementerrordatafield
     * @param PassportElementErrorFrontSide|null $passportelementerrorfrontside
     * @param PassportElementErrorReverseSide|null $passportelementerrorreverseside
     * @param PassportElementErrorSelfie|null $passportelementerrorselfie
     * @param PassportElementErrorFile|null $passportelementerrorfile
     * @param PassportElementErrorFiles|null $passportelementerrorfiles
     * @param PassportElementErrorTranslationFile|null $passportelementerrortranslationfile
     * @param PassportElementErrorTranslationFiles|null $passportelementerrortranslationfiles
     * @param PassportElementErrorUnspecified|null $passportelementerrorunspecified
     */
    public static function make(PassportElementErrorDataField $passportelementerrordatafield = null, PassportElementErrorFrontSide $passportelementerrorfrontside = null, PassportElementErrorReverseSide $passportelementerrorreverseside = null, PassportElementErrorSelfie $passportelementerrorselfie = null, PassportElementErrorFile $passportelementerrorfile = null, PassportElementErrorFiles $passportelementerrorfiles = null, PassportElementErrorTranslationFile $passportelementerrortranslationfile = null, PassportElementErrorTranslationFiles $passportelementerrortranslationfiles = null, PassportElementErrorUnspecified $passportelementerrorunspecified = null): self
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