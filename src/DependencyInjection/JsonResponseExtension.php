<?php declare(strict_types=1);

namespace Symfona\Bundle\JsonResponseBundle\DependencyInjection;

use Symfona\Bundle\JsonResponseBundle\EventListener\ResponseListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\KernelEvents;

final class JsonResponseExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $listener = new Definition(ResponseListener::class);
        $listener->addTag('kernel.event_listener', ['event' => KernelEvents::VIEW]);
        $listener->addTag('kernel.event_listener', ['event' => KernelEvents::EXCEPTION]);

        $container->setDefinition(ResponseListener::class, $listener);
    }
}
