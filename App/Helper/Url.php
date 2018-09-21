<?php

namespace App\Helper;

/**
 * Class manage/validate URLs
 *
 * @package  App
 * @author   Wever Kley <wever-kley@live.com>
*/
class Url {

	/**
	 * Get server current url
	 *
	 * @return string
	 */
    public static function getServerUrl() {
        return sprintf(
            '%s://%s',
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );
    }
}
