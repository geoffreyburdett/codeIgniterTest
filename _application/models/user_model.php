<?php

class User_model extends ODD_Model{
    
    function __construct(){
        parent::__construct();
        $this->table = 'users';
    }
    
    public function validate(){
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean(md5($this->input->post('password')));
        
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        
        if($query->num_rows == 1){
            $row = $query->row();
            $data = array(
                    'id' => $row->id,
                    'first_name' => $row->first_name,
                    'last_name' => $row->last_name,
                    'username' => $row->username,
                    'email' => $row->username,
                    'access_level' => $row->access_level,
                    'validated' => TRUE
                    );
            $this->session->set_userdata($data);
            return true;
        }
        return false;
    }
    
    public function random_password() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $alphaLength = strlen($alphabet) - 1;
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $password .= $alphabet[$n];
        }
        return $password;
    }
}