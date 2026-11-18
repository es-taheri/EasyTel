<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents one result of an inline query. Telegram clients currently support results of the following 20 types:
 * @method self inlinequeryresultcachedaudio(InlineQueryResultCachedAudio $value)
 * @method self inlinequeryresultcacheddocument(InlineQueryResultCachedDocument $value)
 * @method self inlinequeryresultcachedgif(InlineQueryResultCachedGif $value)
 * @method self inlinequeryresultcachedmpeg4gif(InlineQueryResultCachedMpeg4Gif $value)
 * @method self inlinequeryresultcachedphoto(InlineQueryResultCachedPhoto $value)
 * @method self inlinequeryresultcachedsticker(InlineQueryResultCachedSticker $value)
 * @method self inlinequeryresultcachedvideo(InlineQueryResultCachedVideo $value)
 * @method self inlinequeryresultcachedvoice(InlineQueryResultCachedVoice $value)
 * @method self inlinequeryresultarticle(InlineQueryResultArticle $value)
 * @method self inlinequeryresultaudio(InlineQueryResultAudio $value)
 * @method self inlinequeryresultcontact(InlineQueryResultContact $value)
 * @method self inlinequeryresultgame(InlineQueryResultGame $value)
 * @method self inlinequeryresultdocument(InlineQueryResultDocument $value)
 * @method self inlinequeryresultgif(InlineQueryResultGif $value)
 * @method self inlinequeryresultlocation(InlineQueryResultLocation $value)
 * @method self inlinequeryresultmpeg4gif(InlineQueryResultMpeg4Gif $value)
 * @method self inlinequeryresultphoto(InlineQueryResultPhoto $value)
 * @method self inlinequeryresultvenue(InlineQueryResultVenue $value)
 * @method self inlinequeryresultvideo(InlineQueryResultVideo $value)
 * @method self inlinequeryresultvoice(InlineQueryResultVoice $value)
 */
class InlineQueryResult
{
    protected const _UNION_BASE = true;
    /** @var InlineQueryResultCachedAudio */
    public InlineQueryResultCachedAudio $inlinequeryresultcachedaudio;
    /** @var InlineQueryResultCachedDocument */
    public InlineQueryResultCachedDocument $inlinequeryresultcacheddocument;
    /** @var InlineQueryResultCachedGif */
    public InlineQueryResultCachedGif $inlinequeryresultcachedgif;
    /** @var InlineQueryResultCachedMpeg4Gif */
    public InlineQueryResultCachedMpeg4Gif $inlinequeryresultcachedmpeg4gif;
    /** @var InlineQueryResultCachedPhoto */
    public InlineQueryResultCachedPhoto $inlinequeryresultcachedphoto;
    /** @var InlineQueryResultCachedSticker */
    public InlineQueryResultCachedSticker $inlinequeryresultcachedsticker;
    /** @var InlineQueryResultCachedVideo */
    public InlineQueryResultCachedVideo $inlinequeryresultcachedvideo;
    /** @var InlineQueryResultCachedVoice */
    public InlineQueryResultCachedVoice $inlinequeryresultcachedvoice;
    /** @var InlineQueryResultArticle */
    public InlineQueryResultArticle $inlinequeryresultarticle;
    /** @var InlineQueryResultAudio */
    public InlineQueryResultAudio $inlinequeryresultaudio;
    /** @var InlineQueryResultContact */
    public InlineQueryResultContact $inlinequeryresultcontact;
    /** @var InlineQueryResultGame */
    public InlineQueryResultGame $inlinequeryresultgame;
    /** @var InlineQueryResultDocument */
    public InlineQueryResultDocument $inlinequeryresultdocument;
    /** @var InlineQueryResultGif */
    public InlineQueryResultGif $inlinequeryresultgif;
    /** @var InlineQueryResultLocation */
    public InlineQueryResultLocation $inlinequeryresultlocation;
    /** @var InlineQueryResultMpeg4Gif */
    public InlineQueryResultMpeg4Gif $inlinequeryresultmpeg4gif;
    /** @var InlineQueryResultPhoto */
    public InlineQueryResultPhoto $inlinequeryresultphoto;
    /** @var InlineQueryResultVenue */
    public InlineQueryResultVenue $inlinequeryresultvenue;
    /** @var InlineQueryResultVideo */
    public InlineQueryResultVideo $inlinequeryresultvideo;
    /** @var InlineQueryResultVoice */
    public InlineQueryResultVoice $inlinequeryresultvoice;

