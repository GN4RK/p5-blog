<?php
declare(strict_types=1);

namespace App\Model;


class UserManager extends Manager {

    public function checkUser(string $email, string $password) {
        $user = $this->getUserByEmail($email, true);
        if ($user) {
            if (password_verify($password, $user["password"])) {
                return $user;
            }
        }
        return false;
       
    }

    public function addUser(string $name, string $firstName, string $role, string $email, string $status, string $password): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'INSERT INTO user(name, first_name, role, email, status, password) VALUES(?, ?, ?, ?, ?, ?)'
        );
        $affectedLines = $user->execute(array($name, $firstName, $role, $email, $status, $password));

        return $affectedLines;
    }

    public function deleteUser(string $idUser): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'DELETE FROM user WHERE id = ?'
        );
        $affectedLines = $user->execute(array($idUser));

        return $affectedLines;
    }

    public function getUserById(int $idUser) {
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

    public function getUserByEmail(string $email, bool $validated = false) {
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

    public function emailAvailable(string $email): bool {
        return !$this->getUserByEmail($email);
    }

    public function accountValidation(string $email): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET status = "validated"
            WHERE email = ?'
        );
        $affectedLines = $user->execute(array($email));

        return $affectedLines;
    }

    public function loadInfo(int $idUser): void {
        $session = new Session();
        $user = $this->getUserById($idUser);
        $session->set('user', $user);
    }

    public function setName(int $idUser, string $name): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET name = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($name, $idUser));

        return $affectedLines;
    }

    public function setFirstName(int $idUser, string $firstName): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET first_name = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($firstName, $idUser));

        return $affectedLines;
    }

    public function setEmail(int $idUser, string $email): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET email = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($email, $idUser));

        return $affectedLines;
    }

    public function setPassword(int $idUser, string $hash): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET password = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($hash, $idUser));

        return $affectedLines;
    }

    public function setRole(int $idUser, string $role): bool {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET role = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($role, $idUser));

        return $affectedLines;
    }

    public function getUsers(): array {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM user');
        $req->execute();
        $users = $req->fetchAll();
        return $users;
    }
    
}