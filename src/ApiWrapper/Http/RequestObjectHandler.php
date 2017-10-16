<?php

namespace SpExt\ApiWrapper\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ValidatorBuilder;

class RequestObjectHandler implements IRequestObjectHandler
{
    private $accessor;

    private $validator;

    public function __construct()
    {
        $this->accessor = new PropertyAccessor();
        $this->validator = (new ValidatorBuilder())->getValidator();
    }

    public function fillFromRequest(Request $request, IRequestObject $requestObject)
    {
        $queryData = $request->query->all();
        $requestData = $request->request->all();
        $files = $request->files->all();

        $bodyData = [];

        $requestContent = $request->getContent();

        if ($requestContent) {
            $bodyData = json_decode($requestContent, true) ?: $bodyData;
        }

        $data = array_merge($queryData, $requestData, $files, $bodyData);

        $this->fillFromData($data, $requestObject);
    }

    public function fillFromData(array $data, IRequestObject $requestObject)
    {
        foreach ($data as $key => $value) {
            if (!$this->accessor->isWritable($requestObject, $key)) {
                continue;
            }

            $this->accessor->setValue($requestObject, $key, $value);
        }
    }

//    public function fillFromConsoleInput(InputInterface $input, IRequestObject $requestObject)
//    {
//        $arguments = $input->getArguments();
//        $options = $input->getOptions();
//
//        $data = array_merge($arguments, $options);
//
//        $this->fillFromData($data, $requestObject);
//    }

    public function validate(IRequestObject $requestObject, $throwable = false, array $groups = null)
    {
        $rules = $requestObject->rules()->toArray();

        $properties = array_keys($rules);

        $values = [];

        foreach ($properties as $property) {
            if (!$this->accessor->isReadable($requestObject, $property)) {
                continue;
            }

            $values[$property] = $this->accessor->getValue($requestObject, $property);
        }

        $errors = $this->validator->validate($values, new Collection($rules), $groups);

        if ($throwable && $errors->count() > 0) {
            throw new RequestObjectValidationException($errors, $requestObject);
        }

        return $errors;
    }
}
