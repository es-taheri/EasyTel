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

    public function __construct(private readonly array $result)
    {
        $this->success = $result['success'];
        if (!is_null($result['response'])):
            $this->response = $result['response'];
            $this->ok = json::to_object($result['response'])->ok;
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
                'header' => $this->result['header'],
                'size' => $this->result['size'],
            ]);
        else
            return null;
    }

    /**
     * Telegram bot api response
     * @param int $type
     * @return object{ok:true,result:object}|object{ok:false,error_code:int,description:string}|array|string|null
     */
    public function response(int $type = Telegram::OUTPUT_OBJECT): object|array|string|null
    {
        return (isset($this->response)) ? Statics::output($this->response, $type) : null;
    }

    /**
     * HTTP request error details
     * @return object{code:int,error:string}|null
     */
    public function fail(): object|null
    {
        if (!$this->success)
            return json::to_object([
                'code' => $this->result['code'],
                'error' => $this->result['error'],
            ]);
        else
            return null;
    }
}