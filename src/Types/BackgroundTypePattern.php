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
 * @method self is_inverted(bool $value) <em>Optional</em>. <em>True</em>, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only.
 * @method self is_moving(bool $value) <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
 */
class BackgroundTypePattern
{
    /** @var string */
    public string $type;
    /** @var Document */
    public Document $document;
    /** @var BackgroundFill */
    public BackgroundFill $fill;
    /** @var int */
    public int $intensity;
    /** @var bool */
    public bool $is_inverted;
    /** @var bool */
    public bool $is_moving;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The background is a .PNG or .TGV (gzipped subset of SVG with MIME type “application/x-tgwallpattern”) pattern to be combined with the background fill chosen by the user.
     * @param string|null $type Type of the background, always “pattern”
     * @param Document|null $document Document with the pattern
     * @param BackgroundFill|null $fill The background fill that is combined with the pattern
     * @param int|null $intensity Intensity of the pattern when it is shown above the filled background; 0-100
     * @param bool|null $is_inverted <em>Optional</em>. <em>True</em>, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only.
     * @param bool|null $is_moving <em>Optional</em>. <em>True</em>, if the background moves slightly when the device is tilted
     */
    public static function make(string $type = null, Document $document = null, BackgroundFill $fill = null, int $intensity = null, bool $is_inverted = null, bool $is_moving = null): self
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