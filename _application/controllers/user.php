<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login controller class
 */
class User extends ODD_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->model('user_model');
    }
    
    
    
    public function index(){
        $this->login();   
    }
    
    

    public function login($error = NULL){
        $this->session->keep_flashdata('redirect_current');
        $this->session->keep_flashdata('redirect_last');
        $this->data['title'] = 'User Login';
        $this->layout->render('user/login', $this->data);
    }
    
    
    
    public function process(){
        $result = $this->user_model->validate();
        $this->session->keep_flashdata('redirect_current');
        $this->session->keep_flashdata('redirect_last');
        if(! $result){
            // If user did not validate, then show them login page again
            $this->messages->add_messages('error','Invalid username or password.');
            redirect('/user/login/failed');
        } else {
            $this->messages->add_messages('success','Welcome, ' .$this->session->userdata('first_name'));
            $redirect = $this->session->flashdata('redirect_current');
            redirect($redirect);
        }        
    }
    
    
    
    public function logout(){
        $this->session->sess_destroy();
        redirect('user/login');
    }
    
    
    
    public function manage($slug = FALSE){
        $this->session->redirect_invalid_user(40);
        $data['users'] = $this->user_model->get_all();
        $data['title'] = 'Manage Users';
        $this->layout->render('user/manage', $data);
    }
    
    
    
    public function edit($id = FALSE,$success = FALSE){
        $this->session->redirect_invalid_user(40);
        
        $this->load->helper('form'); 
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('first_name',   'First Name',    'trim|required');
        $this->form_validation->set_rules('last_name',    'Last Name',     'trim|required');
        $this->form_validation->set_rules('username',     'Username',      'trim|required|min_length[5]');
        $this->form_validation->set_rules('email',        'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('access_level', 'Access Level',  'trim|required');    
        if (! $id){   
            $this->form_validation->set_rules('username', 'Username',      'trim|required|min_length[5]|is_unique[users.username]');
            $this->form_validation->set_rules('email',    'Email Address', 'trim|required|valid_email|is_unique[users.email]');
        }
        
        $user_data = $_POST;
        if ($this->form_validation->run() === TRUE){
            if ($id){
                $this->user_model->update($user_data);
            } else {
                $new_password = $this->user_model->random_password();
                $user_data['password'] = md5($new_password);
                $insert_id = $this->user_model->insert($user_data);
                $this->load->library('email');
                
                $email_data = array(
                    'to_name' => $user_data['first_name'],
                    'username' => $user_data['username'],
                    'password' => $new_password,
                    'header_title' => 'Welcome to Odder Otter',
                );
                $this->email->from('info@geoffreydesigns.com', 'Geoffrey Burdett')
                            ->to($user_data['email'])
                            ->subject($email_data['header_title'])
                            ->message($this->layout->render('templates/email_new_user', $email_data, 'email', TRUE))
                            ->send();
                            
                redirect("user/edit/$insert_id/success");
            }
            $success = TRUE;
        }
                 
        if ($id){
            $data['user'] = $this->user_model->get_single('id',$id);
            $data['title'] = 'Edit User';
        } else {
            $data['title'] = 'Add User';
        }
        $data['success'] = $success; 
        $this->layout->render('user/edit', $data);
    }
    
    
    
    public function delete($id){
        $this->session->redirect_invalid_user(40);
                 
        if (! $id){
            redirect('user/manage');
        }
                 
        if (@$_POST['confirm_delete_' . $id]){
            $this->user_model->delete('id',$id);
            redirect('user/manage');
        }
        
        $this->load->helper('form'); 
        $data['user'] = $this->user_model->get_single('id',$id);
        $data['title'] = 'Delete User';
        $this->layout->render('user/delete', $data);
    }
    
    
    
    public function account(){
    }
}