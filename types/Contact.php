<?php

namespace EasyTel\types;

use Nette\Utils\Json;

class Contact
{
    public string $phone_number;
    public string $first_name;
    public string $last_name;
    public int $user_id;
    public string $vcard;
    
    public function __construct(array $update)
    {
        $objects = array_keys($update);
        foreach ($objects as $object):
            $reflection = new \ReflectionClass(__CLASS__);
            $property = $reflection->getProperty($object);
            $type = $property->gettype()->getName();
            if (in_array(strtolower($type), ['bool', 'int', 'string', 'array', 'true', 'object', 'json|string','float']))
                $this->{$object} = $update[$object];
        endforeach;
    }
}