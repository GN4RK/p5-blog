<?php
require_once("src/model/Manager.php");

class UserManager extends Manager {

    public function checkUser($email, $password) {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT * 
            FROM user 
            WHERE email = ? 
            AND password = ?'
        );
        $req->execute(array($email, $password));
        $req = $req->fetch();

        return $req;
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

    public function getUser($id) {
        $db = $this->dbConnect();
        $user = $db->prepare(
            'SELECT * 
            FROM user 
            WHERE id = ?'
        );
        $user->execute(array($id));

        return $user;
    }
    
}