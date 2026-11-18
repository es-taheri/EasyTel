<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the content of a poll description or a quiz explanation to be sent. It should be one of
 * @method self inputmediaanimation(InputMediaAnimation $value)
 * @method self inputmediaaudio(InputMediaAudio $value)
 * @method self inputmediadocument(InputMediaDocument $value)
 * @method self inputmedialivephoto(InputMediaLivePhoto $value)
 * @method self inputmedialocation(InputMediaLocation $value)
 * @method self inputmediaphoto(InputMediaPhoto $value)
 * @method self inputmediavenue(InputMediaVenue $value)
 * @method self inputmediavideo(InputMediaVideo $value)
 */
class InputPollMedia
{
    protected const _UNION_BASE = true;
    /** @var InputMediaAnimation */
    public InputMediaAnimation $inputmediaanimation;
    /** @var InputMediaAudio */
    public InputMediaAudio $inputmediaaudio;
    /** @var InputMediaDocument */
    public InputMediaDocument $inputmediadocument;
    /** @var InputMediaLivePhoto */
    public InputMediaLivePhoto $inputmedialivephoto;
    /** @var InputMediaLocation */
    public InputMediaLocation $inputmedialocation;
    /** @var InputMediaPhoto */
    public InputMediaPhoto $inputmediaphoto;
    /** @var InputMediaVenue */
    public InputMediaVenue $inputmediavenue;
    /** @var InputMediaVideo */
    public InputMediaVideo $inputmediavideo;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents the content of a poll description or a quiz explanation to be sent. It should be one of
     * @param InputMediaAnimation|null $inputmediaanimation
     * @param InputMediaAudio|null $inputmediaaudio
     * @param InputMediaDocument|null $inputmediadocument
     * @param InputMediaLivePhoto|null $inputmedialivephoto
     * @param InputMediaLocation|null $inputmedialocation
     * @param InputMediaPhoto|null $inputmediaphoto
     * @param InputMediaVenue|null $inputmediavenue
     * @param InputMediaVideo|null $inputmediavideo
     */
    public static function make(InputMediaAnimation $inputmediaanimation = null, InputMediaAudio $inputmediaaudio = null, InputMediaDocument $inputmediadocument = null, InputMediaLivePhoto $inputmedialivephoto = null, InputMediaLocation $inputmedialocation = null, InputMediaPhoto $inputmediaphoto = null, InputMediaVenue $inputmediavenue = null, InputMediaVideo $inputmediavideo = null): self
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