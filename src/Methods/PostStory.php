<?php

namespace EasyTel\Methods;

use EasyTel\Handler\Request;
use EasyTel\Handler\Result;

use EasyTel\Types\InputStoryContent;

/**
 * Posts a story on behalf of a managed business account. Requires the <em>can_manage_stories</em> business bot right. Returns <a href="https://core.telegram.org/bots/api#story">Story</a> on success.
 *
 * @method PostStory caption(string $value) Caption of the story, 0-2048 characters after entities parsing
 * @method PostStory parse_mode(string $value) Mode for parsing entities in the story caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details.
 * @method PostStory caption_entities(string $value) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
 * @method PostStory areas(string $value) A JSON-serialized list of clickable areas to be shown on the story
 * @method PostStory post_to_chat_page(bool $value) Pass <em>True</em> to keep the story accessible after it expires
 * @method PostStory protect_content(bool $value) Pass <em>True</em> if the content of the story must be protected from forwarding and screenshotting
 *
 * @link https://core.telegram.org/bots/api#poststory
 */
class PostStory
{
    private Request $_request;
    private bool $_sent = false;
    /** @var string Unique identifier of the business connection */
    private string $business_connection_id;
    /** @var InputStoryContent Content of the story */
    private InputStoryContent $content;
    /** @var int Period after which the story is moved to the archive, in seconds; must be one of <code>6 * 3600</code>, <code>12 * 3600</code>, <code>86400</code>, or <code>2 * 86400</code> */
    private int $active_period;
    /** @var string Caption of the story, 0-2048 characters after entities parsing */
    private string $caption;
    /** @var string Mode for parsing entities in the story caption. See <a href="https://core.telegram.org/bots/api#formatting-options">formatting options</a> for more details. */
    private string $parse_mode;
    /** @var string A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em> */
    private string $caption_entities;
    /** @var string A JSON-serialized list of clickable areas to be shown on the story */
    private string $areas;
    /** @var bool Pass <em>True</em> to keep the story accessible after it expires */
    private bool $post_to_chat_page;
    /** @var bool Pass <em>True</em> if the content of the story must be protected from forwarding and screenshotting */
    private bool $protect_content;

    /**
     * @param Request $request
     * @param string $business_connection_id Unique identifier of the business connection
     * @param InputStoryContent $content Content of the story
     * @param int $active_period Period after which the story is moved to the archive, in seconds; must be one of <code>6 * 3600</code>, <code>12 * 3600</code>, <code>86400</code>, or <code>2 * 86400</code>
     */
    public function __construct(Request $request, string $business_connection_id, InputStoryContent $content, int $active_period)
    {
        $this->_request = $request;
        $this->business_connection_id = $business_connection_id;
        $this->content = $content;
        $this->active_period = $active_period;
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
