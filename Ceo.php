<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//CEO or Super admin.
class Ceo extends CI_Controller
{
    function __construct() {            
            parent::__construct();
            $this->load->model('auth');
    }
    
    public function index()
    {
         
        if(isset($_SESSION['loggedIn']))
        {
        redirect('dashboard','refresh');
        }
        else
        {
        $this->load->view('templates/login_ceo_view');
        }
    }
    
    //Super admin login
    public function login()
    {
        if($this->input->post('email') && $this->input->post('password'))
        {
            $query = $this->db->get_where('users', array('email' => $this->input->post('email'), 'password'=> md5($this->input->post('password', TRUE))), 1);
           
            if($this->db->affected_rows()>=1)
            {
            $ret = $query->row();
                if($ret->user_level==1)
                {
                $this->session->set_userdata('loggedIn', $ret->user_level);
                redirect('dashboard', 'refresh');
                }
                else
                {
                   $this->session->set_flashdata('error',"only admin login is allowed.<br> ACCESS DENIED!"); 
                   redirect('ceo', 'refresh');  
                }
            }
            else {
            $this->session->set_flashdata('error',"Please enter correct credentials.!");
            redirect('ceo', 'refresh');
            }
        }
        else
        {
            $this->session->set_flashdata('error',"input can't be empty");
            redirect('ceo', 'refresh');
        }
        
    }
}

