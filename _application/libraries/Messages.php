<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Messages {
    
    protected $CI;
    
    protected $flashMessages;
    
	public function __construct(){
        $this->CI =& get_instance();
        $this->flashMessages = array();
	}
    
    public function get_messages($type){
        $messages = $this->CI->session->flashdata($type);
        if ( ! $messages){
            return false;   
        }
        if ( ! is_array($messages)) {
            $messages = array($messages);
        }
        return $messages;
    }
    
    public function add_messages($type = 'error', $messages = FALSE){
        if ( ! is_array($messages)) {
            $messages = array($messages);
        }
        if (@$this->flashMessages[$type]){
            $this->flashMessages[$type] = array_merge($this->flashMessages[$type], $messages);
        } else {
            $this->flashMessages[$type] = $messages;
        } 
        $this->CI->session->set_flashdata($type, $this->flashMessages[$type]);
    }
}

/* End of file Messages.php */