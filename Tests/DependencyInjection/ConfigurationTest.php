<?php

namespace Gintko\Swiftmailer\SparkpostBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;

/**
 * Class ConfigurationTest
 *
 * @author Gintas Kovalevskis <gintaskov@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "api_key" at path "gintko_swiftmailer_sparkpost" must be configured.
     */
    public function it_should_throw_exception_if_required_api_key_is_not_set()
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $processor->processConfiguration($configuration, []);
    }

    /**
     * @test
     */
    public function it_should_set_default_configuration_values_if_not_specified()
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, [['api_key' => 'test']]);

        $expected = [
            'api_key' => 'test',
            'ip_pool_probability' => 1.0,
            'recipient_override' => [
                'email' => null,
                'gmail_style' => false,
            ],
            'message_options' => [
                'transactional' => true,
                'open_tracking' => false,
                'click_tracking' => false,
                'sandbox' => false,
                'skip_suppression' => false,
                'inline_css' => false,
                'ip_pool' => null,
            ]
        ];

        $this->assertEquals($expected, $config);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The value -1 is too small for path "gintko_swiftmailer_sparkpost.ip_pool_probability". Should be greater than or equal to 0
     */
    public function it_should_throw_exception_if_ip_pool_probability_is_less_than_zero()
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = [
            'api_key' => 'test',
            'ip_pool_probability' => -1,
        ];

        $processor->processConfiguration($configuration, [$config]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The value 2 is too big for path "gintko_swiftmailer_sparkpost.ip_pool_probability". Should be less than or equal to 1
     */
    public function it_should_throw_exception_if_ip_pool_probability_is_greater_than_one()
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = [
            'api_key' => 'test',
            'ip_pool_probability' => 2,
        ];

        $processor->processConfiguration($configuration, [$config]);
    }
}