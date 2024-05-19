<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
ini_set('memory_limit', '2024M');
return function (array $context) {
    (new Dotenv())->usePutenv()->bootEnv(dirname(__DIR__).'/.env');
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
