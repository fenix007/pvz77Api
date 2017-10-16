<?php

namespace SpExt\Pvz\Model;

use SpExt\ApiWrapper\Http\Rules;
use SpExt\ApiWrapper\Model\AbstractModel;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParcelPerson extends AbstractModel
{
    /** @var  string */
    public $nick;

    /** @var  string */
    public $name;

    /** @var  string */
    public $phone;

    public static function rules()
    {
        return Rules::create([
            'nick'  => [new NotBlank()],
            'name'  => [new NotBlank(), new Length(['min' => 3])],
            'phone' => [new NotBlank(), new Length(['min' => 7, 'max' => 12])]
        ]);
    }

    public static function createFromParcelPerson(ParcelPerson $person, array $data = [])
    {
        $data = array_merge($person->toArray(), $data);

        return static::create($data);
    }
}
