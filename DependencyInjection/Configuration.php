<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\DependencyInjection;

use EveryWorkflow\EavBundle\Attribute\FieldMapper\CurrencyMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\DateMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\DateTimeMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\MultiSelectMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\NumberMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\SelectMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\SwatchMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\LongTextMapper;
use EveryWorkflow\EavBundle\Attribute\FieldMapper\TextMapper;
use EveryWorkflow\EavBundle\Attribute\Type\CurrencyAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\DateAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\DateTimeAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\MultiSelectAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\NumberAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\SelectAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\SwatchAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\LongTextAttribute;
use EveryWorkflow\EavBundle\Attribute\Type\TextAttribute;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('eav');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('default')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_class')->defaultValue('text')->end()
                        ->scalarNode('attribute_class')->defaultValue('text')->end()
                        ->scalarNode('attribute_field_mapper_class')->defaultValue('text')->end()
                        ->scalarNode('attribute_type')->defaultValue('text')->end()
                        ->scalarNode('attribute_field')->defaultValue('text_field')->end()
                    ->end()
                ->end()
                ->arrayNode('entity_types')
                    ->useAttributeAsKey('type')
                    ->scalarPrototype()
                    ->end()
                ->end()
                ->arrayNode('attribute_types')
                    ->useAttributeAsKey('type')
                    ->scalarPrototype()
                    ->end()
                ->end()
                ->arrayNode('attribute_type_to_form_field_mapper')
                    ->useAttributeAsKey('type')
                    ->scalarPrototype()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
