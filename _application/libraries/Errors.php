<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Errors {
    
    protected $CI;
    
	public function __construct(){
        $this->CI =& get_instance();
	}
    
    public function get_message($msg){

        switch ($msg) {
            case 'failed':
                return "Your username or password is incorrect.";
            case 'restricted':
                return "You are trying to access a restricted page.  Please login first.";
        }
        return FALSE;
        
    }
}

/* End of file Errors.php */