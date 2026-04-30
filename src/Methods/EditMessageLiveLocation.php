<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InlineKeyboardMarkup;
/**
 * @method EditMessageLiveLocation business_connection_id(string $value) Unique identifier of the business connection on behalf of which the message to be edited was sent
 * @method EditMessageLiveLocation chat_id(int|string $value) Required if <em>inline_message_id</em> is not specified. Unique identifier for the target chat or username of the target channel (in the format <code>@channelusername</code>)
 * @method EditMessageLiveLocation message_id(int $value) Required if <em>inline_message_id</em> is not specified. Identifier of the message to edit
 * @method EditMessageLiveLocation inline_message_id(string $value) Required if <em>chat_id</em> and <em>message_id</em> are not specified. Identifier of the inline message
 * @method EditMessageLiveLocation live_period(int $value) New period in seconds during which the location can be updated, starting from the message send date. If 0x7FFFFFFF is specified, then the location can be updated forever. Otherwise, the new value must not exceed the current <em>live_period</em> by more than a day, and the live location expiration date must remain within the next 90 days. If not specified, then <em>live_period</em> remains unchanged
 * @method EditMessageLiveLocation horizontal_accuracy(Float $value) The radius of uncertainty for the location, measured in meters; 0-1500
 * @method EditMessageLiveLocation heading(int $value) Direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
 * @method EditMessageLiveLocation proximity_alert_radius(int $value) The maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
 * @method EditMessageLiveLocation reply_markup(InlineKeyboardMarkup $value) A JSON-serialized object for a new <a href="/bots/features#inline-keyboards">inline keyboard</a>.
 */
class EditMessageLiveLocation
{
    private Request $_request;
    private bool $_sent = false;
    private Float $latitude;
    private Float $longitude;
    private string $business_connection_id;
    private int|string $chat_id;
    private int $message_id;
    private string $inline_message_id;
    private int $live_period;
    private Float $horizontal_accuracy;
    private int $heading;
    private int $proximity_alert_radius;
    private InlineKeyboardMarkup $reply_markup;

    public function __construct(Request $request, Float $latitude, Float $longitude)
    {
        $this->_request = $request;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
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