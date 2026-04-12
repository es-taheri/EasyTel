<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the content of a media message to be sent. It should be one of

 */
class InputMedia
{
    public InputMediaAnimation $inputmediaanimation;
    public InputMediaDocument $inputmediadocument;
    public InputMediaAudio $inputmediaaudio;
    public InputMediaPhoto $inputmediaphoto;
    public InputMediaVideo $inputmediavideo;

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
        $this->inputmediaanimation = new InputMediaAnimation($update);
        $this->inputmediadocument = new InputMediaDocument($update);
        $this->inputmediaaudio = new InputMediaAudio($update);
        $this->inputmediaphoto = new InputMediaPhoto($update);
        $this->inputmediavideo = new InputMediaVideo($update);
    }

    
    public static function make(InputMediaAnimation $inputmediaanimation=null, InputMediaDocument $inputmediadocument=null, InputMediaAudio $inputmediaaudio=null, InputMediaPhoto $inputmediaphoto=null, InputMediaVideo $inputmediavideo=null): self
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