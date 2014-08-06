<?php

class ODD_Model extends CI_Model {

    protected $table;
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_all(){
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    function get_single($field = 'id', $value = ''){
        $query = $this->db->get_where($this->table, array($field => $value), 1);
        return $query->row();
    }

    function insert($data){
        foreach ($data AS $key => $value){
            $this->$key = $value;
        }
        $this->db->insert($this->table, $this);
        return $this->db->insert_id();
    }

    function update($data){
        foreach ($data AS $key => $value){
            $this->$key = $value;
        }
        $this->db->update($this->table, $this, array('id' => $data['id']));
    }
    
    function delete($field = 'id', $value = ''){
        $this->db->delete($this->table, array('id' => $value)); 
        return true;
    }
}