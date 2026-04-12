<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The paid media isn&#39;t available before the payment.
 * @method self type(string $value) Type of the paid media, always “preview”
 * @method self width(int $value) <em>Optional</em>. Media width as defined by the sender
 * @method self height(int $value) <em>Optional</em>. Media height as defined by the sender
 * @method self duration(int $value) <em>Optional</em>. Duration of the media in seconds as defined by the sender
 */
class PaidMediaPreview
{
    public string $type;
    public int $width;
    public int $height;
    public int $duration;

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
     * The paid media isn&#39;t available before the payment.
     * @param string|null $type Type of the paid media, always “preview”
     * @param int|null $width <em>Optional</em>. Media width as defined by the sender
     * @param int|null $height <em>Optional</em>. Media height as defined by the sender
     * @param int|null $duration <em>Optional</em>. Duration of the media in seconds as defined by the sender
     */
    public static function make(string $type = null, int $width = null, int $height = null, int $duration = null): self
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