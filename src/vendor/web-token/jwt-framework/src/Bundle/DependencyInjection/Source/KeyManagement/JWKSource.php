<?php

declare(strict_types=1);

namespace Jose\Bundle\JoseFramework\DependencyInjection\Source\KeyManagement;

use InvalidArgumentException;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\KeyManagement\JWKSource\JWKSource as JWKSourceInterface;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\Source;
use LogicException;
use Override;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use function array_key_exists;
use function count;
use function sprintf;

final class JWKSource implements Source
{
    /**
     * @var JWKSourceInterface[]|null
     */
    private ?array $jwkSources = null;

    #[Override]
    public function name(): string
    {
        return 'keys';
    }

    #[Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $sources = $this->getJWKSources();
        foreach ($configs[$this->name()] as $name => $itemConfig) {
            foreach ($itemConfig as $sourceName => $sourceConfig) {
                if (array_key_exists($sourceName, $sources)) {
                    $source = $sources[$sourceName];
                    $source->create($container, 'key', $name, $sourceConfig);
                } else {
                    throw new LogicException(sprintf('The JWK definition "%s" is not configured.', $name));
                }
            }
        }
    }

    #[Override]
    public function getNodeDefinition(NodeDefinition $node): void
    {
        $sourceNodeBuilder = $node
            ->children()
            ->arrayNode('keys')
            ->treatFalseLike([])
            ->treatNullLike([])
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->validate()
            ->ifTrue(fn ($config): bool => count($config) !== 1)
            ->thenInvalid('One key type must be set.')
            ->end()
            ->children();
        foreach ($this->getJWKSources() as $name => $source) {
            $sourceNode = $sourceNodeBuilder->arrayNode($name)
                ->canBeUnset();
            $source->addConfiguration($sourceNode);
        }
    }

    #[Override]
    public function prepend(ContainerBuilder $container, array $config): array
    {
        return [];
    }

    /**
     * @return JWKSourceInterface[]
     */
    private function getJWKSources(): array
    {
        if ($this->jwkSources !== null) {
            return $this->jwkSources;
        }

        // load bundled adapter factories
        $tempContainer = new ContainerBuilder();
        $tempContainer->registerForAutoconfiguration(JWKSourceInterface::class)->addTag('jose.jwk_source');
        $loader = new PhpFileLoader($tempContainer, new FileLocator(__DIR__ . '/../../../Resources/config'));
        $loader->load('jwk_sources.php');
        $tempContainer->compile(true);

        $services = $tempContainer->findTaggedServiceIds('jose.jwk_source');
        $jwkSources = [];
        foreach (array_keys($services) as $id) {
            $factory = $tempContainer->get($id);
            if (! $factory instanceof JWKSourceInterface) {
                throw new InvalidArgumentException('Invalid object');
            }
            $jwkSources[str_replace('-', '_', $factory->getKey())] = $factory;
        }

        $this->jwkSources = $jwkSources;

        return $jwkSources;
    }
}
