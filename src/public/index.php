<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;   
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Http\Response\Cookies;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;

// $config = new Config([]);


// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');


/* 
   **Register an autoloader**
   Autoloader bascially used to register the services which include classes,
   if the service doesn't include classes like views then it can't be autoload.

   e.g. controllers, models 
*/

$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/"
    ]
);

$loader->registerNamespaces(
    [
        'App\Components' => APP_PATH . '/components',
        'App\Listeners' => APP_PATH . '/listeners'
    ]
);

$loader->register();

/*
    **FactoryDefault**
    DI is reposible for registering the services like view, 
    the services which doesn't correspond to the classes
*/

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

/* To register the base URI */
$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

/* To register a session */
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();
        return $session;
    }, true
);

/* To register a config file */

$container->set(
    'config',
    function () {
        $configData = require APP_PATH.'/etc/config.php';
        return new Config($configData);
    }
);

$container->setShared(
    'modelsManager',
    function () {
        $eventsManager = new EventManager();

        $eventsManager->attach(
            'model:beforeSave',
            function(Event $event, $model){
                $listener = new \App\Components\EventListener();
                $listener->modelEvent($event, $model, Settings::findFirst());
            }   
        );

        $modelsManager = new ModelsManager();
        $modelsManager->setEventsManager($eventsManager);

        return $modelsManager;
    }
);
// $eventsManager = new EventsManager();

// $eventsManager->attach(
//     'model:beforeCreate',
//     new \App\Listeners\EventListener()
// );
$container->set(
    'db',
    function () {
        $config = $this->get('config');
        return new Mysql(
                [
                    'host'     => $config->database->host,
                    'username' => $config->database->username,
                    'password' => $config->database->password,
                    'dbname'   => $config->database->dbname
                ]
            );
        }
);

$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
