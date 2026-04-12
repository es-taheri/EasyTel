<?php

namespace EasyTel;

use EasyTel\Helper\Types;
use EasyTel\Helper\Methods;
use EasyTel\Types\Update;
use GuzzleHttp\Client;

class Telegram
{
    public Update $updates;
    public Methods $methods;
    public Webhook $webhook;
    public const OUTPUT_JSON = 111;
    public const OUTPUT_OBJECT = 112;
    public const OUTPUT_ARRAY = 113;
    public int $output;

    public function __construct(
        string $bot_token, string|null $updates = null, string $botapi_url = 'https://api.telegram.org',
        string $request_method = 'POST', array $guzzle_client_options = [], int $output = self::OUTPUT_OBJECT
    )
    {
        $guzzle_client_options['base_uri'] = "$botapi_url/bot$bot_token/";
        $guzzle = new Client($guzzle_client_options);
        if (!empty($updates)) $this->updates = Types::make($updates)->Update();
        $this->methods = new Methods($guzzle, $request_method, $output);
        $this->webhook = new Webhook($guzzle, $request_method, $output);
    }
}