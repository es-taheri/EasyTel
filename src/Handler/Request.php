<?php

namespace EasyTel\Handler;

use EasyTel\Telegram;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use JSON\json;
use Psr\Http\Message\ResponseInterface;

class Request
{
    private string $method;
    public int $output;
    private Client $guzzle;

    public function __construct(
        Client $guzzle, string $method = 'POST', $output = Telegram::OUTPUT_OBJECT
    )
    {
        $this->guzzle = $guzzle;
        $this->method = $method;
        $this->output = $output;
    }

    public function send(string $method, array $parameters): Result
    {
        $promise = $this->guzzle->requestAsync($this->method, "$method", $this->options($parameters));

        return $promise->then(
            function (ResponseInterface $res) {
                $body = $res->getBody();
                $size = $body->getSize();
                $contents = $body->getContents();

                return new Result([
                    'success' => true,
                    'code' => $res->getStatusCode(),
                    'header' => $res->getHeaders(),
                    'response' => $contents,
                    'size' => $size
                ], $this->output);
            },
            function (ClientException|ServerException|RequestException|GuzzleException|ConnectException $err) {
                // Guzzle embeds the full request URI in its message; the URI
                // contains the bot token, so it must never reach logs.
                $message = preg_replace('~bot\d+:[A-Za-z0-9_-]+~', 'bot<token>', $err->getMessage());

                if ($err instanceof ConnectException) {
                    $return = [
                        'success' => false,
                        'code' => $err->getCode(),
                        'error' => $message,
                        'response' => null
                    ];
                } else {
                    $return = [
                        'success' => false,
                        'code' => $err->getCode(),
                        'error' => $message
                    ];
                    $resp = $err->getResponse();
                    $return['response'] = is_null($resp) ? null : $resp->getBody()->getContents();
                }
                return new Result($return, $this->output);
            }
        )->wait();
    }

    /**
     * Build Guzzle request options for the configured HTTP verb.
     *
     * Telegram accepts urlencoded parameters, JSON encoded strings for nested
     * objects and multipart/form-data for uploads. The Bot API also requires
     * GET requests to carry their parameters in the query string.
     */
    private function options(array $parameters): array
    {
        $isGet = in_array(strtoupper($this->method), ['GET', 'HEAD'], true);

        if ($isGet)
            return ['query' => array_map([self::class, 'stringify'], $parameters)];

        if (self::hasFile($parameters))
            return ['multipart' => self::multipart($parameters)];

        return ['form_params' => $parameters];
    }

    private static function hasFile(array $parameters): bool
    {
        foreach ($parameters as $value) {
            if (self::isFile($value))
                return true;
            if (is_array($value) && self::hasFile($value))
                return true;
        }

        return false;
    }

    private static function isFile(mixed $value): bool
    {
        return is_resource($value)
            || $value instanceof \SplFileInfo
            || $value instanceof \CURLFile;
    }

    private static function stringify(mixed $value): mixed
    {
        if (is_bool($value))
            return $value ? 'true' : 'false';
        if (is_array($value))
            return json::to_json($value);
        if (is_object($value))
            return json::to_json($value);

        return $value;
    }

    private static function multipart(array $parameters): array
    {
        $parts = [];

        foreach ($parameters as $name => $value) {
            if (self::isFile($value)):
                $parts[] = self::filePart((string) $name, $value);
                continue;
            endif;

            if (is_null($value))
                continue;

            $parts[] = [
                'name' => (string) $name,
                'contents' => self::stringify($value),
            ];
        }

        return $parts;
    }

    private static function filePart(string $name, mixed $value): array
    {
        if ($value instanceof \CURLFile) {
            $path = $value->getFilename();
            $filename = $value->getPostFilename() ?: basename($path);
            $contents = is_file($path) ? fopen($path, 'rb') : $path;

            $part = ['name' => $name, 'contents' => $contents, 'filename' => $filename];
            if ($value->getMimeType())
                $part['headers'] = ['Content-Type' => $value->getMimeType()];

            return $part;
        }

        if ($value instanceof \SplFileInfo) {
            return [
                'name' => $name,
                'contents' => fopen($value->getPathname(), 'rb'),
                'filename' => $value->getFilename(),
            ];
        }

        $uri = stream_get_meta_data($value)['uri'] ?? '';
        $filename = ($uri !== '' && @is_file($uri)) ? basename($uri) : 'file';

        return ['name' => $name, 'contents' => $value, 'filename' => $filename];
    }
}
