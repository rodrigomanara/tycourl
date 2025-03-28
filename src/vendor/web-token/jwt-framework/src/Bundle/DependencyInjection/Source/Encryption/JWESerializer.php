<?php

declare(strict_types=1);

namespace Jose\Bundle\JoseFramework\DependencyInjection\Source\Encryption;

use Jose\Bundle\JoseFramework\DependencyInjection\Source\Source;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\Encryption\Serializer\JWESerializerManagerFactory;
use Override;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use function sprintf;

final readonly class JWESerializer implements Source
{
    #[Override]
    public function name(): string
    {
        return 'serializers';
    }

    #[Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        foreach ($configs[$this->name()] as $name => $itemConfig) {
            $service_id = sprintf('jose.jwe_serializer.%s', $name);
            $definition = new Definition(JWESerializerManager::class);
            $definition
                ->setFactory([new Reference(JWESerializerManagerFactory::class), 'create'])
                ->setArguments([$itemConfig['serializers']])
                ->addTag('jose.jwe_serializer_manager')
                ->setPublic($itemConfig['is_public']);
            foreach ($itemConfig['tags'] as $id => $attributes) {
                $definition->addTag($id, $attributes);
            }
            $container->setDefinition($service_id, $definition);
            $container->registerAliasForArgument($service_id, JWESerializerManager::class, $name . 'JweSerializer');
        }
    }

    #[Override]
    public function getNodeDefinition(NodeDefinition $node): void
    {
        $node->children()
            ->arrayNode($this->name())
            ->treatFalseLike([])
            ->treatNullLike([])
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children()
            ->booleanNode('is_public')
            ->info('If true, the service will be public, else private.')
            ->defaultTrue()
            ->end()
            ->arrayNode('serializers')
            ->info('A list of JWE serializers aliases.')
            ->isRequired()
            ->scalarPrototype()
            ->end()
            ->treatNullLike([])
            ->treatFalseLike([])
            ->requiresAtLeastOneElement()
            ->end()
            ->arrayNode('tags')
            ->info('A list of tags to be associated to the service.')
            ->useAttributeAsKey('name')
            ->treatNullLike([])
            ->treatFalseLike([])
            ->variablePrototype()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();
    }

    #[Override]
    public function prepend(ContainerBuilder $container, array $config): array
    {
        return [];
    }
}
