<?php


namespace App\Modules\Auth\Structures;


use App\Exceptions\InvalidNewUserPropertiesException;

class NewUserStructure
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';
    public array $additionalData = [];

    public static function fromArray(array $newUserArray): self
    {
        try {
            $obj = new self;
            $obj->name = $newUserArray['name'];
            $obj->email = $newUserArray['email'];
            $obj->password = $newUserArray['password'];
            $obj->role = $newUserArray['role'];
            $obj->additionalData = $newUserArray['additionalData'] ?? [];

            return $obj;
        } catch (\Exception $e) {
            throw new InvalidNewUserPropertiesException('Something went wrong');
        }
    }
}
