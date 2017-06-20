<?php

namespace Gintko\Swiftmailer\SparkpostBundle\Tests;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FunctionalTest
 *
 * @author Gintas Kovalevskis <gintaskov@gmail.com>
 */
class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestKernel
     */
    private $kernel;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->kernel = new TestKernel(uniqid(), true);

        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($this->kernel->getCacheDir());
        $this->filesystem->mkdir($this->kernel->getLogDir());
    }

    public function tearDown()
    {
        $this->kernel->shutdown();
        $this->filesystem->remove($this->kernel->getCacheRootDir());
        $this->filesystem->remove($this->kernel->getLogDir());
    }

    /**
     * @test
     */
    public function services_are_registered()
    {
        $this->loadConfig('basic.yml');

        $container = $this->kernel->getContainer();

        $this->assertTrue($container->has('gintko.swiftmailer.transport.sparkpost'), 'The transport for SparkPost is available.');

        // transport
        $transport = $container->get('gintko.swiftmailer.transport.sparkpost');

        $this->assertInstanceof('Swift_Transport', $transport);
        $this->assertInstanceof('SwiftSparkPost\Transport', $transport);

        // sparkpost configuration
        $this->assertTrue($container->has('gintko.swiftmailer.transport.sparkpost.configuration'), 'The configuration for SparkPost is available.');

        $config = $container->get('gintko.swiftmailer.transport.sparkpost.configuration');

        $this->assertInstanceof('SwiftSparkPost\Configuration', $config);

        // transport alias
        $this->assertTrue($container->has('swiftmailer.mailer.transport.sparkpost'), 'The transport alias for SparkPost is available.');

        $transportAlias = $container->get('swiftmailer.mailer.transport.sparkpost');

        $this->assertInstanceof('SwiftSparkPost\Transport', $transportAlias);
        $this->assertSame($transport, $transportAlias);
    }

    /**
     * @test
     */
    public function configuration_has_set_correct_settings()
    {
        $this->loadConfig('basic.yml');

        $container = $this->kernel->getContainer();

        $config = $container->get('gintko.swiftmailer.transport.sparkpost.configuration');

        $this->assertEquals('test@test.com', $config->getRecipientOverride());
        $this->assertEquals(true, $config->overrideGmailStyle());
        $this->assertEquals(0.5, $config->getIpPoolProbability());

        $this->assertEquals(false, $config->getOptions()['transactional']);
        $this->assertEquals(true, $config->getOptions()['open_tracking']);
        $this->assertEquals(true, $config->getOptions()['click_tracking']);
        $this->assertEquals(true, $config->getOptions()['sandbox']);
        $this->assertEquals(true, $config->getOptions()['skip_suppression']);
        $this->assertEquals(true, $config->getOptions()['inline_css']);
        $this->assertEquals('ip-pool', $config->getOptions()['ip_pool']);
    }

    /**
     * @test
     */
    public function it_works_with_swiftmailer_bundle()
    {
        $this->kernel->registerBundle(new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle());

        $this->loadConfig('swiftmailer_integration.yml');

        $container = $this->kernel->getContainer();

        $this->assertTrue($container->has('mailer'));

        $mailer = $container->get('mailer');

        $this->assertInstanceOf('Swift_Mailer', $mailer);
        $this->assertInstanceOf('SwiftSparkPost\Transport', $mailer->getTransport());

        $message = $mailer->createMessage('sparkpost');

        $this->assertInstanceOf('SwiftSparkPost\Message', $message);
    }

    /**
     * @param string $filename
     */
    private function loadConfig($filename)
    {
        $this->kernel->setConfigurationFilename(__DIR__ . '/fixtures/' . $filename);
        $this->kernel->boot();
    }
}