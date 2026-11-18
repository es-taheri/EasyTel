<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * The query contains data signed by a TON wallet of the user who sent the query. The data is signed for the domain <code>bot<bot_id></code>, where <code><bot_id></code> is the identifier of the bot.
 * @method self type(string $value) Type of the query, always “sign_data”
 * @method self wallet(WidgetQueryWallet $value) The wallet that signed the data
 * @method self sign_data(string $value) JSON-serialized signed data
 */
class WidgetQueryTypeSignData
{
    /** @var string */
    public string $type;
    /** @var WidgetQueryWallet */
    public WidgetQueryWallet $wallet;
    /** @var string */
    public string $sign_data;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * The query contains data signed by a TON wallet of the user who sent the query. The data is signed for the domain <code>bot<bot_id></code>, where <code><bot_id></code> is the identifier of the bot.
     * @param string|null $type Type of the query, always “sign_data”
     * @param WidgetQueryWallet|null $wallet The wallet that signed the data
     * @param string|null $sign_data JSON-serialized signed data
     */
    public static function make(string $type = null, WidgetQueryWallet $wallet = null, string $sign_data = null): self
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