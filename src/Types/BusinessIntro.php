<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Contains information about the start page settings of a Telegram Business account.
 * @method self title(string $value) <em>Optional</em>. Title text of the business intro
 * @method self message(string $value) <em>Optional</em>. Message text of the business intro
 * @method self sticker(Sticker $value) <em>Optional</em>. Sticker of the business intro
 */
class BusinessIntro
{
    public string $title;
    public string $message;
    public Sticker $sticker;

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
        if (isset($update['sticker'])) $this->sticker = new Sticker($update['sticker']);
    }

    /**
     * Contains information about the start page settings of a Telegram Business account.
     * @param string|null $title <em>Optional</em>. Title text of the business intro
     * @param string|null $message <em>Optional</em>. Message text of the business intro
     * @param Sticker|null $sticker <em>Optional</em>. Sticker of the business intro
     */
    public static function make(string $title = null, string $message = null, Sticker $sticker = null): self
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