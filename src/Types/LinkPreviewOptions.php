<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the options used for link preview generation.
 * @method self is_disabled(bool $value) <em>Optional</em>. <em>True</em>, if the link preview is disabled
 * @method self url(string $value) <em>Optional</em>. URL to use for the link preview. If empty, then the first URL found in the message text will be used.
 * @method self prefer_small_media(bool $value) <em>Optional</em>. <em>True</em>, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method self prefer_large_media(bool $value) <em>Optional</em>. <em>True</em>, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method self show_above_text(bool $value) <em>Optional</em>. <em>True</em>, if the link preview must be shown above the message text; otherwise, the link preview will be shown below the message text
 */
class LinkPreviewOptions
{
    /** @var bool */
    public bool $is_disabled;
    /** @var string */
    public string $url;
    /** @var bool */
    public bool $prefer_small_media;
    /** @var bool */
    public bool $prefer_large_media;
    /** @var bool */
    public bool $show_above_text;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes the options used for link preview generation.
     * @param bool|null $is_disabled <em>Optional</em>. <em>True</em>, if the link preview is disabled
     * @param string|null $url <em>Optional</em>. URL to use for the link preview. If empty, then the first URL found in the message text will be used.
     * @param bool|null $prefer_small_media <em>Optional</em>. <em>True</em>, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
     * @param bool|null $prefer_large_media <em>Optional</em>. <em>True</em>, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
     * @param bool|null $show_above_text <em>Optional</em>. <em>True</em>, if the link preview must be shown above the message text; otherwise, the link preview will be shown below the message text
     */
    public static function make(bool $is_disabled = null, string $url = null, bool $prefer_small_media = null, bool $prefer_large_media = null, bool $show_above_text = null): self
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