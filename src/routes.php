<?php
// Routes

// $app->get('/', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Homepage route");
//     // Render views
//     return $this->renderer->render($response, 'homepage.phtml', $args);
// });

$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'homepage.html');
});
