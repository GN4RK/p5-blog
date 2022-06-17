<?php
declare(strict_types=1);

namespace App\Model;

/**
 * UserManager class
 */
class UserManager extends Manager 
{    
    /**
     * Check user connection
     *
     * @param  string $email
     * @param  string $password
     * @return array|false Return the user's info or false if password not match
     */
    public function checkUser(string $email, string $password): array|false 
    {
        $user = $this->getUserByEmail($email, true);
        if ($user) {
            if (password_verify($password, $user["password"])) {
                return $user;
            }
        }
        return false;
    }
    
    /**
     * Add a user to the database
     *
     * @param  string $name
     * @param  string $firstName
     * @param  string $role
     * @param  string $email
     * @param  string $status
     * @param  string $password
     * @return bool
     */
    public function addUser(
        string $name, 
        string $firstName, 
        string $role, 
        string $email, 
        string $status, 
        string $password
    ): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'INSERT INTO user(name, first_name, role, email, status, password) 
            VALUES(?, ?, ?, ?, ?, ?)'
        );
        $affectedLines = $user->execute(array(
            $name, 
            $firstName, 
            $role, 
            $email, 
            $status, 
            $password
        ));

        return $affectedLines;
    }
    
    /**
     * Delete a user from the database
     *
     * @param  int $idUser
     * @return bool
     */
    public function deleteUser(int $idUser): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'DELETE FROM user WHERE id = ?'
        );
        $affectedLines = $user->execute(array($idUser));

        return $affectedLines;
    }
    
    /**
     * Return user's info if found from user id
     *
     * @param  int $idUser
     * @return array|false false if not found
     */
    public function getUserById(int $idUser): array|false 
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT * 
            FROM user 
            WHERE id = ?'
        );
        $req->execute(array($idUser));
        $user = $req->fetch();
        return $user;
    }
    
    /**
     * Return user's info if found from user email
     *
     * @param  string $email
     * @param  bool $validated optional does the user has to bo validated ?
     * @return array|false false if not found
     */
    public function getUserByEmail(string $email, bool $validated = false): array|false
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT * 
            FROM user 
            WHERE email = ?' . 
            ($validated ? ' AND status = "validated"' : '')
        );
        $req->execute(array($email));
        $user = $req->fetch();
        return $user;
    }
    
    /**
     * Return if the email is available in the database
     *
     * @param  string $email
     * @return bool
     */
    public function emailAvailable(string $email): bool 
    {
        return !$this->getUserByEmail($email);
    }
    
    /**
     * Update status of a user to 'validated'
     *
     * @param  string $email
     * @return bool
     */
    public function accountValidation(string $email): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET status = "validated"
            WHERE email = ?'
        );
        $affectedLines = $user->execute(array($email));

        return $affectedLines;
    }
    
    /**
     * Load user's info in the $_SESSION
     *
     * @param  int $idUser
     * @return void
     */
    public function loadInfo(int $idUser): void 
    {
        $session = new Session();
        $user = $this->getUserById($idUser);
        $session->set('user', $user);
    }
    
    /**
     * Set name of a user in the database
     *
     * @param  int $idUser
     * @param  string $name
     * @return bool
     */
    public function setName(int $idUser, string $name): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET name = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array(
            htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), 
            $idUser
        ));

        return $affectedLines;
    }
    
    /**
     * Set first name of a user in the database
     *
     * @param  int $idUser
     * @param  string $firstName
     * @return bool
     */
    public function setFirstName(int $idUser, string $firstName): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET first_name = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array(
            htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'), 
            $idUser
        ));

        return $affectedLines;
    }
    
    /**
     * Set email of a user in the database
     *
     * @param  int $idUser
     * @param  string $email
     * @return bool
     */
    public function setEmail(int $idUser, string $email): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET email = ?
            WHERE id = ?'
        );
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $affectedLines = $user->execute(array($email, $idUser));

        return $affectedLines;
    }
    
    /**
     * Set password hash of a user in the database
     *
     * @param  int $idUser
     * @param  string $hash
     * @return bool
     */
    public function setPassword(int $idUser, string $hash): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET password = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($hash, $idUser));

        return $affectedLines;
    }
    
    /**
     * Set role of a user in the database
     *
     * @param  int $idUser
     * @param  string $role
     * @return bool
     */
    public function setRole(int $idUser, string $role): bool 
    {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET role = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($role, $idUser));

        return $affectedLines;
    }
    
    /**
     * Return all the users of the database
     *
     * @param  bool $admin if true, will return all admin users
     * @return array
     */
    public function getUsers(bool $admin = false): array 
    {
        $db = $this->dbConnect();
        $condition = ($admin) ? 'WHERE role = "admin"' : '';
        $req = $db->prepare(
            'SELECT * FROM user '. $condition
        );
        $req->execute();
        $users = $req->fetchAll();
        return $users;
    }
    
}