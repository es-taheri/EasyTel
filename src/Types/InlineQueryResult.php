<?php

namespace EasyTel\Types;

use EasyTel\Helper\Statics;
use EasyTel\Telegram;
use JSON\json;

/**
 * This object represents one result of an inline query. Telegram clients currently support results of the following 20 types:

 */
class InlineQueryResult
{
    public InlineQueryResultCachedAudio $inlinequeryresultcachedaudio;
    public InlineQueryResultCachedDocument $inlinequeryresultcacheddocument;
    public InlineQueryResultCachedGif $inlinequeryresultcachedgif;
    public InlineQueryResultCachedMpeg4Gif $inlinequeryresultcachedmpeg4gif;
    public InlineQueryResultCachedPhoto $inlinequeryresultcachedphoto;
    public InlineQueryResultCachedSticker $inlinequeryresultcachedsticker;
    public InlineQueryResultCachedVideo $inlinequeryresultcachedvideo;
    public InlineQueryResultCachedVoice $inlinequeryresultcachedvoice;
    public InlineQueryResultArticle $inlinequeryresultarticle;
    public InlineQueryResultAudio $inlinequeryresultaudio;
    public InlineQueryResultContact $inlinequeryresultcontact;
    public InlineQueryResultGame $inlinequeryresultgame;
    public InlineQueryResultDocument $inlinequeryresultdocument;
    public InlineQueryResultGif $inlinequeryresultgif;
    public InlineQueryResultLocation $inlinequeryresultlocation;
    public InlineQueryResultMpeg4Gif $inlinequeryresultmpeg4gif;
    public InlineQueryResultPhoto $inlinequeryresultphoto;
    public InlineQueryResultVenue $inlinequeryresultvenue;
    public InlineQueryResultVideo $inlinequeryresultvideo;
    public InlineQueryResultVoice $inlinequeryresultvoice;

    public function __construct(array $update = [])
    {
        $objects = array_keys($update);
        $r = new \ReflectionClass(static::class);
        foreach ($objects as $object):
            if ($r->hasProperty($object)):
                $prop = $r->getProperty($object);
                $type = $prop->getType();
                if (in_array(strtolower(trim($type)), ['string', 'true', 'false', 'bool', 'int', 'float', 'array', 'mixed']) || str_contains($type, '|'))
                    $this->{$object} = $update[$object];
            endif;
        endforeach;
        $this->inlinequeryresultcachedaudio = new InlineQueryResultCachedAudio($update);
        $this->inlinequeryresultcacheddocument = new InlineQueryResultCachedDocument($update);
        $this->inlinequeryresultcachedgif = new InlineQueryResultCachedGif($update);
        $this->inlinequeryresultcachedmpeg4gif = new InlineQueryResultCachedMpeg4Gif($update);
        $this->inlinequeryresultcachedphoto = new InlineQueryResultCachedPhoto($update);
        $this->inlinequeryresultcachedsticker = new InlineQueryResultCachedSticker($update);
        $this->inlinequeryresultcachedvideo = new InlineQueryResultCachedVideo($update);
        $this->inlinequeryresultcachedvoice = new InlineQueryResultCachedVoice($update);
        $this->inlinequeryresultarticle = new InlineQueryResultArticle($update);
        $this->inlinequeryresultaudio = new InlineQueryResultAudio($update);
        $this->inlinequeryresultcontact = new InlineQueryResultContact($update);
        $this->inlinequeryresultgame = new InlineQueryResultGame($update);
        $this->inlinequeryresultdocument = new InlineQueryResultDocument($update);
        $this->inlinequeryresultgif = new InlineQueryResultGif($update);
        $this->inlinequeryresultlocation = new InlineQueryResultLocation($update);
        $this->inlinequeryresultmpeg4gif = new InlineQueryResultMpeg4Gif($update);
        $this->inlinequeryresultphoto = new InlineQueryResultPhoto($update);
        $this->inlinequeryresultvenue = new InlineQueryResultVenue($update);
        $this->inlinequeryresultvideo = new InlineQueryResultVideo($update);
        $this->inlinequeryresultvoice = new InlineQueryResultVoice($update);
    }

    
    public static function make(InlineQueryResultCachedAudio $inlinequeryresultcachedaudio=null, InlineQueryResultCachedDocument $inlinequeryresultcacheddocument=null, InlineQueryResultCachedGif $inlinequeryresultcachedgif=null, InlineQueryResultCachedMpeg4Gif $inlinequeryresultcachedmpeg4gif=null, InlineQueryResultCachedPhoto $inlinequeryresultcachedphoto=null, InlineQueryResultCachedSticker $inlinequeryresultcachedsticker=null, InlineQueryResultCachedVideo $inlinequeryresultcachedvideo=null, InlineQueryResultCachedVoice $inlinequeryresultcachedvoice=null, InlineQueryResultArticle $inlinequeryresultarticle=null, InlineQueryResultAudio $inlinequeryresultaudio=null, InlineQueryResultContact $inlinequeryresultcontact=null, InlineQueryResultGame $inlinequeryresultgame=null, InlineQueryResultDocument $inlinequeryresultdocument=null, InlineQueryResultGif $inlinequeryresultgif=null, InlineQueryResultLocation $inlinequeryresultlocation=null, InlineQueryResultMpeg4Gif $inlinequeryresultmpeg4gif=null, InlineQueryResultPhoto $inlinequeryresultphoto=null, InlineQueryResultVenue $inlinequeryresultvenue=null, InlineQueryResultVideo $inlinequeryresultvideo=null, InlineQueryResultVoice $inlinequeryresultvoice=null): self
    {
        $args = get_defined_vars();
        $updates = array_filter($args, fn($v) => isset($v));
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
        foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $property):
            $name = $property->getName();
            if (isset($this->{$name})):
                $value = $property->getValue($this);
                $property_type = $property->getType();
                if ($property_type == 'object')
                    $output[$name] = (fn() => ($this->_output($type)))->bindTo($value, $value)();
                else
                    $output[$name] = $value;
            endif;
        endforeach;
        return Statics::output($output, $type);
    }
}