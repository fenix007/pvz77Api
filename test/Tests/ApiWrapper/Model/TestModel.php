<?php

namespace Tests\ApiWrapper\Model;

use SpExt\ApiWrapper\Http\Rules;
use SpExt\ApiWrapper\Model\AbstractModel;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestModel extends AbstractModel
{
    public $title   = 'test';

    public $message = '123';

    public static function rules()
    {
        return Rules::create([
            'title'   => [new NotBlank()],
            'message' => [new NotBlank(), new Length(['min' => 3])]
        ]);
    }

    public function testArray()
    {
        return [
            'title'   => $this->title,
            'message' => $this->message
        ];
    }

    public function testArrayWithPrefix($prefix)
    {
        return [
            $prefix . 'title'   => $this->title,
            $prefix . 'message' => $this->message
        ];
    }
}
