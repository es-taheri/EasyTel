<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 * @method self type(string $value) Type of the entity. Currently, can be “mention” (<code>@username</code>), “hashtag” (<code>#hashtag</code> or <code>#hashtag@chatusername</code>), “cashtag” (<code>$USD</code> or <code>$USD@chatusername</code>), “bot_command” (<code>/start@jobs_bot</code>), “url” (<code>https://telegram.org</code>), “email” (<code>do-not-reply@telegram.org</code>), “phone_number” (<code>+1-212-555-0123</code>), “bold” (<strong>bold text</strong>), “italic” (<em>italic text</em>), “underline” (underlined text), “strikethrough” (strikethrough text), “spoiler” (spoiler message), “blockquote” (block quotation), “expandable_blockquote” (collapsed-by-default block quotation), “code” (monowidth string), “pre” (monowidth block), “text_link” (for clickable text URLs), “text_mention” (for users <a href="https://telegram.org/blog/edit#new-mentions">without usernames</a>), “custom_emoji” (for inline custom emoji stickers), or “date_time” (for formatted date and time)
 * @method self offset(int $value) Offset in <a href="/api/entities#entity-length">UTF-16 code units</a> to the start of the entity
 * @method self length(int $value) Length of the entity in <a href="/api/entities#entity-length">UTF-16 code units</a>
 * @method self url(string $value) <em>Optional</em>. For “text_link” only, URL that will be opened after user taps on the text
 * @method self user(User $value) <em>Optional</em>. For “text_mention” only, the mentioned user
 * @method self language(string $value) <em>Optional</em>. For “pre” only, the programming language of the entity text
 * @method self custom_emoji_id(string $value) <em>Optional</em>. For “custom_emoji” only, unique identifier of the custom emoji. Use <a href="https://core.telegram.org/bots/api#getcustomemojistickers">getCustomEmojiStickers</a> to get full information about the sticker
 * @method self unix_time(int $value) <em>Optional</em>. For “date_time” only, the Unix time associated with the entity
 * @method self date_time_format(string $value) <em>Optional</em>. For “date_time” only, the string that defines the formatting of the date and time. See <a href="https://core.telegram.org/bots/api#date-time-entity-formatting">date-time entity formatting</a> for more details.
 */
class MessageEntity
{
    public string $type;
    public int $offset;
    public int $length;
    public string $url;
    public User $user;
    public string $language;
    public string $custom_emoji_id;
    public int $unix_time;
    public string $date_time_format;

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
        if (isset($update['user'])) $this->user = new User($update['user']);
    }

    /**
     * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
     * @param string|null $type Type of the entity. Currently, can be “mention” (<code>@username</code>), “hashtag” (<code>#hashtag</code> or <code>#hashtag@chatusername</code>), “cashtag” (<code>$USD</code> or <code>$USD@chatusername</code>), “bot_command” (<code>/start@jobs_bot</code>), “url” (<code>https://telegram.org</code>), “email” (<code>do-not-reply@telegram.org</code>), “phone_number” (<code>+1-212-555-0123</code>), “bold” (<strong>bold text</strong>), “italic” (<em>italic text</em>), “underline” (underlined text), “strikethrough” (strikethrough text), “spoiler” (spoiler message), “blockquote” (block quotation), “expandable_blockquote” (collapsed-by-default block quotation), “code” (monowidth string), “pre” (monowidth block), “text_link” (for clickable text URLs), “text_mention” (for users <a href="https://telegram.org/blog/edit#new-mentions">without usernames</a>), “custom_emoji” (for inline custom emoji stickers), or “date_time” (for formatted date and time)
     * @param int|null $offset Offset in <a href="/api/entities#entity-length">UTF-16 code units</a> to the start of the entity
     * @param int|null $length Length of the entity in <a href="/api/entities#entity-length">UTF-16 code units</a>
     * @param string|null $url <em>Optional</em>. For “text_link” only, URL that will be opened after user taps on the text
     * @param User|null $user <em>Optional</em>. For “text_mention” only, the mentioned user
     * @param string|null $language <em>Optional</em>. For “pre” only, the programming language of the entity text
     * @param string|null $custom_emoji_id <em>Optional</em>. For “custom_emoji” only, unique identifier of the custom emoji. Use <a href="https://core.telegram.org/bots/api#getcustomemojistickers">getCustomEmojiStickers</a> to get full information about the sticker
     * @param int|null $unix_time <em>Optional</em>. For “date_time” only, the Unix time associated with the entity
     * @param string|null $date_time_format <em>Optional</em>. For “date_time” only, the string that defines the formatting of the date and time. See <a href="https://core.telegram.org/bots/api#date-time-entity-formatting">date-time entity formatting</a> for more details.
     */
    public static function make(string $type = null, int $offset = null, int $length = null, string $url = null, User $user = null, string $language = null, string $custom_emoji_id = null, int $unix_time = null, string $date_time_format = null): self
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