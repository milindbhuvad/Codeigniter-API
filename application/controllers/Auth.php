<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
     
class Auth extends REST_Controller {
    
	public function __construct() {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->load->helper('security');
    }
      
     
    public function user_post()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        if($this->form_validation->run('addUser')){
            
            $user = $this->input->post('user');
            $userResult = $this->user_model->checkUserExists($user);
            if(count($userResult)>0){
                $this->response(array(
                    "status"=>"failed",
                    "response"=>"The user already exists"
                ), REST_Controller::HTTP_BAD_REQUEST);
            }else{
                $post = $this->input->post();
                $post['password'] = md5($this->input->post('password'));            
                $post['status'] = 'Y';

                $result = $this->user_model->insertUser($post);
                if($result){
                    $this->response(array(
                        "status"=>"success",
                        "response"=>"User added successfully"
                    ), REST_Controller::HTTP_OK);
                }else{
                    $this->response(array(
                        "status"=>"failed",
                        "response"=>'Something went wrong! Please try again.'
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }
        else{
            $this->form_validation->set_error_delimiters('','');

            if(!empty(form_error('user'))){
                $this->response(array(
                    "status"=>"failed",
                    "response"=>form_error('user')
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
            elseif(!empty(form_error('email'))){
                $this->response(array(
                    "status"=>"failed",
                    "response"=>form_error('email')
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
            elseif(!empty(form_error('password'))){
                $this->response(array(
                    "status"=>"failed",
                    "response"=>form_error('password')
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

	public function user_get($id = 0)
	{        
        if(!empty($id)){
            $data = $this->user_model->getUserByID($id);
            if($data){
                $this->response(array(
                    "status"=>"success",
                    "response"=>$data
                ), REST_Controller::HTTP_OK);
            }else{
                $this->response(array(
                    "status"=>"failed",
                    "response"=>"Data not found"
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $data = $this->user_model->getUserList();
            if(count($data)>0){
                $this->response(array(
                    "status"=>"success",
                    "response"=>$data
                ), REST_Controller::HTTP_OK);
            }else{
                $this->response(array(
                    "status"=>"failed",
                    "response"=>"Data not found"
                ), REST_Controller::HTTP_NOT_FOUND);
            }
        }     
        
	}
      
        
    public function user_put()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);    
        $this->form_validation->set_data($_POST);        
        if($this->form_validation->run('updateUser')){
            $post = $this->input->post();
            $user = $this->input->post('user');
            $id = $this->input->post('id');
            unset($post['id']);
            
            $query = $this->user_model->checkUserExistsBYIdName($id, $user);
            if($query){
                $this->response(array(
                    "status"=>"failed",
                    "response"=>"User already exists"
                ), REST_Controller::HTTP_BAD_REQUEST);
            }else{
                $result = $this->user_model->updateUser($id, $post);
                if($result){
                    $this->response(array(
                        "status"=>"success",
                        "response"=>"User updated successfully"
                    ), REST_Controller::HTTP_OK);
                }else{
                    $this->response(array(
                        "status"=>"failed",
                        "response"=>"Something went wrong! Please try again"
                    ), REST_Controller::HTTP_BAD_REQUEST);
                }    
            }                
        }else{
            $this->form_validation->set_error_delimiters('','');
            if(!empty(form_error('id'))){
                $this->response(array(
                    "status"=>"failed",
                    "response"=>form_error('id')
                ), REST_Controller::HTTP_BAD_REQUEST);
            }
        } 
    }
     

    public function user_delete($id)
    {
        $result = $this->user_model->deleteUser($id);
        if($result){
            $this->response(array(
                "status"=>"success",
                "response"=>"User deleted successfully"
            ), REST_Controller::HTTP_OK);
        }else{
            $this->response(array(
                "status"=>"failed",
                "response"=>"Something went wrong! Please try again"
            ), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function valid_password($password = ''){
        $password = trim($password);

        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';

        if (empty($password))
        {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');

            return FALSE;
        }

        if (preg_match_all($regex_lowercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');

            return FALSE;
        }

        if (preg_match_all($regex_uppercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');

            return FALSE;
        }

        if (preg_match_all($regex_number, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');

            return FALSE;
        }

        if (preg_match_all($regex_special, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>ยง~'));

            return FALSE;
        }

        if (strlen($password) < 5)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 5 characters in length.');

            return FALSE;
        }

        if (strlen($password) > 32)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');

            return FALSE;
        }

        return TRUE;
    }
    	
}