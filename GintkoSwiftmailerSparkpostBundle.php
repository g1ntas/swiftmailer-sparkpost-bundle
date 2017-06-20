<?php

namespace Gintko\Swiftmailer\SparkpostBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SwiftmailerSparkpostBundle
 *
 * @author Gintas Kovalevskis <gintaskov@gmail.com>
 */
class GintkoSwiftmailerSparkpostBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // register sparkpost message instance
        \Swift_DependencyContainer::getInstance()
            ->register('message.sparkpost')
            ->asNewInstanceOf('SwiftSparkPost\Message')
        ;
    }
}