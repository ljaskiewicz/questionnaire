<?php

namespace Shared\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const array CONFIGS_NAMESPACES_ARRAY = [
        'shared',
        'questionnaire',
    ];

    private const string CONFIG_EXTS = '.{php,xml,yaml,yml}';

    private static function getConfigNamespaces(): string
    {
        return '/{'.\implode(',', self::CONFIGS_NAMESPACES_ARRAY).'}';
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('.container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.self::getConfigNamespaces().'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.self::getConfigNamespaces().'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.self::getConfigNamespaces().'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.self::getConfigNamespaces().'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, 'glob');
        $routes->import($confDir.self::getConfigNamespaces().'/{routes}/*'.self::CONFIG_EXTS, 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, 'glob');
    }
}