    public function __construct(array $update = [])
    {
        Statics::hydrate($this, $update);
    }

    /**
     * This object represents one result of an inline query. Telegram clients currently support results of the following 20 types:
     * @param InlineQueryResultCachedAudio|null $inlinequeryresultcachedaudio
     * @param InlineQueryResultCachedDocument|null $inlinequeryresultcacheddocument
     * @param InlineQueryResultCachedGif|null $inlinequeryresultcachedgif
     * @param InlineQueryResultCachedMpeg4Gif|null $inlinequeryresultcachedmpeg4gif
     * @param InlineQueryResultCachedPhoto|null $inlinequeryresultcachedphoto
     * @param InlineQueryResultCachedSticker|null $inlinequeryresultcachedsticker
     * @param InlineQueryResultCachedVideo|null $inlinequeryresultcachedvideo
     * @param InlineQueryResultCachedVoice|null $inlinequeryresultcachedvoice
     * @param InlineQueryResultArticle|null $inlinequeryresultarticle
     * @param InlineQueryResultAudio|null $inlinequeryresultaudio
     * @param InlineQueryResultContact|null $inlinequeryresultcontact
     * @param InlineQueryResultGame|null $inlinequeryresultgame
     * @param InlineQueryResultDocument|null $inlinequeryresultdocument
     * @param InlineQueryResultGif|null $inlinequeryresultgif
     * @param InlineQueryResultLocation|null $inlinequeryresultlocation
     * @param InlineQueryResultMpeg4Gif|null $inlinequeryresultmpeg4gif
     * @param InlineQueryResultPhoto|null $inlinequeryresultphoto
     * @param InlineQueryResultVenue|null $inlinequeryresultvenue
     * @param InlineQueryResultVideo|null $inlinequeryresultvideo
     * @param InlineQueryResultVoice|null $inlinequeryresultvoice
     */
    public static function make(InlineQueryResultCachedAudio $inlinequeryresultcachedaudio = null, InlineQueryResultCachedDocument $inlinequeryresultcacheddocument = null, InlineQueryResultCachedGif $inlinequeryresultcachedgif = null, InlineQueryResultCachedMpeg4Gif $inlinequeryresultcachedmpeg4gif = null, InlineQueryResultCachedPhoto $inlinequeryresultcachedphoto = null, InlineQueryResultCachedSticker $inlinequeryresultcachedsticker = null, InlineQueryResultCachedVideo $inlinequeryresultcachedvideo = null, InlineQueryResultCachedVoice $inlinequeryresultcachedvoice = null, InlineQueryResultArticle $inlinequeryresultarticle = null, InlineQueryResultAudio $inlinequeryresultaudio = null, InlineQueryResultContact $inlinequeryresultcontact = null, InlineQueryResultGame $inlinequeryresultgame = null, InlineQueryResultDocument $inlinequeryresultdocument = null, InlineQueryResultGif $inlinequeryresultgif = null, InlineQueryResultLocation $inlinequeryresultlocation = null, InlineQueryResultMpeg4Gif $inlinequeryresultmpeg4gif = null, InlineQueryResultPhoto $inlinequeryresultphoto = null, InlineQueryResultVenue $inlinequeryresultvenue = null, InlineQueryResultVideo $inlinequeryresultvideo = null, InlineQueryResultVoice $inlinequeryresultvoice = null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => $v !== null);
        return new self($updates);
    }

    public function __call(string $name, array $arguments)
    {
        $this->{$name} = array_shift($arguments);
        return $this;
    }

    protected function _output(int $type = Telegram::OUTPUT_JSON): object|array|string
    {
        $output = [];
        $r = new \ReflectionClass(static::class);
        $union = $r->hasConstant('_UNION_BASE');

        $normalize = function ($value) use (&$normalize) {
            if (is_object($value) && method_exists($value, '_output'))
                return (fn() => $this->_output(Telegram::OUTPUT_ARRAY))->bindTo($value, $value)();
            if (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item)
                    $items[$key] = $normalize($item);
                return $items;
            }
            return $value;
        };

        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (!isset($this->{$name}))
                continue;

            $raw = $property->getValue($this);
            $value = $normalize($raw);

            // A union base variant property represents the payload itself, so
            // its fields replace the variant key.
            if ($union && is_object($raw) && is_array($value)
                && $name === strtolower((new \ReflectionClass($raw))->getShortName())):
                $output += $value;
                continue;
            endif;

            $output[$name] = $value;
        endforeach;
        return Statics::output($output, $type);
    }
}