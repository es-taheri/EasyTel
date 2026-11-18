<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes a rich message to be sent. Exactly <strong>one</strong> of the fields <em>html</em>, <em>markdown</em>, or <em>blocks</em> must be used.
 * @method self blocks(InputRichBlock[] $value) <em>Optional</em>. Content of the rich message to send described as a list of blocks
 * @method self html(string $value) <em>Optional</em>. Content of the rich message to send described using HTML formatting. See <a href="https://core.telegram.org/bots/api#rich-message-formatting-options">rich message formatting options</a> for more details. Use <em>media</em> field to specify the media used in the message.
 * @method self markdown(string $value) <em>Optional</em>. Content of the rich message to send described using Markdown formatting. See <a href="https://core.telegram.org/bots/api#rich-message-formatting-options">rich message formatting options</a> for more details. Use <em>media</em> field to specify the media used in the message.
 * @method self media(InputRichMessageMedia[] $value) <em>Optional</em>. List of media that are specified in the <em>markdown</em> or <em>html</em> fields using <code>tg://photo?id=</code>, <code>tg://video?id=</code>, <code>tg://document?id=</code>, and <code>tg://audio?id=</code> links
 * @method self is_rtl(bool $value) <em>Optional</em>. Pass <em>True</em> if the rich message must be shown right-to-left
 * @method self skip_entity_detection(bool $value) <em>Optional</em>. Pass <em>True</em> to skip automatic detection of entities (e.g., URLs, email addresses, username mentions, hashtags, cashtags, bot commands, or phone numbers) in the text
 */
class InputRichMessage
{
    /** @var InputRichBlock[] */
    public array $blocks;
    /** @var string */
    public string $html;
    /** @var string */
    public string $markdown;
    /** @var InputRichMessageMedia[] */
    public array $media;
    /** @var bool */
    public bool $is_rtl;
    /** @var bool */
    public bool $skip_entity_detection;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * Describes a rich message to be sent. Exactly <strong>one</strong> of the fields <em>html</em>, <em>markdown</em>, or <em>blocks</em> must be used.
     * @param InputRichBlock[]|null $blocks <em>Optional</em>. Content of the rich message to send described as a list of blocks
     * @param string|null $html <em>Optional</em>. Content of the rich message to send described using HTML formatting. See <a href="https://core.telegram.org/bots/api#rich-message-formatting-options">rich message formatting options</a> for more details. Use <em>media</em> field to specify the media used in the message.
     * @param string|null $markdown <em>Optional</em>. Content of the rich message to send described using Markdown formatting. See <a href="https://core.telegram.org/bots/api#rich-message-formatting-options">rich message formatting options</a> for more details. Use <em>media</em> field to specify the media used in the message.
     * @param InputRichMessageMedia[]|null $media <em>Optional</em>. List of media that are specified in the <em>markdown</em> or <em>html</em> fields using <code>tg://photo?id=</code>, <code>tg://video?id=</code>, <code>tg://document?id=</code>, and <code>tg://audio?id=</code> links
     * @param bool|null $is_rtl <em>Optional</em>. Pass <em>True</em> if the rich message must be shown right-to-left
     * @param bool|null $skip_entity_detection <em>Optional</em>. Pass <em>True</em> to skip automatic detection of entities (e.g., URLs, email addresses, username mentions, hashtags, cashtags, bot commands, or phone numbers) in the text
     */
    public static function make(array $blocks = null, string $html = null, string $markdown = null, array $media = null, bool $is_rtl = null, bool $skip_entity_detection = null): self
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