<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method SetWebhook certificate(mixed $value) Upload your public key certificate so that the root certificate in use can be checked. See our <a href="/bots/self-signed">self-signed guide</a> for details.
 * @method SetWebhook ip_address(string $value) The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS
 * @method SetWebhook max_connections(int $value) The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery, 1-100. Defaults to <em>40</em>. Use lower values to limit the load on your bot&#39;s server, and higher values to increase your bot&#39;s throughput.
 * @method SetWebhook allowed_updates(string  $value) A JSON-serialized list of the update types you want your bot to receive. For example, specify <code>[&quot;message&quot;, &quot;edited_channel_post&quot;, &quot;callback_query&quot;]</code> to only receive updates of these types. See <a href="https://core.telegram.org/bots/api#update">Update</a> for a complete list of available update types. Specify an empty list to receive all update types except <em>chat_member</em>, <em>message_reaction</em>, and <em>message_reaction_count</em> (default). If not specified, the previous setting will be used.<br>Please note that this parameter doesn&#39;t affect updates created before the call to the setWebhook, so unwanted updates may be received for a short period of time.
 * @method SetWebhook drop_pending_updates(bool $value) Pass <em>True</em> to drop all pending updates
 * @method SetWebhook secret_token(string $value) A secret token to be sent in a header “X-Telegram-Bot-Api-Secret-Token” in every webhook request, 1-256 characters. Only characters <code>A-Z</code>, <code>a-z</code>, <code>0-9</code>, <code>_</code> and <code>-</code> are allowed. The header is useful to ensure that the request comes from a webhook set by you.
 */
class SetWebhook
{
    private Request $_request;
    private bool $_sent = false;
    private string $url;
    private mixed $certificate;
    private string $ip_address;
    private int $max_connections;
    private string  $allowed_updates;
    private bool $drop_pending_updates;
    private string $secret_token;

    public function __construct(Request $request, string $url)
    {
        $this->_request = $request;
        $this->url = $url;
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];
        foreach ($this as $key => $value):
            if (isset($this->{$key}) && !in_array($key, ['_request', '_result'])):
                if (gettype($value) == 'object')
                    $parameters[$key] = (fn() => ($this->_output()))->bindTo($value, $value)();
                else
                    $parameters[$key] = $value;
            endif;
        endforeach;
        $r = new \ReflectionClass($this);
        $this->_sent = true;
        return $this->_request->send(lcfirst($r->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if (!$this->_sent) $this->_result();
    }
}