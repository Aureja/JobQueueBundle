<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Aureja\Bundle\JobQueueBundle\Form\Type;

use Aureja\Bundle\JobQueueBundle\Exception\Exception;

/**
 * Class JobFactoryTypeMap
 *
 * @package Aureja\Bundle\JobQueueBundle\Form\Type
 */
final class JobFactoryTypeMap
{
    /**
     * @var array
     */
    private static $types = [
        'aureja_php_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\PhpJobFactoryType',
        'aureja_shell_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\ShellJobFactoryType',
        'aureja_symfony_command_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\SymfonyCommandJobFactoryType',
        'aureja_job_configuration_form_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\SymfonyServiceJobFactoryType',
    ];

    /**
     * @param string $name
     *
     * @return string
     * @throws Exception
     */
    public static function getType($name)
    {
        if (false === isset(self::$types[$name])) {
            throw new Exception('Cannot map factory type');
        }

        return self::$types[$name];
    }
}
