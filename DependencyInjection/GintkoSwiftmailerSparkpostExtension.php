<?php

namespace Gintko\Swiftmailer\SparkpostBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GintkoSwiftmailerSparkpostExtension
 *
 * @author Gintas Kovalevskis <gintaskov@gmail.com>
 */
class GintkoSwiftmailerSparkpostExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $transportClass = $container->getParameter('gintko.swiftmailer.transport.sparkpost.class');
        $configClass = $container->getParameter('gintko.swiftmailer.transport.sparkpost.configuration.class');

        // register configuration class
        $container
            ->register('gintko.swiftmailer.transport.sparkpost.configuration', $configClass)
            ->setFactory([$configClass, 'newInstance'])
            ->addMethodCall('setRecipientOverride', [$config['recipient_override']['email']])
            ->addMethodCall('setOverrideGmailStyle', [$config['recipient_override']['gmail_style']])
            ->addMethodCall('setOptions', [$config['message_options']])
            ->addMethodCall('setIpPoolProbability', [$config['ip_pool_probability']])
        ;

        // register transport class
        $container
            ->register('gintko.swiftmailer.transport.sparkpost', $transportClass)
            ->addArgument($config['api_key'])
            ->addArgument(new Reference('gintko.swiftmailer.transport.sparkpost.configuration'))
            ->setFactory([$transportClass, 'newInstance'])
        ;

        // set alias for sparkpost swiftmailer transport
        $container->setAlias('swiftmailer.mailer.transport.sparkpost', 'gintko.swiftmailer.transport.sparkpost');
    }
}