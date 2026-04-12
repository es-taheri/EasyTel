<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Contains information about a suggested post.
 * @method self state(string $value) State of the suggested post. Currently, it can be one of “pending”, “approved”, “declined”.
 * @method self price(SuggestedPostPrice $value) <em>Optional</em>. Proposed price of the post. If the field is omitted, then the post is unpaid.
 * @method self send_date(int $value) <em>Optional</em>. Proposed send date of the post. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user or administrator who approves it.
 */
class SuggestedPostInfo
{
    public string $state;
    public SuggestedPostPrice $price;
    public int $send_date;

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
        if (isset($update['price'])) $this->price = new SuggestedPostPrice($update['price']);
    }

    /**
     * Contains information about a suggested post.
     * @param string|null $state State of the suggested post. Currently, it can be one of “pending”, “approved”, “declined”.
     * @param SuggestedPostPrice|null $price <em>Optional</em>. Proposed price of the post. If the field is omitted, then the post is unpaid.
     * @param int|null $send_date <em>Optional</em>. Proposed send date of the post. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user or administrator who approves it.
     */
    public static function make(string $state = null, SuggestedPostPrice $price = null, int $send_date = null): self
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