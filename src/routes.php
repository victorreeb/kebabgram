<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Home route");

    // Render index view
    return $this->renderer->render($response, 'home.phtml', $args);
});
