<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object describes an <a href="/bots/widgets">HTML widget</a>.
 * @method self id(string $value) <em>Optional</em>. Unique identifier of the shared widget, which can be used to send the widget again using the method <a href="https://core.telegram.org/bots/api#sendsharedwidget">sendSharedWidget</a>; omitted if the widget isn't shared
 * @method self width(int $value) <em>Optional</em>. Width of the widget in logical pixels; omitted if the widget must occupy the whole width of the message bubble
 * @method self height(int $value) <em>Optional</em>. Height of the widget in logical pixels; omitted if the height of the widget isn't fixed
 * @method self html(string $value) HTML code of the widget
 * @method self public_data(string $value) <em>Optional</em>. Public data of the widget, which is available wherever the widget is shown. All messages with a shared widget have the same public data.
 * @method self chat_data(string $value) <em>Optional</em>. Data of the widget, which is specific to the message
 */
class Widget
{
    /** @var string */
    public string $id;
    /** @var int */
    public int $width;
    /** @var int */
    public int $height;
    /** @var string */
    public string $html;
    /** @var string */
    public string $public_data;
    /** @var string */
    public string $chat_data;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object describes an <a href="/bots/widgets">HTML widget</a>.
     * @param string|null $id <em>Optional</em>. Unique identifier of the shared widget, which can be used to send the widget again using the method <a href="https://core.telegram.org/bots/api#sendsharedwidget">sendSharedWidget</a>; omitted if the widget isn't shared
     * @param int|null $width <em>Optional</em>. Width of the widget in logical pixels; omitted if the widget must occupy the whole width of the message bubble
     * @param int|null $height <em>Optional</em>. Height of the widget in logical pixels; omitted if the height of the widget isn't fixed
     * @param string|null $html HTML code of the widget
     * @param string|null $public_data <em>Optional</em>. Public data of the widget, which is available wherever the widget is shown. All messages with a shared widget have the same public data.
     * @param string|null $chat_data <em>Optional</em>. Data of the widget, which is specific to the message
     */
    public static function make(string $id = null, int $width = null, int $height = null, string $html = null, string $public_data = null, string $chat_data = null): self
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