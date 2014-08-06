<?php
class Blog extends ODD_Controller {
    
	public function __construct(){
        parent::__construct();
        $this->load->model('blog_model');
	}



	public function index(){
        $this->read();
	}
    
    
    
	public function read($slug = NULL){
        // Show Only 1 Blog Entry 
        if ($slug) {  
            $this->data['entry'] = $this->blog_model->get_single('slug',$slug); 
            if ( empty($this->data['entry']) ) {
                show_404(); 
            } else {
                $this->data['title'] = $this->data['entry']->title;
                $this->layout->render(array('blog/single','blog/footer'), $this->data);
            }
            
        // Show Last 10 blog entries
        } else { 
                $this->data['last_ten_entries'] = $this->blog_model->get_last_ten_entries(); 
                $this->data['title'] = "Recent Entries";
                $this->layout->render(array('blog/last10','blog/footer'), $this->data);
        }
	}
    
    
    
	public function write($id = FALSE, $success = FALSE){
        if ($id){
            $req_access_level = 20;
        } else {
            $req_access_level = 30;
        }
        $this->session->redirect_invalid_user($req_access_level);
    
        $this->load->helper('form'); 
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('slug', 'URL Slug', 'required');
        $this->form_validation->set_rules('content', 'Content', 'required');
        
        if ($this->form_validation->run() === TRUE){
            $this->messages->add_messages('success',$_POST['title'] . ' has been updated');
            if ($id){
                $this->blog_model->update($_POST);
            } else {
                $insert_id = $this->blog_model->insert($_POST,'id');
                redirect("blog/write/$insert_id/success");
            }
        }
        
        if ($id){
            $this->data['entry'] = $this->blog_model->get_single('id',$id);
            $this->data['title'] = 'Edit Entry';
        } else {
            $this->data['title'] = 'Add Entry';   
        }
        $this->layout->render(array('blog/write'), $this->data);
	}
    
    
    
	public function comments(){
        $this->layout->render('blog/comments');
	}
}
?>