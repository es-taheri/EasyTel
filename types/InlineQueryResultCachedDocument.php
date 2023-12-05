<?php

namespace EasyTel\types;

use Nette\Utils\Json;

class InlineQueryResultCachedDocument
{
    public string $type;
    public string $id;
    public string $title;
    public string $document_file_id;
    public string $description;
    public string $caption;
    public string $parse_mode;
    public Json|string  $caption_entities;
    public Json|string $reply_markup;
    public InputMessageContent $input_message_content;
    
    public function __construct(array $update)
    {
        $objects = array_keys($update);
        foreach ($objects as $object):
            $this->{$object} = $update[$object];
        endforeach;
        if (isset($update['input_message_content'])) $this->input_message_content = new InputMessageContent($update['input_message_content']);
    }
}