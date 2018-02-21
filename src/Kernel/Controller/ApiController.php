<?php
namespace Jgrc\Bootstrap\Kernel\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiController
{
    public function execute(Request $request): JsonResponse;
}