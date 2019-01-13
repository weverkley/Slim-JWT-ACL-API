<?php

namespace App\Controller;

use App\Helper\JwtGenerator;
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
		
		if (empty($post['email'])) {
			$this->return['error'] = true;
			$this->return['message'] = 'Email cannot be empty.';
            return $response->withJson($this->return);
		}

		if (empty($post['password'])) {
			$this->return['error'] = true;
			$this->return['message'] = 'Password cannot be empty.';
            return $response->withJson($this->return);
		}

        $user = $this->userService->getByEmail($post['email']);

        if (!$user) {
            $this->return['error'] = true;
            $this->return['message'] = 'This email isnt registered.';
            return $response->withJson($this->return);
        }

        if (md5($post['password']) != $user['password']) {
            $this->return['error'] = true;
            $this->return['message'] = 'Password doesnt match.';
            return $response->withJson($this->return);
        }

        $userPerm = $this->userService->getPermissions($user['id']);
		$permissions = [];
		
        if (!empty($userPerm)) {
			foreach ($userPerm as $k => $v) {
				if (!in_array($v, $permissions))
					$permissions[] = $v['permission'];
            }
		}

        $jwt = JwtGenerator::getToken($user, $permissions);
        $this->return['data'] = $jwt;
        $this->return['message'] = 'Authentication ok.';

        return $response->withJson($this->return);
    }
}
