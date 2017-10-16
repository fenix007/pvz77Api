<?php


namespace SpExt\ApiWrapper\Http;


use Symfony\Component\Validator\Constraint;

class Rules implements IRules
{
    private $constraints;

    private function __construct(array $constraints = [], IRules $previousConstraints = null)
    {
        if ($previousConstraints) {
            $constraints = array_merge($previousConstraints->toArray(), $constraints);
        }

        $this->constraints = $constraints;
    }

    public static function create(array $constraints = [], IRules $previousConstraints = null)
    {
        return new self($constraints, $previousConstraints);
    }

    public function toArray()
    {
        return $this->constraints;
    }

    /**
     * @param string $name
     * @param Constraint|Constraint[] $rule
     */
    public function addRule($name, $rule)
    {
        $this->constraints[$name] = $rule;
    }

    public function removeRule($name)
    {
        if (!isset($this->constraints[$name])) {
            return;
        }

        unset($this->constraints[$name]);
    }

    public function addConstraintToRule($name, Constraint $constraint)
    {
        if (!isset($this->constraints[$name])) {
            $this->constraints[$name] = [];
        } else if (!is_array($this->constraints[$name])) {
            $this->constraints[$name] = [$this->constraints[$name]];
        }

        $this->constraints[$name][] = $constraint;
    }
}
