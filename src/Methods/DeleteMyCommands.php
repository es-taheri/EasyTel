<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\BotCommandScope;
/**
 * @method DeleteMyCommands scope(BotCommandScope $value) A JSON-serialized object, describing scope of users for which the commands are relevant. Defaults to <a href="https://core.telegram.org/bots/api#botcommandscopedefault">BotCommandScopeDefault</a>.
 * @method DeleteMyCommands language_code(string $value) A two-letter ISO 639-1 language code. If empty, commands will be applied to all users from the given scope, for whose language there are no dedicated commands
 */
class DeleteMyCommands
{
    private Request $_request;
    private bool $_sent = false;
    private BotCommandScope $scope;
    private string $language_code;

    public function __construct(Request $request)
    {
        $this->_request = $request;
        
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