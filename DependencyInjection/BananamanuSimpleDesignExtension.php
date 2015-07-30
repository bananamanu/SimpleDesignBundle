<?php

namespace Bananamanu\SimpleDesignBundle\DependencyInjection;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BananamanuSimpleDesignExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Loads SimpleDesignBundle configuration.
     *
     * @param ContainerBuilder $container
     */
    public function prepend( ContainerBuilder $container )
    {
        $legacyConfigFile = __DIR__ . '/../Resources/config/legacy_settings.yml';
        $config = Yaml::parse( file_get_contents( $legacyConfigFile ) );
        $container->prependExtensionConfig( 'ez_publish_legacy', $config );
        $container->addResource( new FileResource( $legacyConfigFile ) );

        $designConfigFile = __DIR__ . '/../Resources/config/simpledesign.yml';
        $config = Yaml::parse(file_get_contents($designConfigFile));
        $container->prependExtensionConfig('ezpublish', $config);
        $container->addResource(new FileResource($designConfigFile));

        $configFile = __DIR__ . '/../Resources/config/image_variations.yml';
        $config = Yaml::parse( file_get_contents( $configFile ) );
        $container->prependExtensionConfig( 'ezpublish', $config );
        $container->addResource( new FileResource( $configFile ) );
    }
}

