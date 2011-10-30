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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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

        if (isset($config['locale'])) {
            $container
                ->getDefinition('faker.generator')
                ->setArguments(array($config['locale']))
                ;
        }

        if (isset($config['populator'])) {
            $container->setParameter('faker.populator.class', $config['populator']);
        }

        $i = 0;
        foreach ($config['entities'] as $class => $params) {
            $number = isset($params['number']) ? $params['number'] : 5;

            $container
                ->register('faker.entities.' . $i)
                ->setClass('Faker\ORM\Propel\EntityPopulator')
                ->setArguments(array($class))
                ;

            $formatters = array();
            if (isset($params['custom_formatters'])) {
                $j = 0;
                foreach ($params['custom_formatters'] as $column => $formatter) {
                    $method = $formatter['method'];
                    $parameters = $formatter['parameters'];

                    if (null === $method) {
                        $formatters[$column] = null;
                    } else {
                        $container->setDefinition('faker.entities.' . $i . '.formatters.' . $j, new Definition(
                            'closure',
                            array(new Reference('faker.generator'), $method, $parameters)
                        ))->setFactoryService(
                            'faker.formatter_factory'
                        )->setFactoryMethod(
                            'createClosure'
                        );

                        $formatters[$column] = new Reference('faker.entities.' . $i . '.formatters.' . $j);
                        $j++;
                    }
                }
            }

            $container
                ->getDefinition('faker.populator')
                ->addMethodCall('addEntity', array(new Reference('faker.entities.' . $i), $number, $formatters))
                ;

            $i++;
        }
    }
}
