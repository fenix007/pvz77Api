<?php


namespace SpExt\ApiWrapper\Http;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface IRequestObjectHandler
{
    public function fillFromRequest(Request $request, IRequestObject $requestObject);

    public function fillFromData(array $data, IRequestObject $requestObject);

//    public function fillFromConsoleInput(InputInterface $input, IRequestObject $requestObject);

    /**
     * @param IRequestObject $requestObject
     * @param bool $throwable
     * @param array|null $groups
     * @throws RequestObjectValidationException
     *
     * @return null|ConstraintViolationListInterface
     */
    public function validate(IRequestObject $requestObject, $throwable = false, array $groups = null);
}
