<?php

namespace Components;

class CRUD {

    private $db;
    private static $instance;

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

    private function __construct() {
        $this->db = \Vk\DB::getInstance();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function makeWhere($fields, &$q) {
        $bdVals = [];
        $q .= ' WHERE';
        foreach ($fields as $k => $val) {
            if (is_array($val) && $val['LOGIC']) {
                $logic = $val['LOGIC'];
                unset($val['LOGIC']);
                $first = true;
                foreach ($val as $arr) {
                    if (!$first) {
                        $q .= $logic;
                    }
                    $first = false;
                    foreach ($arr as $col => $param) {
                        $q .= ' ' . $col . ' = ' . ':' . $col;
                        $bdVals[':'.$col] = $param;
                    }
                }
            } else {

                $q .= ' ' . $k . ' = ' . ':' . $k;
                $bdVals[':'.$k] = $val;
            }
        }
        return $bdVals;
    }

    public function create($table, $fields) {
        $into = '';
        foreach ($fields as $col => $val) {
            if ($into) {
                $into .= ', ';
            } else {
                $into = '(';
            }
            $into .= '`' . $col . '`';

            if ($values) {
                $values.= ', ';
            } else {
                $values = '(';
            }
            $values .= ':' . $col;
            $bdVals[':'.$col] = $val;
        }

        $into .= ')';
        $values .= ')';

        $q = 'INSER INTO ' . $table . ' ' . $into
                . 'VALUES ' . $values;
        return $this->db->execute($q, $bdVals);
    }

    public function read($table, $fields, $where) {
        $bdFields = implode(', ', $fields);

        $q = 'SELECT ' . $bdFields . ' FROM ' . $table;

        if ($where) {
            $whereVals = $this->makeWhere($where, $q);
        }

        return $this->db->execute($q, $whereVals);
    }

    public function update($table, $id, $fields) {
        $upd = '';
        foreach ($fields as $k => $v) {
            $upd .= $k . ' = :' . $k . ' ';
            $bdVals[':'.$k] = $v;
        }
        $q = 'UPDATE ' . $table . ' SET ' . $upd . ' WHERE id = '.  intval($id);
        return $this->db->execute($q, [$bdVals]);
    }

    public function delete($table, $id) {
        $q = 'DELETE FROM ' . $table . ' WHERE id = ?';
        return $this->db->execute($q, [$id]);
    }

    public function lastInsertId(){
        return $this->db->lastInsertId();
    }
}
