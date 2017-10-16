<?php

namespace SpExt\Pvz\Model;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParcelRecipient extends ParcelPerson
{
    /** @var  string */
    public $email;

    /** @var  string */
    public $address;

    /** @var string Prefix for array conversion */
    protected $prefix = 'recipient_';

    public static function rules()
    {
        $rules = parent::rules();

        $rules->addRule('email', [new NotBlank(), new Email()]);

        return $rules;
    }
}
