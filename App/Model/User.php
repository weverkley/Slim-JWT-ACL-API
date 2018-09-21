<?php

namespace App\Model;

/**
 * Class to manage user data as an entity.
 *
 * @package  App
 * @author   Wever Kley <wever-kley@live.com>
*/
class User {
	private $id;
    private $name;
    private $email;
	private $password;
	
	public function __construct() {}

	/**
	 * Set user id
	 *
	 * @param Int $id
	 * @return void
	 */
    public function setID(Int $id) {
        $this->id = $id;
    }

	/**
	 * Return user id
	 *
	 * @return string
	 */
    public function getID() {
        return (Int)$this->id;
    }

	/**
	 * Set user name
	 *
	 * @param String $name
	 * @return void
	 */
    public function setName(String $name) {
        $this->name = $name;
    }

	/**
	 * Return user name
	 *
	 * @return string
	 */
    public function getName() {
        return (String)$this->name;
    }
	
	/**
	 * Set user email
	 *
	 * @param String $email
	 * @return void
	 */
    public function setEmail(String $email) {
        $this->email = $email;
    }

	/**
	 * Return user email
	 *
	 * @return string
	 */
    public function getEmail() {
        return (String)$this->email;
    }

	/**
	 * Set user password
	 *
	 * @param String $password
	 * @return void
	 */
    public function setPassword(String $password) {
        $this->password = $password;
    }

	/**
	 * Return user password
	 *
	 * @return string
	 */
    public function getPassword() {
        return (String)$this->password;
	}
	
	public function getObject() {
		return [
			'id' => $this->getID(),
			'name' => $this->getName(),
			'email' => $this->getEmail(),
			'password' => $this->getPassword()
		];
	}
}
