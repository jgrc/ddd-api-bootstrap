<?php
namespace Jgrc\Bootstrap\Kernel\Utils;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ApiControllerResolver implements ControllerResolverInterface
{
    private const CONTROLLER_METHOD = 'execute';

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getController(Request $request)
    {
        $route = $request->attributes->get('_route', '[without-name]');
        $service = $request->attributes->get('_service');

        if (null === $service) {
            throw new \Exception(
                sprintf('Route %s without associated service', $route)
            );
        }

        if (false === $this->container->has($service)) {
            throw new \Exception(
                sprintf('Route %s uses an non existing service %s', $route, $service)
            );
        }

        return [$this->container->get($service), self::CONTROLLER_METHOD];
    }
}