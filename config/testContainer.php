<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Ajustez le chemin vers autoload.php
$container = require __DIR__ . '/../config/container.php'; // Ajustez le chemin vers container.php

// Tester SessionManager
$sessionManager = $container->get(App\Service\SessionManager::class);
var_dump($sessionManager);
