<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The background is a .PNG or .TGV (gzipped subset of SVG with MIME type “application/x-tgwallpattern”) pattern to be combined with the background fill chosen by the user.
 * @method self type(string $value) Type of the background, always “pattern”
 * @method self document(Document $value) Document with the pattern
 * @method self fill(BackgroundFill $value) The background fill that is combined with the pattern
 * @method self intensity(int $value) Intensity of the pattern when it is shown above the filled background; 0-100
 * @method self is_inverted(True $value) <em>Optional</em>. <em>True</em>, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only
 * @method self is_moving(True $value) <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
 */
class BackgroundTypePattern
{
    public string $type;
    public Document $document;
    public BackgroundFill $fill;
    public int $intensity;
    public True $is_inverted;
    public True $is_moving;

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
        if (isset($update['document'])) $this->document = new Document($update['document']);
        if (isset($update['fill'])) $this->fill = new BackgroundFill($update['fill']);
    }

    /**
     * The background is a .PNG or .TGV (gzipped subset of SVG with MIME type “application/x-tgwallpattern”) pattern to be combined with the background fill chosen by the user.
     * @param string|null $type Type of the background, always “pattern”
     * @param Document|null $document Document with the pattern
     * @param BackgroundFill|null $fill The background fill that is combined with the pattern
     * @param int|null $intensity Intensity of the pattern when it is shown above the filled background; 0-100
     * @param True|null $is_inverted <em>Optional</em>. <em>True</em>, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only
     * @param True|null $is_moving <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
     */
    public static function make(string $type = null, Document $document = null, BackgroundFill $fill = null, int $intensity = null, True $is_inverted = null, True $is_moving = null): self
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