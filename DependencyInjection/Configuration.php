<?php

namespace Gintko\Swiftmailer\SparkpostBundle\DependencyInjection;

use SwiftSparkPost\Option;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Gintas Kovalevskis <gintaskov@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gintko_swiftmailer_sparkpost');

        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
                ->floatNode('ip_pool_probability')
                    ->defaultValue(1.0)
                    ->min(0.0)
                    ->max(1.0)
                ->end()
                ->arrayNode('recipient_override')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('email')->defaultNull()->end()
                        ->booleanNode('gmail_style')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('message_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode(Option::TRANSACTIONAL)->defaultTrue()->end()
                        ->booleanNode(Option::OPEN_TRACKING)->defaultFalse()->end()
                        ->booleanNode(Option::CLICK_TRACKING)->defaultFalse()->end()
                        ->booleanNode(Option::SANDBOX)->defaultFalse()->end()
                        ->booleanNode(Option::SKIP_SUPPRESSION)->defaultFalse()->end()
                        ->booleanNode(Option::INLINE_CSS)->defaultFalse()->end()
                        ->scalarNode(Option::IP_POOL)->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}