<?php


namespace SpExt\ApiWrapper\Http;


use Symfony\Component\Validator\Constraint;

interface IRules
{
    public static function create(array $constraints = [], IRules $previousConstraints = null);

    /**
     * @return Constraint[]
     */
    public function toArray();

    /**
     * @param string $name
     * @param Constraint|Constraint[] $rule
     */
    public function addRule($name, $rule);

    public function removeRule($name);

    public function addConstraintToRule($name, Constraint $constraint);
}
