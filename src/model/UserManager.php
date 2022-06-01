<?php
require_once("src/model/Manager.php");

class UserManager extends Manager {

    public function checkUser($email, $password) {
        $user = $this->getUserByEmail($email, true);
        if ($user) {
            if (password_verify($password, $user["password"])) {
                return $user;
            }
        }
        return false;
       
    }

    public function addUser($name, $firstName, $role, $email, $status, $password) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'INSERT INTO user(name, first_name, role, email, status, password) VALUES(?, ?, ?, ?, ?, ?)'
        );
        $affectedLines = $user->execute(array($name, $firstName, $role, $email, $status, $password));

        return $affectedLines;
    }

    public function deleteUser($id) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'DELETE FROM user WHERE id = ?'
        );
        $affectedLines = $user->execute(array($id));

        return $affectedLines;
    }

    public function getUserById($id) {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT * 
            FROM user 
            WHERE id = ?'
        );
        $req->execute(array($id));
        $user = $req->fetch();
        return $user;
    }

    public function getUserByEmail($email, $validated = false) {
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

    public function emailAvailable($email) {
        return !$this->getUserByEmail($email);
    }

    public function accountValidation($email) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET status = "validated"
            WHERE email = ?'
        );
        $affectedLines = $user->execute(array($email));

        return $affectedLines;
    }

    public function loadInfo($id) {
        $user = $this->getUserById($id);
        $_SESSION['user'] = $user;
    }

    public function setName($id, $name) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET name = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($name, $id));

        return $affectedLines;
    }

    public function setFirstName($id, $firstName) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET first_name = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($firstName, $id));

        return $affectedLines;
    }

    public function setEmail($id, $email) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET email = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($email, $id));

        return $affectedLines;
    }

    public function setPassword($id, $hash) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE user
            SET password = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($hash, $id));

        return $affectedLines;
    }
    
}