<?php
namespace Jgrc\Bootstrap\Kernel\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

class ApiArgumentResolver implements ArgumentResolverInterface
{
    public function getArguments(Request $request, $controller): array
    {
        return [$request];
    }
}