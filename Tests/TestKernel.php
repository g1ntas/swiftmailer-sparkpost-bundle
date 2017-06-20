<?php

namespace Gintko\Swiftmailer\SparkpostBundle\Tests;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class TestKernel
 */
class TestKernel extends Kernel
{
    /**
     * @var string
     */
    private $configurationFilename;

    /**
     * @var array
     */
    private $runtimeBundles = [];

    /**
     * @return bool|string
     */
    public function getCacheRootDir()
    {
        return realpath($this->getCacheDir() . '/..');
    }

    /**
     * @param $filename
     */
    public function setConfigurationFilename($filename)
    {
        $this->configurationFilename = $filename;
    }

    /**
     * @param object $bundle
     */
    public function registerBundle($bundle)
    {
        $this->runtimeBundles[] = $bundle;
    }

    /**
     * {@inheritDoc}
     */
    public function registerBundles()
    {
        return array_merge([
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Gintko\Swiftmailer\SparkpostBundle\GintkoSwiftmailerSparkpostBundle(),
        ], $this->runtimeBundles);
    }

    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->configurationFilename);
    }
}