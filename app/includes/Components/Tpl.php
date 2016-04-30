<?php

namespace Components;

class Tpl {

    private $crud;

    public function __construct() {
        $this->table = 'messages_tpl';
        $this->crud = CRUD::getInstance();
    }

    public function getProjectTpls($uid, $projectId) {
        $sth = $this->crud->read($this->table, ['id', 'tpl'], ['user_id' => $uid,
            'project_id' => $projectId]);

        if ($res = $sth->fetchAll()) {
            return $res;
        }

        return false;
    }

    public function addTpl($fields) {
        $fields['id'] = 'NULL';
        return $this->crud->create($this->table, $fields);
    }

    public function removeTpl($id) {
        return $this->crud->delete($this->table, $id);
    }

    public function updateTpl($id, $fields) {
        return $this->crud->update($this->table, $id, $fields);
    }

}
