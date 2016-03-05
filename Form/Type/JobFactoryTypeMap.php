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

use Aureja\Bundle\JobQueueBundle\Exception\InvalidArgumentException;
use Aureja\Bundle\JobQueueBundle\Util\LegacyFormHelper;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 3/5/16 10:23 AM
 */
class JobFactoryTypeMap 
{
    private static $map = [
        'aureja_php_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\PhpJobFactoryType',
        'aureja_shell_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\ShellJobFactoryType',
        'aureja_symfony_command_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\SymfonyCommandJobFactoryType',
        'aureja_symfony_service_job_factory' => 'Aureja\Bundle\JobQueueBundle\Form\Type\SymfonyServiceJobFactoryType',
    ];

    /**
     * @param string $factoryName
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function getType($factoryName)
    {
        if (false === isset(self::$map[$factoryName])) {
            throw InvalidArgumentException::create( sprintf('Not found job factory %s type', $factoryName));
        }

        if (LegacyFormHelper::isLegacy()) {
            return $factoryName;
        }

        return self::$map[$factoryName];
    }
}
