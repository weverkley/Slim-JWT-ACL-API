<?php

namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \PDOException;
use App\Model\User;

class UserController {

	private $userService;
	private $jwt;
	private $return;
    
    public function __construct($userService) {
		$this->userService = $userService;
		$this->return = ['error' => false, 'message' => '', 'data' => []];
    }
	
	public function create(Request $request, Response $response, $args) {
		$post = $request->getParsedBody();
		
		$user = new User();
		$user->setName($post['name']);
		$user->setEmail($post['email']);
		$user->setPassword(md5($post['senha']));

		$data = $this->userService->insert($user);
		$this->return['error'] = $data['error'];
		$this->return['message'] = $data['message'];
		$this->return['data'] = $data['data'];
        
        return $response->withJson($this->return);
	}

}
