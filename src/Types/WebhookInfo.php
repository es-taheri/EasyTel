<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * Describes the current status of a webhook.
 * @method self url(string $value) Webhook URL, may be empty if webhook is not set up
 * @method self has_custom_certificate(bool $value) <em>True</em>, if a custom certificate was provided for webhook certificate checks
 * @method self pending_update_count(int $value) Number of updates awaiting delivery
 * @method self ip_address(string $value) <em>Optional</em>. Currently used webhook IP address
 * @method self last_error_date(int $value) <em>Optional</em>. Unix time for the most recent error that happened when trying to deliver an update via webhook
 * @method self last_error_message(string $value) <em>Optional</em>. Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook
 * @method self last_synchronization_error_date(int $value) <em>Optional</em>. Unix time of the most recent error that happened when trying to synchronize available updates with Telegram datacenters
 * @method self max_connections(int $value) <em>Optional</em>. The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery
 * @method self allowed_updates(array $value) <em>Optional</em>. A list of update types the bot is subscribed to. Defaults to all update types except <em>chat_member</em>
 */
class WebhookInfo
{
    public string $url;
    public bool $has_custom_certificate;
    public int $pending_update_count;
    public string $ip_address;
    public int $last_error_date;
    public string $last_error_message;
    public int $last_synchronization_error_date;
    public int $max_connections;
    public array $allowed_updates;

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
        
    }

    /**
     * Describes the current status of a webhook.
     * @param string|null $url Webhook URL, may be empty if webhook is not set up
     * @param bool|null $has_custom_certificate <em>True</em>, if a custom certificate was provided for webhook certificate checks
     * @param int|null $pending_update_count Number of updates awaiting delivery
     * @param string|null $ip_address <em>Optional</em>. Currently used webhook IP address
     * @param int|null $last_error_date <em>Optional</em>. Unix time for the most recent error that happened when trying to deliver an update via webhook
     * @param string|null $last_error_message <em>Optional</em>. Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook
     * @param int|null $last_synchronization_error_date <em>Optional</em>. Unix time of the most recent error that happened when trying to synchronize available updates with Telegram datacenters
     * @param int|null $max_connections <em>Optional</em>. The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery
     * @param array|null $allowed_updates <em>Optional</em>. A list of update types the bot is subscribed to. Defaults to all update types except <em>chat_member</em>
     */
    public static function make(string $url = null, bool $has_custom_certificate = null, int $pending_update_count = null, string $ip_address = null, int $last_error_date = null, string $last_error_message = null, int $last_synchronization_error_date = null, int $max_connections = null, array $allowed_updates = null): self
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