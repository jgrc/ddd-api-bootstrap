<?php
namespace Jgrc\Bootstrap\Kernel;

use Jgrc\Bootstrap\Kernel\Utils\ApiArgumentResolver;
use Jgrc\Bootstrap\Kernel\Utils\ApiControllerResolver;
use Jgrc\Bootstrap\Kernel\Utils\ApiDomainEventSubscriber;
use Jgrc\Bootstrap\Kernel\Utils\ApiExceptionSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader as ContainerXmlFileLoader;

class Kernel
{
    private $routeCollection;
    private $container;
    private $eventSubscriberServices;
    
    public function __construct(
        string $dirPath,
        string $routesXmlFile,
        string $containerXmlFile,
        array $eventSubscriberServices
    ) {
        $this->routeCollection = $this->routes($dirPath, $routesXmlFile);
        $this->container = self::container($dirPath, $containerXmlFile);
        $this->eventSubscriberServices = $eventSubscriberServices;
    }

    public function run(): void
    {
        $kernel = new HttpKernel(
            $this->dispatcher(),
            new ApiControllerResolver($this->container),
            new RequestStack(),
            new ApiArgumentResolver()
        );

        $request = Request::createFromGlobals();
        $reponse = $kernel->handle($request);
        $reponse->send();

        $kernel->terminate($request, $reponse);
    }

    private function dispatcher(): EventDispatcher
    {
        $dispatcher = new EventDispatcher();

        $dispatcher->addSubscriber(
            new RouterListener(
                new UrlMatcher(
                    $this->routeCollection,
                    new RequestContext()
                ),
                new RequestStack()
            )
        );

        $dispatcher->addSubscriber(
            new ApiExceptionSubscriber()
        );

        $dispatcher->addSubscriber(
            new ApiDomainEventSubscriber(
                $this->container,
                $this->eventSubscriberServices
            )
        );

        return $dispatcher;
    }

    private function routes(string $dirPath, string $routesXmlFile): RouteCollection
    {
        return (new XmlFileLoader(
            new FileLocator($dirPath)
        ))->load($routesXmlFile);
    }

    public static function container(string $dirPath, string $containerXmlFile): ContainerBuilder
    {
        (new ContainerXmlFileLoader(
            $container = new ContainerBuilder(),
            new FileLocator($dirPath)
        ))->load($containerXmlFile);

        return $container;
    }
}