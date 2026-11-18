<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

/**
 * Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns <em>True</em> on success.
 *
 * @method CreateNewStickerSet sticker_type(string $value) Type of stickers in the set, pass “regular”, “mask”, or “custom_emoji”. By default, a regular sticker set is created.
 * @method CreateNewStickerSet needs_repainting(bool $value) Pass <em>True</em> if stickers in the sticker set must be repainted to the color of text when used in messages, the accent color if used as emoji status, white on chat photos, or another appropriate color based on context; for custom emoji sticker sets only
 *
 * @link https://core.telegram.org/bots/api#createnewstickerset
 */
class CreateNewStickerSet
{
    private Request $_request;
    private bool $_sent = false;
    /** @var int User identifier of created sticker set owner */
    private int $user_id;
    /** @var string Short name of sticker set, to be used in <code>t.me/addstickers/</code> URLs (e.g., <em>animals</em>). Can contain only English letters, digits and underscores. Must begin with a letter, can't contain consecutive underscores and must end in <code>"_by_<bot_username>"</code>. <code><bot_username></code> is case insensitive. 1-64 characters. */
    private string $name;
    /** @var string Sticker set title, 1-64 characters */
    private string $title;
    /** @var string A JSON-serialized list of 1-50 initial stickers to be added to the sticker set */
    private string $stickers;
    /** @var string Type of stickers in the set, pass “regular”, “mask”, or “custom_emoji”. By default, a regular sticker set is created. */
    private string $sticker_type;
    /** @var bool Pass <em>True</em> if stickers in the sticker set must be repainted to the color of text when used in messages, the accent color if used as emoji status, white on chat photos, or another appropriate color based on context; for custom emoji sticker sets only */
    private bool $needs_repainting;

    /**
     * @param Request $request
     * @param int $user_id User identifier of created sticker set owner
     * @param string $name Short name of sticker set, to be used in <code>t.me/addstickers/</code> URLs (e.g., <em>animals</em>). Can contain only English letters, digits and underscores. Must begin with a letter, can't contain consecutive underscores and must end in <code>"_by_<bot_username>"</code>. <code><bot_username></code> is case insensitive. 1-64 characters.
     * @param string $title Sticker set title, 1-64 characters
     * @param string $stickers A JSON-serialized list of 1-50 initial stickers to be added to the sticker set
     */
    public function __construct(Request $request, int $user_id, string $name, string $title, string $stickers)
    {
        $this->_request = $request;
        $this->user_id = $user_id;
        $this->name = $name;
        $this->title = $title;
        $this->stickers = $stickers;
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
