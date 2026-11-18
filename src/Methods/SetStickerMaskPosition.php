<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\MaskPosition;

/**
 * Use this method to change the <a href="https://core.telegram.org/bots/api#maskposition">mask position</a> of a mask sticker. The sticker must belong to a sticker set that was created by the bot. Returns <em>True</em> on success.
 *
 * @method SetStickerMaskPosition mask_position(MaskPosition $value) A JSON-serialized object with the position where the mask should be placed on faces. Omit the parameter to remove the mask position.
 *
 * @link https://core.telegram.org/bots/api#setstickermaskposition
 */
class SetStickerMaskPosition
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string File identifier of the sticker */
    private string $sticker;
    /** @var MaskPosition A JSON-serialized object with the position where the mask should be placed on faces. Omit the parameter to remove the mask position. */
    private MaskPosition $mask_position;

    /**
     * @param Request $request
     * @param string $sticker File identifier of the sticker
     */
    public function __construct(Request $request, string $sticker)
    {
        $this->_request = $request;
        $this->sticker = $sticker;
    }

    public function __call(string $name, array $arguments)
    {
        if (!property_exists($this, $name)) {
            $this->_sent = true; // never send a half built request from __destruct()
            throw new \BadMethodCallException(
                sprintf('Unknown parameter "%s" for %s.', $name, static::class)
            );
        }

        if ($arguments === []) {
            $this->_sent = true;
            throw new \InvalidArgumentException(
                sprintf('Parameter "%s" of %s requires a value.', $name, static::class)
            );
        }

        try {
            $this->{$name} = array_shift($arguments);
        } catch (\Throwable $e) {
            $this->_sent = true; // the value did not fit: the request is invalid
            throw $e;
        }

        return $this;
    }

    public function _result(): Result
    {
        $parameters = [];

        foreach ($this as $key => $value) {
            if (str_starts_with($key, '_') || !isset($this->{$key})) {
                continue;
            }

            if (is_object($value)) {
                $parameters[$key] = method_exists($value, '_output')
                    ? (fn() => $this->_output())->bindTo($value, $value)()
                    : $value;
            } else {
                $parameters[$key] = $value;
            }
        }

        $reflection = new \ReflectionClass($this);
        $this->_sent = true;

        return $this->_request->send(lcfirst($reflection->getShortName()), $parameters);
    }

    public function __destruct()
    {
        if ($this->_sent)
            return;

        // Fire-and-forget call from the destructor must never raise inside
        // shutdown; the explicit _result() call is the error-reporting path.
        try {
            $this->_result();
        } catch (\Throwable) {
            // Ignored on purpose.
        }
    }
}
