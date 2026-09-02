<?php

require_once __DIR__ . '/../vendor/autoload.php';

$storagePath = '/tmp/storage';
$cachePath   = '/tmp/bootstrap/cache';

$directories = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
    $cachePath,
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

$_ENV['APP_SERVICES_CACHE'] = $cachePath . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
$_ENV['APP_CONFIG_CACHE']   = $cachePath . '/config.php';
$_ENV['APP_ROUTES_CACHE']   = $cachePath . '/routes.php';
$_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';

putenv("APP_SERVICES_CACHE={$cachePath}/services.php");
putenv("APP_PACKAGES_CACHE={$cachePath}/packages.php");
putenv("APP_CONFIG_CACHE={$cachePath}/config.php");
putenv("APP_ROUTES_CACHE={$cachePath}/routes.php");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("LOG_CHANNEL=stderr");

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);