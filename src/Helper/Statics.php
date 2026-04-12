<?php

namespace EasyTel\Helper;

use EasyTel\Telegram;
use JSON\json;

class Statics
{
    public static function output(string|object|array $data, int $type = Telegram::OUTPUT_JSON): object|array|string
    {
        return match ($type) {
            Telegram::OUTPUT_JSON => json::to_json($data),
            Telegram::OUTPUT_OBJECT => json::to_object($data),
            default => json::to_array($data),
        };
    }

}