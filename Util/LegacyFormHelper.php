<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\Util;

use Aureja\Bundle\JobQueueBundle\Exception\InvalidArgumentException;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 3/5/16 9:46 AM
 */
final class LegacyFormHelper
{
    private static $map = [
        'Aureja\Bundle\JobQueueBundle\Form\Type\JobConfigurationType' => 'aureja_job_configuration',
        'Aureja\Bundle\JobQueueBundle\Form\Type\PhpJobFactoryType' => 'aureja_php_job_factory',
        'Aureja\Bundle\JobQueueBundle\Form\Type\ShellJobFactoryType' => 'aureja_shell_job_factory',
        'Aureja\Bundle\JobQueueBundle\Form\Type\SymfonyCommandJobFactoryType' => 'aureja_symfony_command_job_factory',
        'Aureja\Bundle\JobQueueBundle\Form\Type\SymfonyServiceJobFactoryType' => 'aureja_symfony_service_job_factory',
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType' => 'checkbox',
        'Symfony\Component\Form\Extension\Core\Type\ChoiceType' => 'choice',
        'Symfony\Component\Form\Extension\Core\Type\IntegerType' => 'integer',
        'Symfony\Component\Form\Extension\Core\Type\SubmitType' => 'submit',
        'Symfony\Component\Form\Extension\Core\Type\TextType' => 'text',
        'Symfony\Component\Form\Extension\Core\Type\TextareaType' => 'textarea',
    ];

    /**
     * @param string $class
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function getType($class)
    {
        if (false === self::isLegacy()) {
            return $class;
        }

        if (false === isset(self::$map[$class])) {
            throw InvalidArgumentException::create(
                sprintf('Form type with class "%s" can not be found. Please check for typos or add it to the map in LegacyFormHelper', $class)
            );
        }

        return self::$map[$class];
    }

    /**
     * @return bool
     */
    public static function isLegacy()
    {
        return !method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix');
    }
}
