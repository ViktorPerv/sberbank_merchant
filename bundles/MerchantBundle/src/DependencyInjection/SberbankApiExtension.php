<?php

namespace EMerchant\MerchantBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Класс расширения для внедрения зависимостей
 * Нужен для загрузки конфигурации сервисов в контейнер
 * Описания сервисов пишутся в файлы Resources/config
 * После загрузки сервисы описаны как в config/services.yaml
 * и могут использоваться в контейнере сервисов
 */
class SberbankApiExtension extends Extension
{
    /**
     * Загрузка конфигурационных файлов для бандла
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Загрузка конфигурации сервисов из файлов
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        // Создаем объект Configuration
        $configuration = $this->getConfiguration($configs, $container);
        // Обработка конфигурации бандла
        $config = $this->processConfiguration($configuration, $configs);

        //Инициализация сервисов
        $definition = $container->getDefinition('EMerchant\MerchantBundle\Services\AbstractBaseService');
        $definition->setArgument('$login', $config['login']);
        $definition->setArgument('$password', $config['password']);
        $definition->setArgument('$endpoint', $config['endpoint']);
    }

    /**
     * Генерация произвольного псевдонима (алиаса)
     * Псевдоним используется в файлах конфигурации
     * По умолчанию псевдоним генерится из названия класса вот так:
     * 
     * This convention is to remove the "Extension" postfix from the class
     * name and then lowercase and underscore the result. So:
     *
     *     AcmeHelloExtension
     *
     * becomes
     *
     *     acme_hello
     *
     * @return void
     */
    public function getAlias()
    {
        return 'sberbank';
    }
}
