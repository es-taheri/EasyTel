<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;


/**
 * @method GetUpdates offset(int $value) Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as <a href="https://core.telegram.org/bots/api#getupdates">getUpdates</a> is called with an <em>offset</em> higher than its <em>update_id</em>. The negative offset can be specified to retrieve updates starting from <em>-offset</em> update from the end of the updates queue. All previous updates will be forgotten.
 * @method GetUpdates limit(int $value) Limits the number of updates to be retrieved. Values between 1-100 are accepted. Defaults to 100.
 * @method GetUpdates timeout(int $value) Timeout in seconds for long polling. Defaults to 0, i.e. usual short polling. Should be positive, short polling should be used for testing purposes only.
 * @method GetUpdates allowed_updates(string  $value) A JSON-serialized list of the update types you want your bot to receive. For example, specify <code>[&quot;message&quot;, &quot;edited_channel_post&quot;, &quot;callback_query&quot;]</code> to only receive updates of these types. See <a href="https://core.telegram.org/bots/api#update">Update</a> for a complete list of available update types. Specify an empty list to receive all update types except <em>chat_member</em>, <em>message_reaction</em>, and <em>message_reaction_count</em> (default). If not specified, the previous setting will be used.<br><br>Please note that this parameter doesn&#39;t affect updates created before the call to getUpdates, so unwanted updates may be received for a short period of time.
 */
class GetUpdates
{
    private Request $_request;
    private bool $_sent = false;
    private int $offset;
    private int $limit;
    private int $timeout;
    private string  $allowed_updates;

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