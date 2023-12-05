<?php

namespace EasyTel\types;

use Nette\Utils\Json;

class InputMediaVideo
{
    public string $type;
    public string $media;
    public mixed $thumbnail;
    public string $caption;
    public string $parse_mode;
    public Json|string  $caption_entities;
    public int $width;
    public int $height;
    public int $duration;
    public bool $supports_streaming;
    public bool $has_spoiler;
    
    public function __construct(array $update)
    {
        $objects = array_keys($update);
        foreach ($objects as $object):
            $this->{$object} = $update[$object];
        endforeach;
        
    }
}