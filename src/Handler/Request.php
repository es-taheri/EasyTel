<?php

namespace EasyTel\Handler;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
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
        $promise = $this->guzzle->requestAsync($this->method, "$method", [
            'form_params' => $parameters
        ]);
        return $promise->then(
            function (ResponseInterface $res) {
                $body = $res->getBody();
                return new Result([
                    'success' => true,
                    'code' => $res->getStatusCode(),
                    'header' => $res->getHeaders(),
                    'response' => $body->getContents(),
                    'size' => $body->getSize()
                ]);
            },
            function (ClientException|ServerException|RequestException|GuzzleException|ConnectException $err) {
                if ($err instanceof ConnectException) {
                    $return = [
                        'success' => false,
                        'code' => $err->getCode(),
                        'error' => $err->getMessage(),
                        'response' => null
                    ];
                } else {
                    $return = [
                        'success' => false,
                        'code' => $err->getCode(),
                        'error' => $err->getMessage()
                    ];
                    $resp = $err->getResponse();
                    $return['response'] = is_null($resp) ? null : $resp->getBody()->getContents();
                }
                return new Result($return);
            }
        )->wait();
    }
}