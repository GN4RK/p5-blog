<?php
require_once("src/model/Manager.php");

class UserManager extends Manager {

    public function checkUser($email, $password) {
        $user = $this->getUserByEmail($email);
        if ($user) {
            if (password_verify($password, $user["password"])) {
                return $user;
            }
        }
        return false;
       
    }

    public function addUser($name, $firstName, $role, $email, $status) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'INSERT INTO user(name, fistr_name, role, email, status) VALUES(?, ?, ?, ?, ?)'
        );
        $affectedLines = $user->execute(array($name, $firstName, $role, $email, $status));

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

    public function getUserByEmail($email) {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT * 
            FROM user 
            WHERE email = ?'
        );
        $req->execute(array($email));
        $user = $req->fetch();
        return $user;
    }
    
}