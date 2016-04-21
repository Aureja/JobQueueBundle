<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AurejaJobQueueExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('commands.xml');
        $loader->load('controllers.xml');
        $loader->load('form-handlers.xml');
        $loader->load('form-subscribers.xml');
        $loader->load('form-types.xml');
        $loader->load('forms.xml');
        $loader->load('handlers.xml');
        $loader->load('jobs.xml');
        $loader->load('services.xml');

        if (!in_array(strtolower($config['db_driver']), ['mongodb', 'orm'])) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load('db_driver/' . sprintf('%s.xml', $config['db_driver']));

        $container->setAlias($this->getAlias() . '.manager.job_configuration', $config['job_configuration_manager']);
        $container->setAlias($this->getAlias() . '.manager.job_report', $config['job_report_manager']);

        $container->setParameter($this->getAlias() . '.model.job_configuration.class', $config['class']['model']['job_configuration']);
        $container->setParameter($this->getAlias() . '.model.job_report.class', $config['class']['model']['job_report']);
        $container->setParameter($this->getAlias() . '.queues', $config['queues']);
        $container->setParameter($this->getAlias() . '.reset.timeout', $config['reset']['timeout']);
        $container->setParameter($this->getAlias() . '.console_dir', $config['console_dir']);
    }
}
