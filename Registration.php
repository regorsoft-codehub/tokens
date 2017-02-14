<?php 

class Registration extends CI_Controller
{
    function __construct() {            
            parent::__construct();
            $this->load->model('auth');
    }
    
    public function client()
    {
        if(1)
        {
 
           $this->form_validation->set_rules('name', 'name', 'trim|required|min_length[1]|max_length[30]'); 
           $this->form_validation->set_rules('surname', 'surname', 'trim|required|min_length[1]|max_length[30]'); 
           $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[1]|max_length[30]|valid_email|is_unique[users.email]'); 
           $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[75]'); 
           $this->form_validation->set_rules('nationality', 'nationality', 'trim|required|min_length[1]|max_length[40]'); 
           
           $this->form_validation->set_rules('idcardno', 'ID card number', 'trim|required|min_length[4]|max_length[20]'); 
           $this->form_validation->set_rules('nnumber', 'National number', 'trim|required|min_length[3]|max_length[20]'); 
           $this->form_validation->set_rules('country', 'country', 'trim|required|min_length[3]|max_length[20]'); 
           $this->form_validation->set_rules('city', 'city', 'trim|required|min_length[1]|max_length[30]'); 
           $this->form_validation->set_rules('region', 'region', 'trim|required|min_length[2]|max_length[30]'); 
           $this->form_validation->set_rules('pc', 'Postal code', 'trim|required|min_length[3]|max_length[10]'); 
           
           $this->form_validation->set_message('is_unique', 'The %s is already taken, choose another!');
           
           if ($this->form_validation->run() == FALSE)
                {   
                        $this->load->view('header/header');
                        $this->load->view('templates/registration');
                        
                }
                else
                {
                        // checks postcode if present will add into client tbl if not, first will add into postal_code tbl n then add the id into client tbl
                        $postcode = $this->auth->is_postcode_exist($this->input->post('pc'));
                       
                        $data=[
                                
                                'name'      =>     $this->input->post('name'),
                                'surname'   =>     $this->input->post('surname'),
                                'email'   =>     $this->input->post('email'),
                                'nationality'   =>     $this->input->post('nationality'),
                                'idcard_number'   =>     $this->input->post('idcardno'),
                                'national_number'   =>     $this->input->post('nnumber'),
                                'country'   =>     $this->input->post('country'),
                                'city'   =>     $this->input->post('city'),
                                'birthplace'   =>     $this->input->post('bp'),
                                'birthdate'   =>     "DOB",
                                'state'   =>     $this->input->post('region'),
                                'client_id' =>   'CL-'.rand(10,1000000),
                                'street1'   =>     $this->input->post('sn'),
                                'street2'   =>     $this->input->post('street2'),
                                'surname'   =>     $this->input->post('surname'),
                                'street_number'   =>     25,
                                'postal_code_id'   =>     $postcode,
                                
                               ];
                        
                        $query = $this->db->insert('clients',$data);
                        $insert_id = $this->db->insert_id();
                        
                        if($insert_id)
                        {
                            $data=[
                                'user_id'    =>     $insert_id,
                                'user_level' => 4,
                                'email'      =>     $this->input->post('email'),
                                'password'   =>  md5($this->input->post('password'))
                               ];
                            
                            $query = $this->db->insert('users',$data);// insert into users
                        }
                        
                          $this->session->set_flashdata('success', 'Please login, successfully registered.!');
                          redirect(base_url('login'), 'refresh');
//                        $this->load->view('header/header');
//                        $this->load->view('templates/registration');
                }
           
            
        }
   
    }
}