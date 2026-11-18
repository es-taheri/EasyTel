<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * A block with a video, corresponding to the HTML tag <code><video></code>.
 * @method self type(string $value) Type of the block, always “video”
 * @method self video(Video $value) The video
 * @method self has_spoiler(bool $value) <em>Optional</em>. <em>True</em>, if the media preview is covered by a spoiler animation
 * @method self caption(RichBlockCaption $value) <em>Optional</em>. Caption of the block
 */
class RichBlockVideo
{
    /** @var string */
    public string $type;
    /** @var Video */
    public Video $video;
    /** @var bool */
    public bool $has_spoiler;
    /** @var RichBlockCaption */
    public RichBlockCaption $caption;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * A block with a video, corresponding to the HTML tag <code><video></code>.
     * @param string|null $type Type of the block, always “video”
     * @param Video|null $video The video
     * @param bool|null $has_spoiler <em>Optional</em>. <em>True</em>, if the media preview is covered by a spoiler animation
     * @param RichBlockCaption|null $caption <em>Optional</em>. Caption of the block
     */
    public static function make(string $type = null, Video $video = null, bool $has_spoiler = null, RichBlockCaption $caption = null): self
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