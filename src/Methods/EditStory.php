<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputStoryContent;

/**
 * Edits a story previously posted by the bot on behalf of a managed business account. Requires the <em>can_manage_stories</em> business bot right. Returns <a href="https://core.telegram.org/bots/api#story">Story</a> on success.
 *
 * @method EditStory caption(string $value) Caption of the story, 0-2048 characters after entities parsing
 * @method EditStory parse_mode(string $value) Mode for parsing entities in the story caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method EditStory caption_entities(string $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method EditStory areas(string $value) A JSON-serialized list of clickable areas to be shown on the story
 *
 * @link https://core.telegram.org/bots/api#editstory
 */
class EditStory
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection */
    private string $business_connection_id;
    /** @var int Unique identifier of the story to edit */
    private int $story_id;
    /** @var InputStoryContent Content of the story */
    private InputStoryContent $content;
    /** @var string Caption of the story, 0-2048 characters after entities parsing */
    private string $caption;
    /** @var string Mode for parsing entities in the story caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em> */
    private string $caption_entities;
    /** @var string A JSON-serialized list of clickable areas to be shown on the story */
    private string $areas;

    /**
     * @param Request $request
     * @param string $business_connection_id Unique identifier of the business connection
     * @param int $story_id Unique identifier of the story to edit
     * @param InputStoryContent $content Content of the story
     */
    public function __construct(Request $request, string $business_connection_id, int $story_id, InputStoryContent $content)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->story_id = $story_id;
        $this->content = $content;
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
