<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\StudioApiBundle\DependencyInjection;

use Pimcore\Bundle\StudioApiBundle\Exception\InvalidPathException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public const ROOT_NODE = 'pimcore_studio_api';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NODE);

        $rootNode = $treeBuilder->getRootNode();
        $rootNode->addDefaultsIfNotSet();
        $rootNode->children()
            ->arrayNode('openApiScanPaths')
                ->prototype('scalar')->end()
                ->validate()
                ->always(function ($paths) {
                    foreach ($paths as $path) {
                        if (!is_dir($path)) {
                            throw new InvalidPathException(sprintf('The path "%s" is not a valid directory.', $path));
                        }
                    }

                    return $paths;
                })
                ->end()
            ->end()
            ->arrayNode('api_token')
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('lifetime')
                        ->defaultValue(3600)
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}