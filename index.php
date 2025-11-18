<?php
// Royal SaaS Starter Entry Point

require __DIR__ . '/core/bootstrap.php';

use Core\Router;

$router = new Router();
$router->dispatch();
