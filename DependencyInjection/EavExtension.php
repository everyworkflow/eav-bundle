<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\DependencyInjection;

use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Attribute\BaseFieldMapperInterface;
use EveryWorkflow\EavBundle\Entity\BaseEntityInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EavExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws \Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $container->registerForAutoconfiguration(BaseEntityInterface::class)
            ->setShared(false)->setPublic(true);
        $container->registerForAutoconfiguration(BaseAttributeInterface::class)
            ->setShared(false)->setPublic(true);
        $container->registerForAutoconfiguration(BaseFieldMapperInterface::class)
            ->setShared(false)->setPublic(true);
    }

    /**
     * @return void
     */
    public function prepend(ContainerBuilder $container)
    {
        $ymlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $ymlLoader->load('eav.yaml');

        $configs = $container->getExtensionConfig($this->getAlias());
        asort($configs); // Reverse priority -> bundle config then project config
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('eav', $config);
    }
}
