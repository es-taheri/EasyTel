<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object defines the criteria used to request suitable users. Information about the selected users will be shared with the bot when the corresponding button is pressed. <a href="/bots/features#chat-and-user-selection">More about requesting users »</a>
 * @method self request_id(int $value) Signed 32-bit identifier of the request that will be received back in the <a href="https://core.telegram.org/bots/api#usersshared">UsersShared</a> object. Must be unique within the message.
 * @method self user_is_bot(bool $value) <em>Optional</em>. Pass <em>True</em> to request bots, pass <em>False</em> to request regular users. If not specified, no additional restrictions are applied.
 * @method self user_is_premium(bool $value) <em>Optional</em>. Pass <em>True</em> to request premium users, pass <em>False</em> to request non-premium users. If not specified, no additional restrictions are applied.
 * @method self max_quantity(int $value) <em>Optional</em>. The maximum number of users to be selected; 1-10. Defaults to 1.
 * @method self request_name(bool $value) <em>Optional</em>. Pass <em>True</em> to request the users' first and last names
 * @method self request_username(bool $value) <em>Optional</em>. Pass <em>True</em> to request the users' usernames
 * @method self request_photo(bool $value) <em>Optional</em>. Pass <em>True</em> to request the users' photos
 */
class KeyboardButtonRequestUsers
{
    /** @var int */
    public int $request_id;
    /** @var bool */
    public bool $user_is_bot;
    /** @var bool */
    public bool $user_is_premium;
    /** @var int */
    public int $max_quantity;
    /** @var bool */
    public bool $request_name;
    /** @var bool */
    public bool $request_username;
    /** @var bool */
    public bool $request_photo;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object defines the criteria used to request suitable users. Information about the selected users will be shared with the bot when the corresponding button is pressed. <a href="/bots/features#chat-and-user-selection">More about requesting users »</a>
     * @param int|null $request_id Signed 32-bit identifier of the request that will be received back in the <a href="https://core.telegram.org/bots/api#usersshared">UsersShared</a> object. Must be unique within the message.
     * @param bool|null $user_is_bot <em>Optional</em>. Pass <em>True</em> to request bots, pass <em>False</em> to request regular users. If not specified, no additional restrictions are applied.
     * @param bool|null $user_is_premium <em>Optional</em>. Pass <em>True</em> to request premium users, pass <em>False</em> to request non-premium users. If not specified, no additional restrictions are applied.
     * @param int|null $max_quantity <em>Optional</em>. The maximum number of users to be selected; 1-10. Defaults to 1.
     * @param bool|null $request_name <em>Optional</em>. Pass <em>True</em> to request the users' first and last names
     * @param bool|null $request_username <em>Optional</em>. Pass <em>True</em> to request the users' usernames
     * @param bool|null $request_photo <em>Optional</em>. Pass <em>True</em> to request the users' photos
     */
    public static function make(int $request_id = null, bool $user_is_bot = null, bool $user_is_premium = null, int $max_quantity = null, bool $request_name = null, bool $request_username = null, bool $request_photo = null): self
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