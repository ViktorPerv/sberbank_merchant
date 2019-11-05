<?php

namespace EMerchant\MerchantBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Класс конфигурации бандла
 */
class Configuration implements ConfigurationInterface
{
    // Структура для всех параметров конфигурации
    // Описываются уровни вложенности, тип и значениме по умолчанию
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sberbank_api');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('login')->isRequired()->end()
            ->scalarNode('password')->isRequired()->end()
            ->scalarNode('endpoint')->isRequired()->end()

            ->end();

        return $treeBuilder;
    }
}
