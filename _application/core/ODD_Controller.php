<?php

class ODD_Controller extends CI_Controller {
    
    protected $data;

    function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->data['success'] = $this->messages->get_messages('success');
        $this->data['error'] = $this->messages->get_messages('error');
    }
}