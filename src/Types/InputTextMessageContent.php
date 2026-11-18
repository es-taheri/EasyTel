<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a text message to be sent as the result of an inline query.
 * @method self message_text(string $value) Text of the message to be sent, 1-4096 characters
 * @method self parse_mode(string $value) <em>Optional</em>. Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method self entities(MessageEntity[] $value) <em>Optional</em>. List of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
 * @method self link_preview_options(LinkPreviewOptions $value) <em>Optional</em>. Link preview generation options for the message
 */
class InputTextMessageContent
{
    /** @var string */
    public string $message_text;
    /** @var string */
    public string $parse_mode;
    /** @var MessageEntity[] */
    public array $entities;
    /** @var LinkPreviewOptions */
    public LinkPreviewOptions $link_preview_options;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Represents the <a href="https://core.telegram.org/bots/api#inputmessagecontent">content</a> of a text message to be sent as the result of an inline query.
     * @param string|null $message_text Text of the message to be sent, 1-4096 characters
     * @param string|null $parse_mode <em>Optional</em>. Mode for parsing entities in the message text. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
     * @param MessageEntity[]|null $entities <em>Optional</em>. List of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
     * @param LinkPreviewOptions|null $link_preview_options <em>Optional</em>. Link preview generation options for the message
     */
    public static function make(string $message_text = null, string $parse_mode = null, array $entities = null, LinkPreviewOptions $link_preview_options = null): self
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