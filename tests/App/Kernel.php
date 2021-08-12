<?php declare(strict_types=1);

namespace Symfona\Bundle\JsonResponseBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        yield new \Symfona\Bundle\JsonResponseBundle\JsonResponseBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', ['test' => true]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('index', '/')->controller(__CLASS__);
    }

    public function __invoke(Request $request): ?array
    {
        return match ($request->getMethod()) {
            Request::METHOD_GET => $request->query->all(),
            Request::METHOD_POST => \json_decode((string) $request->getContent(), true),
            default => throw new \InvalidArgumentException('Something wrong'),
        };
    }
}
