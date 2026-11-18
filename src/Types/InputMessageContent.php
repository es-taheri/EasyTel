<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents the content of a message to be sent as a result of an inline query. Telegram clients currently support the following types:
 * @method self inputtextmessagecontent(InputTextMessageContent $value)
 * @method self inputrichmessagecontent(InputRichMessageContent $value)
 * @method self inputlocationmessagecontent(InputLocationMessageContent $value)
 * @method self inputvenuemessagecontent(InputVenueMessageContent $value)
 * @method self inputcontactmessagecontent(InputContactMessageContent $value)
 * @method self inputinvoicemessagecontent(InputInvoiceMessageContent $value)
 * @method self inputwidgetmessagecontent(InputWidgetMessageContent $value)
 * @method self inputsharedwidgetmessagecontent(InputSharedWidgetMessageContent $value)
 */
class InputMessageContent
{
    protected const _UNION_BASE = true;
    /** @var InputTextMessageContent */
    public InputTextMessageContent $inputtextmessagecontent;
    /** @var InputRichMessageContent */
    public InputRichMessageContent $inputrichmessagecontent;
    /** @var InputLocationMessageContent */
    public InputLocationMessageContent $inputlocationmessagecontent;
    /** @var InputVenueMessageContent */
    public InputVenueMessageContent $inputvenuemessagecontent;
    /** @var InputContactMessageContent */
    public InputContactMessageContent $inputcontactmessagecontent;
    /** @var InputInvoiceMessageContent */
    public InputInvoiceMessageContent $inputinvoicemessagecontent;
    /** @var InputWidgetMessageContent */
    public InputWidgetMessageContent $inputwidgetmessagecontent;
    /** @var InputSharedWidgetMessageContent */
    public InputSharedWidgetMessageContent $inputsharedwidgetmessagecontent;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents the content of a message to be sent as a result of an inline query. Telegram clients currently support the following types:
     * @param InputTextMessageContent|null $inputtextmessagecontent
     * @param InputRichMessageContent|null $inputrichmessagecontent
     * @param InputLocationMessageContent|null $inputlocationmessagecontent
     * @param InputVenueMessageContent|null $inputvenuemessagecontent
     * @param InputContactMessageContent|null $inputcontactmessagecontent
     * @param InputInvoiceMessageContent|null $inputinvoicemessagecontent
     * @param InputWidgetMessageContent|null $inputwidgetmessagecontent
     * @param InputSharedWidgetMessageContent|null $inputsharedwidgetmessagecontent
     */
    public static function make(InputTextMessageContent $inputtextmessagecontent = null, InputRichMessageContent $inputrichmessagecontent = null, InputLocationMessageContent $inputlocationmessagecontent = null, InputVenueMessageContent $inputvenuemessagecontent = null, InputContactMessageContent $inputcontactmessagecontent = null, InputInvoiceMessageContent $inputinvoicemessagecontent = null, InputWidgetMessageContent $inputwidgetmessagecontent = null, InputSharedWidgetMessageContent $inputsharedwidgetmessagecontent = null): self
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