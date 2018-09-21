<?php

namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController {
    private $renderer;

    public function __construct($renderer) {
        $this->renderer = $renderer;
    }

    public function defaultPage(Request $request, Response $response, $args) {
        return $this->renderer->render($response, 'home/index.phtml', $args);
    }
    
}
