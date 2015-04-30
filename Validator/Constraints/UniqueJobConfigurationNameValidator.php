<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\Validator\Constraints;

use Aureja\JobQueue\Model\Manager\JobConfigurationManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 4/30/15 10:28 PM
 */
class UniqueJobConfigurationNameValidator extends ConstraintValidator
{

    /**
     * @var JobConfigurationManagerInterface
     */
    private $configurationManager;

    /**
     * Constructor.
     *
     * @param JobConfigurationManagerInterface $configurationManager
     */
    public function __construct(JobConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null !== $this->configurationManager->findByName($value)) {
            $this->context->addViolation($constraint->message, array('%name%' => $value));
        }
    }
}
