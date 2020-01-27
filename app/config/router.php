<?php

$router = $di->getRouter();


//$router->handle($_SERVER['REQUEST_URI']);

$router->add('/', ['controller' => 'index', 'action' => 'index']);

// User Controllers
$router->add('/user/login', [
    'controller' => 'users',
    'action' => 'login'
]);
$router->add('/user/login/submit', [
    'controller' => 'users',
    'action' => 'loginSubmit'
]);
$router->add('/user/logout', ['controller' => 'users', 'action' => 'logout']);
$router->add('/user/profile', ['controller' => 'users', 'action' => 'profile']);
$router->add('/user/worktable', ['controller' => 'users', 'action' => 'workTable']);
// Admin

$router->add('/user/register', ['controller' => 'admin', 'action' => 'register']);
$router->add('/user/register/submit', ['controller' => 'admin', 'action' => 'registerSubmit']);

// 404
$router->notFound(['controller' => 'index', 'action' => 'route404']);