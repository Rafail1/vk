<?php

namespace Vk;

class DB {

    static private $instance;
    private $pdo;

    private function __construct($host, $db, $user, $pass) {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
        $opt = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );
        $this->pdo = new \PDO($dsn, $user, $pass, $opt);
    }

    static public function getInstance() {
        if (is_null(self::$instance)) {
            $conf = Config::getInstance();
            self::$instance = new self($conf->getParam('host'), $conf->getParam('db'), $conf->getParam('user'), $conf->getParam('password'));
        }
        return self::$instance;
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function execute($q, $params = false) {
        try {
            $sth = $this->pdo->prepare($q);
            $sth->execute($params);
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $sth->queryString;
            return false;
        }

        return $sth;
    }

}
