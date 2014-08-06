<?php 

class Blog_model extends ODD_Model {

     
    function __construct(){
        parent::__construct();
        $this->table = 'entries';
    }
    
    function get_last_ten_entries(){
        $query = $this->db->get($this->table, 10);
        return $query->result();
    }

    function delete_entry()
    {
        
    }
}