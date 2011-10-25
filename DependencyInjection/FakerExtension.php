<?php

/**
 * This file is part of the FakerBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Bazinga\Bundle\FakerBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class FakerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor      = new Processor();
        $configuration  = new Configuration();
        $config         = $processor->processConfiguration($configuration, $configs);

        if (!$container->hasDefinition('faker')) {
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.xml');
        }

        if (isset($config['seed'])) {
            $container
                ->getDefinition('faker.generator')
                ->addMethodCall('seed', array($config['seed']))
                ;
        }

        if (isset($config['populator'])) {
            $container->setParameter('faker.populator.class', $config['populator']);
        }

        foreach ($config['entities'] as $class => $number) {
            $container
                ->getDefinition('faker.populator')
                ->addMethodCall('addEntity', array($class, $number))
                ;
        }
    }
}
