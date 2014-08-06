<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Layout {
    
    protected $CI;
    
	public function __construct(){
        $this->CI =& get_instance();
	}
    
    private function get_template($template){
        $template = "templates/$template.phtml";
        if ( file_exists(APPPATH.'/views/'.$template)){
            return $template;
        } else {
            return FALSE;
        }
    }
    
    public function render($views = array(), $data = array(), $template = 'layout', $return_data = FALSE){

        if ( $views && ! is_array($views)){
            $views = array($views);
        }
        
        $template = $this->get_template($template);
        $use_template = (bool) $template;
        
        $layout_content = '';
        if ( $views ){
            foreach ($views as $view) {
                $view .= '.phtml';
                $layout_content .= $this->CI->load->view($view, $data, $use_template);
            }
        }
        
        $data['layout_content'] = $layout_content;
        
        return $this->CI->load->view($template, $data, $return_data);
        
    }
}

/* End of file Layout.php */