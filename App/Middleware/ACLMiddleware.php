<?php

namespace App\Middleware;

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class ACLMiddleware
{

    /**
     * Slim container
     *
     * @var object
     */
    protected $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $routeInfo = $request->getAttribute('routeInfo');
        $jwt = $request->getAttribute('jwt');
        $route = $request->getAttribute('route')->getName();

        $array = (array) $jwt['data'];
        $array = isset($array['permissions']) ? $array['permissions'] : null;

        if ($array) {
            $routes = [];
            foreach ($array as $v) {
                $routes[] = $v;
            }
            if (in_array($route, $routes)) {
				return $next($request, $response);
			// You can set an admin permission so it can pass anything.
            } else if (in_array('ADMIN', $routes)) {
                return $next($request, $response);
            } else {
                $return['error'] = true;
                $return['message'] = 'This user doesnt have permission to access this function.';
                $return['permission'] = $route;
                $return['permission'] = $routes;
                return $response
                    ->withHeader('Content-Type', 'Application/json')
                    ->withJson($return, 403);
            }
        } else {
            $return['error'] = true;
            $return['message'] = 'This user doesnt have permission to access this function';
            return $response
                ->withHeader('Content-Type', 'Application/json')
                ->withJson($return, 403);
        }

    }

}
