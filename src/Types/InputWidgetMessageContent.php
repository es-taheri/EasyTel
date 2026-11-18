<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a message with an <a href="/bots/widgets">HTML widget</a> to be sent as the result of an inline query. Currently, widgets can't be sent in response to inline queries in the production environment.
 * @method self html(string $value) HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level.
 * @method self width(int $value) <em>Optional</em>. Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble.
 * @method self height(int $value) <em>Optional</em>. Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600.
 * @method self public_data(string $value) <em>Optional</em>. Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data.
 * @method self chat_data(string $value) <em>Optional</em>. Data of the widget, which is specific to the message, 0-1024 bytes
 * @method self caption(string $value) <em>Optional</em>. Widget caption, 0-1024 characters after entities parsing
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the widget caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self caption_entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method self show_caption_above_media(bool $value) <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the widget
 * @method self allow_queries(bool $value) <em>Optional</em>. Pass <em>True</em> to allow the widget to send queries to the bot
 * @method self is_shared(bool $value) <em>Optional</em>. Pass <em>True</em> to create a shared widget. Each message sent from the result creates a separate shared widget, whose identifier is received by the bot only in the field <em>widget_id</em> of queries from the widget. To send an existing shared widget in response to an inline query, use <a href="https://core.telegram.org/bots/api#inputsharedwidgetmessagecontent">InputSharedWidgetMessageContent</a> instead.
 */
class InputWidgetMessageContent
{
    /** @var string */
    public string $html;
    /** @var int */
    public int $width;
    /** @var int */
    public int $height;
    /** @var string */
    public string $public_data;
    /** @var string */
    public string $chat_data;
    /** @var string */
    public string $caption;
    /** @var string */
    public string $parse_mode;
    /** @var MessageEntity[] */
    public array $caption_entities;
    /** @var bool */
    public bool $show_caption_above_media;
    /** @var bool */
    public bool $allow_queries;
    /** @var bool */
    public bool $is_shared;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a message with an <a href="/bots/widgets">HTML widget</a> to be sent as the result of an inline query. Currently, widgets can't be sent in response to inline queries in the production environment.
     * @param string|null $html HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level.
     * @param int|null $width <em>Optional</em>. Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble.
     * @param int|null $height <em>Optional</em>. Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600.
     * @param string|null $public_data <em>Optional</em>. Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data.
     * @param string|null $chat_data <em>Optional</em>. Data of the widget, which is specific to the message, 0-1024 bytes
     * @param string|null $caption <em>Optional</em>. Widget caption, 0-1024 characters after entities parsing
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the widget caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $caption_entities <em>Optional</em>. List of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param bool|null $show_caption_above_media <em>Optional</em>. Pass <em>True</em> if the caption must be shown above the widget
     * @param bool|null $allow_queries <em>Optional</em>. Pass <em>True</em> to allow the widget to send queries to the bot
     * @param bool|null $is_shared <em>Optional</em>. Pass <em>True</em> to create a shared widget. Each message sent from the result creates a separate shared widget, whose identifier is received by the bot only in the field <em>widget_id</em> of queries from the widget. To send an existing shared widget in response to an inline query, use <a href="https://core.telegram.org/bots/api#inputsharedwidgetmessagecontent">InputSharedWidgetMessageContent</a> instead.
     */
    public static function make(string $html = null, int $width = null, int $height = null, string $public_data = null, string $chat_data = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $show_caption_above_media = null, bool $allow_queries = null, bool $is_shared = null): self
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