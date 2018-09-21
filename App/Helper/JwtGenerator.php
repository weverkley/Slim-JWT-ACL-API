<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use App\Helper\Url;

/**
 * Class to generate and manage Json Web Tokens
 *
 * @package  App
 * @author   Wever Kley <wever-kley@live.com>
*/
class JwtGenerator {

	/**
	 * Generate token string
	 *
	 * @param Array $user
	 * @return string
	 */
    public static function getToken($user, $permissions) {
        return JWT::encode([
            'iss' => Url::getServerUrl(),
            'iat' => time(),
            'exp' => time() + (3600 * 12),
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
				'password' => $user['password'],
				'permissions' => $permissions
            ],
        ], (string)getenv('JWT_SECRET'));
    }
}   