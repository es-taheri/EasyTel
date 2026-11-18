<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents a bot command.
 * @method self command(string $value) Text of the command; 1-32 characters. Can contain only lowercase English letters, digits and underscores.
 * @method self description(string $value) Description of the command; 1-256 characters
 * @method self is_ephemeral(bool $value) <em>Optional</em>. <em>True</em>, if the command sends an ephemeral message, which can be seen only by the sender of the message and the bot
 */
class BotCommand
{
    /** @var string */
    public string $command;
    /** @var string */
    public string $description;
    /** @var bool */
    public bool $is_ephemeral;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents a bot command.
     * @param string|null $command Text of the command; 1-32 characters. Can contain only lowercase English letters, digits and underscores.
     * @param string|null $description Description of the command; 1-256 characters
     * @param bool|null $is_ephemeral <em>Optional</em>. <em>True</em>, if the command sends an ephemeral message, which can be seen only by the sender of the message and the bot
     */
    public static function make(string $command = null, string $description = null, bool $is_ephemeral = null): self
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