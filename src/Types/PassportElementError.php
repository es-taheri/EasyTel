<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents an error in the Telegram Passport element which was submitted that should be resolved by the user. It should be one of:

 */
class PassportElementError
{
    public PassportElementErrorDataField $passportelementerrordatafield;
    public PassportElementErrorFrontSide $passportelementerrorfrontside;
    public PassportElementErrorReverseSide $passportelementerrorreverseside;
    public PassportElementErrorSelfie $passportelementerrorselfie;
    public PassportElementErrorFile $passportelementerrorfile;
    public PassportElementErrorFiles $passportelementerrorfiles;
    public PassportElementErrorTranslationFile $passportelementerrortranslationfile;
    public PassportElementErrorTranslationFiles $passportelementerrortranslationfiles;
    public PassportElementErrorUnspecified $passportelementerrorunspecified;

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
        $this->passportelementerrordatafield = new PassportElementErrorDataField($update);
        $this->passportelementerrorfrontside = new PassportElementErrorFrontSide($update);
        $this->passportelementerrorreverseside = new PassportElementErrorReverseSide($update);
        $this->passportelementerrorselfie = new PassportElementErrorSelfie($update);
        $this->passportelementerrorfile = new PassportElementErrorFile($update);
        $this->passportelementerrorfiles = new PassportElementErrorFiles($update);
        $this->passportelementerrortranslationfile = new PassportElementErrorTranslationFile($update);
        $this->passportelementerrortranslationfiles = new PassportElementErrorTranslationFiles($update);
        $this->passportelementerrorunspecified = new PassportElementErrorUnspecified($update);
    }

    
    public static function make(PassportElementErrorDataField $passportelementerrordatafield=null, PassportElementErrorFrontSide $passportelementerrorfrontside=null, PassportElementErrorReverseSide $passportelementerrorreverseside=null, PassportElementErrorSelfie $passportelementerrorselfie=null, PassportElementErrorFile $passportelementerrorfile=null, PassportElementErrorFiles $passportelementerrorfiles=null, PassportElementErrorTranslationFile $passportelementerrortranslationfile=null, PassportElementErrorTranslationFiles $passportelementerrortranslationfiles=null, PassportElementErrorUnspecified $passportelementerrorunspecified=null): self
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