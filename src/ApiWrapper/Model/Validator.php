<?php

namespace SpExt\ApiWrapper\Model;

use SpExt\ApiWrapper\Http\RequestObjectHandler;
use SpExt\ApiWrapper\Http\Rules;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\ValidatorBuilder;

trait Validator
{
    public static function rules()
    {
        return Rules::create();
    }

    public function validate($throwable = false)
    {
        $objectHandler = new RequestObjectHandler(new PropertyAccessor(), (new ValidatorBuilder())->getValidator());

        $errors = $objectHandler->validate($this, $throwable);

        foreach ($this->toArray(null, false) as $element) {
            if ($element instanceof AbstractModel) {
                $errors->addAll($element->validate());
            }
        }

        return $errors;
    }
}
