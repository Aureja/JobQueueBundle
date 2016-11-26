<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Aureja\Bundle\TestFrameworkBundle\AurejaTestFrameworkBundle(),
            new Aureja\Bundle\JobQueueBundle\AurejaJobQueueBundle(),
        ];

        return $bundles;
    }
    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
    }
}
