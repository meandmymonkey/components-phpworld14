<?php

namespace Workshop\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Workshop\Controller\TaskController;

class SingleControllerResolver implements ControllerResolverInterface
{
    private $controller;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    public function getController(Request $request)
    {
        $class = new \ReflectionClass($this->controller);
        $action = $request->get('_controller');

        if (!$class->hasMethod($action)) {
            return false;
        }

        return array($this->controller, $action);
    }

    public function getArguments(Request $request, $controller)
    {
        return array($request);
    }
}
