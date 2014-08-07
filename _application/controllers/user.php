<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Geoffrey Burdett
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
        $this->data['users'] = $this->user_model->get_all();
        $this->data['title'] = 'Manage Users';
        $this->layout->render('user/manage', $this->data);
    }
    
    
    
    public function edit($id = FALSE,$success = FALSE){
        $this->session->redirect_invalid_user(40);
        
        $this->load->helper('form'); 
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('first_name',   'First Name',    'trim|required');
        $this->form_validation->set_rules('last_name',    'Last Name',     'trim|required');
        $this->form_validation->set_rules('username',     'Username',      'trim|required|min_length[5]|is_unique[users.username.id.' . $id . ']');
        $this->form_validation->set_rules('email',        'Email Address', 'trim|required|valid_email|is_unique[users.email.id.' . $id . ']');
        $this->form_validation->set_rules('access_level', 'Access Level',  'trim|required');    
        if (! $id){   
            $this->form_validation->set_rules('username', 'Username',      'trim|required|min_length[5]|is_unique[users.username]');
            $this->form_validation->set_rules('email',    'Email Address', 'trim|required|valid_email|is_unique[users.email]');
        }
        
        $user_data = $_POST;
        if ($this->form_validation->run() === TRUE){
            if ($id){
                $this->user_model->update($user_data);
                $this->data['success'][] =  'The user account for ' . $user_data['first_name'] . ' ' . $user_data['last_name'] . ' has been updated.';
            } else {
                $user_data['password'] = md5($this->user_model->random_password());
                $insert_id = $this->user_model->insert($user_data);
                
                $this->load->library('email');
                $email_data = array(
                    'to_name' => $user_data['first_name'],
                    'username' => $user_data['username'],
                    'password' => $user_data['password'],
                    'header_title' => 'Welcome to Odder Otter',
                );
                $this->email->from('info@geoffreydesigns.com', 'Geoffrey Burdett')
                            ->to($user_data['email'])
                            ->subject($email_data['header_title'])
                            ->message($this->layout->render('templates/email_new_user', $email_data, 'email', TRUE))
                            ->send();
                            
                $this->messages->add_messages('success','A user account for ' . $user_data['first_name'] . ' ' . $user_data['last_name'] . ' has been created.');
                redirect("user/edit/$insert_id");
            }
        }
                 
        if ($id){
            $this->data['user'] = $this->user_model->get_single('id',$id);
            $this->data['title'] = 'Edit User';
        } else {
            $this->data['title'] = 'Add User';
        }
        
        $this->layout->render('user/edit', $this->data);
    }
    
    
    
    public function delete($id){
        $this->session->redirect_invalid_user(40);
        $user_data = $this->user_model->get_single('id',$id);
                 
        if (! $id){
            redirect('user/manage');
        }
                 
        if (@$_POST['confirm_delete_' . $id]){
            $this->user_model->delete('id',$id);                
            $this->messages->add_messages('success','The user account for ' . $user_data['first_name'] . ' ' . $user_data['last_name'] . ' has been deleted.');
            redirect('user/manage');
        }
        
        $this->load->helper('form'); 
        $this->data['user'] = $user_data;
        $this->data['title'] = 'Delete User';
        $this->layout->render('user/delete', $this->data);
    }
    
    
    
    public function account(){
    }
}