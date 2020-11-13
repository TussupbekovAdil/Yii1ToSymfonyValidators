<?php

namespace wooppay\YiiToSymfonyValidatorsBundle\Validator;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;

class DateConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DateConstraint) {
            throw new UnexpectedTypeException($constraint, DateConstraint::class);
        }

        $params = $constraint->getParams();

        if (!filter_var($params['allowEmpty'], FILTER_VALIDATE_BOOLEAN) && empty($value)) {
            $this->addViolation($constraint, 'emptyValue');
        } else {
            try {
                $dateObject = new \DateTime($value);
                $formatter = $this->getFormatter($params['format']);
                $parsedValue = $formatter->format($dateObject);

                if ($value != $parsedValue) {
                    $this->addViolation($constraint, 'invalidDateFormat', $params['format']);
                }
            } catch (\Exception $e) {
                $this->addViolation($constraint, 'invalidDateFormat', $params['format']);   
            }
        }
    }

    private function addViolation(Constraint $constraint, string $text, string $value = null)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $this->translator->trans($text, [
                (!$value) ? null : '%value%' => $value
            ], 'validation'))
            ->addViolation()
        ;
    }

    private function getFormatter(string $format)
    {
        return IntlDateFormatter::create(null, null, null, null, null, $format);
    }
}