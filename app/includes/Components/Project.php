<?php

namespace Components;

class Project {

    private $crud;
    private $table;

    public function __construct() {
        $this->crud = CRUD::getInstance();
        $this->table = 'projects';
    }

    public function addProject($fields) {
        $fields['id'] = 'NULL';
        return $this->crud->create($this->table, $fields);
    }

    public function getProjects($user_id) {
        $sth = $this->crud->read($this->table,['id', 'name'],['user_id' => $user_id]);
        return $sth->fetchAll();
    }
    public function getProjectById($id) {
        $sth = $this->crud->read($this->table,['id', 'name'],['id' => $id]);
        return $sth->fetchAll();
    }

    public function updateProject($id, $fields) {
        return $this->crud->update($this->table, $id, $fields);
    }

    public function deleteProject($id) {
        return $this->crud->delete($this->table, $id);
    }

}
