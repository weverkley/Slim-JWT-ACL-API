<?php

namespace App\Controller;

use App\Helper\JwtGenerator;
use App\Model\User;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{

    private $userService;
    private $return;

    public function __construct($userService)
    {
        $this->userService = $userService;
        $this->return = ['error' => false, 'message' => '', 'data' => []];
    }

    public function authenticate(Request $request, Response $response, $args)
    {
        $post = $request->getParsedBody();

        $user = $this->userService->getByEmail($post['email']);
        if ($user['error'] == true) {
            $this->return['error'] = $user['error'];
            $this->return['message'] = $user['message'];
            return $response->withJson($this->return);
        } else if (!$user['data']) {
            $this->return['error'] = true;
            $this->return['message'] = 'This email isnt registered.';
            return $response->withJson($this->return);
        }

        if (md5($post['password']) != $user['data']['password']) {
            $this->return['error'] = true;
            $this->return['message'] = 'Password doesnt match.';
            return $response->withJson($this->return);
        }

        $userPerm = $this->userService->getPermissions($user['data']['id']);
        $permissions = [];
        foreach ($userPerm['data'] as $k => $v) {
            if (!in_array($v, $permissions)) {
                $permissions[] = $v['permission'];
            }
        }

        $jwt = JwtGenerator::getToken($user['data'], $permissions);
        $this->return['data'] = $jwt;
        $this->return['message'] = 'Authentication ok.';

        return $response->withJson($this->return);
    }
}
