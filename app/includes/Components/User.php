<?php

namespace Components;

class User {

    private $auth;
    private $id;
    private $name;
    private $login;
    private $table;
    private $error;
    private $crud;
    private static $instance;

    private function __construct() {
        $this->table = 'users';
        $this->crud = CRUD::getInstance();
        $this->auth = $this->authFromCookie();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add($name, $login, $password) {
        $params = [$name, $login, $password,
            $this->getHash($login, $password)];

        $sth = $this->crud->create($this->table, ['id' => 'NULL',
            'name' => $name,
            'login' => $login,
            'password' => $password,
            'hash' => $this->getHash($login, $password)]);
        if ($sth) {
            $user = [
                'id' => $this->crud->lastInsertId(),
                'login' => $login,
                'name' => $name,
                'password' => $password];
            return $user;
        } else {
            return false;
        }
    }

    public function isLoginFree($login) {
        $sth = $this->crud->read($this->table, ['login', 'name', 'id'], ['login' => $login]);
        if ($res = $sth->fetch()) {
            return false;
        }
        return true;
    }

    public function setUser($user) {
        $this->id = $user['id'];
        $this->name = $user['name'];
        $this->login = $user['login'];
        setcookie('user', md5('raf' . $user['login'] . $user['password']), time() + 3600 * 60 * 24);
        setcookie('user_id', $user['id'], time() + 3600 * 60 * 24);
        $this->auth = true;
    }

    public function getUser() {
        return ['id' => $this->id, 'name' => $this->name, 'login' => $this->login];
    }

    public function isAuth() {
        return $this->auth;
    }

    public function authFromCookie() {
        if ($id = filter_input(INPUT_COOKIE, 'user_id', FILTER_VALIDATE_INT) &&
                $hash = filter_input(INPUT_COOKIE, 'user', FILTER_SANITIZE_STRING)) {
            if ($user = $this->getById($id)) {
                if ($hash === $this->getHash($user['login'], $user['password'])) {
                    $this->setUser($user);
                    return true;
                }
            }
        }
        return false;
    }

    public function getHash($login, $password) {
        return md5('raf' . $login . $password);
    }

    public function auth($login, $password) {

        $sth = $this->crud->read($this->table, ['login', 'name', 'password', 'id'], ['login' => $login, 'password' => $password]);
        if ($user = $sth->fetch()) {
            $this->setUser($user);
            return true;
        } else {
            $this->error = 'wrong login';
            return false;
        }
    }

    public function getById($id) {

        $sth = $this->crud->read($this->table, ['login', 'name', 'password', 'id'], ['id' => filter_input(INPUT_COOKIE, 'user_id', FILTER_VALIDATE_INT)]);

        if ($user = $sth->fetch()) {
            return $user;
        }
        return false;
    }

    public function getAccessTokens() {
        if (!$this->isAuth()) {
            return false;
        }
        $sth = $this->crud->read('tokens', ['token'], ['user_id' => $this->id]);

        $result = $sth->fetchAll();
        return $result;
    }

    public function getId() {
        return $this->id;
    }

    public function getErrors() {
        return $this->error;
    }

}
