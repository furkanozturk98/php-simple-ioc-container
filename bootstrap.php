<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new App\Application();

$app->setRoute("/products", [\App\Controllers\ProductController::class, "index"]);

$app->setRoute("/", function () {
    return "This is the landing page";
});

return $app;