<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Service;

use Symfony\Component\Validator\Constraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\CompareConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\RequiredConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\NumericalConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\LengthConstraint;
use wooppay\YiiToSymfonyValidatorsBundle\Validator\MatchConstraint;

class Converter
{
    public function toSymfonyValidator(string $type, array $params): Constraint
    {
        switch ($type) {
            case 'compare':
                return new CompareConstraint($params);
            case 'required':
                return new RequiredConstraint($params);
            case 'numerical':
                return new NumericalConstraint($params);
            case 'length':
                return new LengthConstraint($params);
            case 'match':
                return new MatchConstraint($params);
        }
    }
}