<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
     function __construct() {            
            parent::__construct();
            $this->load->model('auth');
            $this->load->model('user_model');
            if_loggedIn_redirect();
    }
    
    public function index()
    {
        if($this->input->post('email') && $this->input->post('password'))
        {
           $query = $this->db->get_where('users', array('email' => $this->input->post('email'), 'password'=> md5($this->input->post('password', TRUE))), 1);
           
           $rowsAffected = $this->db->affected_rows();
           
           
           
            if($rowsAffected >0)
            {
            $ret = $query->row();
            $this->session->set_userdata('loggedIn', $ret->user_level);  
            $userLevel= $ret->user_level;  
            
            //get user details for clients.
//            $user_data = $this->user_model->get_user_byid($ret->user_id, 'clients'); 
//            $this->session->set_userdata('loggedIn', $ret->user_level);
              switch ($userLevel) {
                  case 4:
                      redirect(base_url('dashboard'), 'refresh');
                      break;
                  default:
                      echo "Bar\n";
                      break;
              }
            redirect('dashboard', 'refresh');
            }
            else{
                
            $this->session->set_flashdata('error',"Please enter correct credentials.!");
            $this->load->view('templates/login_view');
            }
        }
        else
        {
           
            $this->load->view('templates/login_view');
        }
    }
}    

