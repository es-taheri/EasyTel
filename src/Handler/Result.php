<?php

namespace EasyTel\Handler;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 *
 * @property bool $success Identifies HTTP request SUCCESS or NOT
 * @property bool $ok Identifies telegram bot api response OK or NOT
 */
class Result
{
    public bool $success;
    public bool $ok = false;
    private readonly string|object|array $response;

    public function __construct(
        private readonly array $result, private readonly int $output = Telegram::OUTPUT_OBJECT
    )
    {
        $this->success = (bool) ($result['success'] ?? false);

        if (!is_null($result['response'] ?? null)):
            $this->response = $result['response'];
            $decoded = json::to_object($result['response']);
            $this->ok = is_object($decoded) && isset($decoded->ok) && $decoded->ok === true;
        endif;
    }

    /**
     * HTTP request success details
     * @return object{code:int,header:object,size:int}|null
     */
    public function success(): object|null
    {
        if ($this->success)
            return json::to_object([
                'code' => $this->result['code'],
                'header' => $this->result['header'] ?? [],
                'size' => $this->result['size'] ?? null,
            ]);
        else
            return null;
    }

    /**
     * Telegram bot api response
     *
     * A method that returns <em>True</em> (setWebhook, deleteMessage, ...) resolves
     * to a boolean here, so the return type can not be narrowed.
     *
     * @param int|null $type Output format, defaults to the one given to {@see Telegram}
     * @return object{ok:true,result:object}|object{ok:false,error_code:int,description:string}|array|string|bool|null
     */
    public function response(?int $type = null): mixed
    {
        if (!isset($this->response))
            return null;

        return Statics::output($this->response, $type ?? $this->output);
    }

    /**
     * HTTP request error details
     * @return object{code:int,error:string}|null
     */
    public function fail(): object|null
    {
        if (!$this->success)
            return json::to_object([
                'code' => $this->result['code'] ?? 0,
                'error' => $this->result['error'] ?? 'Unknown error',
            ]);
        else
            return null;
    }
}
