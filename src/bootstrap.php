<?php

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Yaml\Yaml;
use Workshop\Controller\TaskController;
use Workshop\Model\TaskRepository;
use Workshop\Templating\RouterHelper;
use Workshop\Util\SingleControllerResolver;


// Load and cache config

$loadConfig = function($configDir, $cacheDir) {
    $configCachePath = $cacheDir . '/config.php';
    $configFilePath = $configDir . '/config.yml';
    $configCache = new ConfigCache($configCachePath, true);
    
    if (!$configCache->isFresh()) {
        $resource = new FileResource($configFilePath);
        
        $parameters = Yaml::parse($configFilePath);
        $parameters['cache_dir'] = $cacheDir;
        $parameters['config_dir'] = $configDir;
    
        $code = '<?php return ' . var_export($parameters, true) . ';';
    
        $configCache->write($code, array($resource));
    }

    $data = require $configCachePath;
    
    return $data;
};

$config = $loadConfig(__DIR__ . '/../config', __DIR__ . '/../cache');



// Configure Container

$container = new Pimple\Container($config);

$container['repository'] = function($c) {
    return new TaskRepository(
        new \PDO(
            $c['db']['dsn'],
            $c['db']['username'],
            $c['db']['password'],
            array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            )
        )
    );
};

$container['context'] = function($c) {
    return new RequestContext();
};

$container['router'] = function($c) {
    return new Router(
        new YamlFileLoader(new FileLocator($c['config_dir'])),
        'routing.yml',
        array(
            'cache_dir' => $c['cache_dir']
        ),
        $c['context']
    );
};

$container['templating'] = function($c) {
    $templating = new PhpEngine(
        new TemplateNameParser(),
        new FilesystemLoader(
            array(__DIR__ . '/../views/%name%')
        )
    );
    $templating->addHelpers(
        array(
            new SlotsHelper(),
            new RouterHelper($c['router'])
        )
    );
    
    return $templating;
};

$container['dispatcher'] = function($c) {
    $dispatcher = new EventDispatcher();
    $dispatcher->addSubscriber(
        new RouterListener($c['router']->getMatcher(), $c['context'])
    );
    
    return $dispatcher;
};

$container['controller_resolver'] = function($c) {
    $controller = new TaskController(
        $c['repository'],
        $c['router']->getGenerator(),
        $c['templating']);
    
    return new SingleControllerResolver($controller);
};

$container['kernel'] = function($c) {
    return new HttpKernel($c['dispatcher'], $c['controller_resolver']);
};

return $container;
