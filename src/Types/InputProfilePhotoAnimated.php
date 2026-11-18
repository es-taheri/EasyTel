<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * An animated profile photo in the MPEG4 format.
 * @method self type(string $value) Type of the profile photo, must be <em>animated</em>
 * @method self animation(string $value) The animated profile photo. Profile photos can't be reused and can only be uploaded as a new file, so you can pass “attach://<file_attach_name>” if the photo was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
 * @method self main_frame_timestamp(float $value) <em>Optional</em>. Timestamp in seconds of the frame that will be used as the static profile photo. Defaults to 0.0.
 */
class InputProfilePhotoAnimated
{
    /** @var string */
    public string $type;
    /** @var string */
    public string $animation;
    /** @var float */
    public float $main_frame_timestamp;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * An animated profile photo in the MPEG4 format.
     * @param string|null $type Type of the profile photo, must be <em>animated</em>
     * @param string|null $animation The animated profile photo. Profile photos can't be reused and can only be uploaded as a new file, so you can pass “attach://<file_attach_name>” if the photo was uploaded using multipart/form-data under <file_attach_name>. <a href="https://core.telegram.org/bots/api#sending-files">More information on Sending Files »</a>
     * @param float|null $main_frame_timestamp <em>Optional</em>. Timestamp in seconds of the frame that will be used as the static profile photo. Defaults to 0.0.
     */
    public static function make(string $type = null, string $animation = null, float $main_frame_timestamp = null): self
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