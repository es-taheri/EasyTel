<?php

namespace EasyTel;

use EasyTel\Handler\Request;
use GuzzleHttp\Client;
use EasyTel\Handler\Result;

class Webhook extends Request
{
    public function __construct(
        Client $guzzle, string $method = 'POST', $output = Telegram::OUTPUT_OBJECT
    )
    {
        parent::__construct($guzzle, $method, $output);
    }

    /**
     * Use this method to specify a URL and receive incoming updates via an outgoing webhook.
     * Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update.
     * In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success.
     *
     * @param string $url
     * @param mixed $certificate
     * @param string $ip_address
     * @param int $max_connections
     * @param string $allowed_updates
     * @param bool $drop_pending_updates
     * @param string $secret_token
     * @return Result
     * @link https://core.telegram.org/bots/api#setwebhook
     */
    public function setWebhook(
        string $url, mixed $certificate = null, string $ip_address = null, int $max_connections = null,
        string $allowed_updates = null, bool $drop_pending_updates = null, string $secret_token = null
    ): Result
    {
        return parent::send(__FUNCTION__, get_defined_vars());
    }

    /**
     * Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
     *
     * @param bool $drop_pending_updates
     * @return mixed
     * @link https://core.telegram.org/bots/api#deletewebhook
     */
    public function deleteWebhook(bool $drop_pending_updates = null): Result
    {
        return parent::send(__FUNCTION__, get_defined_vars());
    }

    /**
     * Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object.
     * If the bot is using getUpdates, will return an object with the url field empty.
     *
     * @return mixed
     * @link https://core.telegram.org/bots/api#getwebhookinfo
     */
    public function getWebhookInfo(): Result
    {
        return parent::send(__FUNCTION__, get_defined_vars());
    }
}