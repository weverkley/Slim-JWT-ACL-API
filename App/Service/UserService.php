<?php

namespace App\Service;

use App\Model\User;
use PDO;
use PDOException;

/**
 * Class to manage/store user data
 *
 * @package  App
 * @author   Wever Kley <wever-kley@live.com>
 */
class UserService
{

    /**
     * Handle DB Instance
     * using pdo driver
     *
     * @var PDO
     */
    private $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function insert(User $user)
    {
        try {
            $name = $user->getName();
            $email = $user->getEmail();
            $password = $user->getPassword();

            $stmt = $this->db->prepare("INSERT INTO user (name, email, password) VAlUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
			$stmt->execute();
			
			$user->setID($this->db->lastInsertId());

            return ['error' => false, 'message' => 'User Created.', 'data' => $user->getObject()];
        } catch (PDOException $e) {
            return ['error' => true, 'message' => $e->getMessage(), 'data' => ''];
        }
    }

    public function getByEmail($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return ['error' => false, 'message' => '', 'data' => $stmt->fetch(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            return ['error' => true, 'message' => $e->getMessage(), 'data' => ''];
        }
    }

    public function getPermissions($id)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT
				up.id, p.name AS permission
			FROM
				user_permission AS up
					INNER JOIN
				permission AS p ON up.permission_id = p.id
			WHERE
				up.user_id = :id"
            );
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['error' => false, 'message' => '', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            return ['error' => true, 'message' => $e->getMessage(), 'data' => ''];
        }
    }

}
