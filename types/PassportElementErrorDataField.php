<?php

namespace EasyTel\types;

use Nette\Utils\Json;

class PassportElementErrorDataField
{
    public string $source;
    public string $type;
    public string $field_name;
    public string $data_hash;
    public string $message;
    
    public function __construct(array $update)
    {
        $objects = array_keys($update);
        foreach ($objects as $object):
            $this->{$object} = $update[$object];
        endforeach;
        
    }
}