<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
     function __construct() {            
            parent::__construct();
            $this->load->model('auth');
            $this->load->model('tokens_model');
            $this->load->model('user_model');
            only_allow_loggedIn();
    }
    public function index()
    {
        
       
        $this->load->view('header/header');
        $this->load->view('templates/dashboard_view');
        
        
    }
    
    public function viewtokens($offset = 0)
    {    
      $data['allTokens'] = set_pagi_n_get(base_url('dashboard/viewtokens'), 4, $offset,'tokens');
      $this->load->view('header/header');
      $this->load->view('templates/ceo_tokens_view', $data);  
    }
    
    public function checkout($offset = 0)
    {    
      //$data['allTokens'] = set_pagi_n_get(base_url('dashboard/viewtokens'), 4, $offset,'tokens');
      //$this->user_model->get_user_byid(10, 'clients');    
      $cart = $this->session->userdata('test');
                
        $this->db->select('*');
        $this->db->where_in('id', $cart);
        $query = $this->db->get('tokens');
        $data['allTokens'] =  $query->result();
        
                
      $this->load->view('header/header');
      $this->load->view('templates/checkout_view', $data);  
    }
    
    public function delete()
    {
      $tokenUkey = $this->uri->segment(3);  
      (isset($tokenUkey))?$this->_delete_token($tokenUkey):false;
    }
    
    
    public function addtoken()
    {
      only_allow_Admin();  
      $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[5]|max_length[30]'); 
      $this->form_validation->set_rules('price', 'price', 'trim|required|integer|min_length[1]|max_length[6]'); 
      $this->form_validation->set_rules('description', 'description', 'trim|required|min_length[1]|max_length[30]'); 
      
      
      if ($this->form_validation->run() == FALSE)
        {  
            
          $this->load->view('header/header');
          $this->load->view('templates/ceo_add_tokens_view');    
          $this->session->set_flashdata('error', 'something went wrong please try once again!');
        }
        else
        {
         
            $data=[
                        'token_name'           =>      strip_tags($this->input->post('title')),
                        'token_id'           =>      "TK-".rand(100,10000),
                        'price'           =>      strip_tags($this->input->post('price')),
                        'description'     =>      strip_tags($this->input->post('description')),
                        'ukey'           =>      md5(uniqid(rand().microtime(), true)),
                    ];
                        
            $query = $this->db->insert('tokens',$data);
            $rows= $this->db->affected_rows();
            
            if($rows>0)
            $this->session->set_flashdata('success', 'token added successfully!');    
            else
            $this->session->set_flashdata('error', 'something went wrong please try once again!'); 
            
            
            redirect('dashboard/addtoken','refresh');
            
        }
       
    }
    function _delete_token($ukey)
    {
        only_allow_Admin();
        echo $ukey;
        $this->db->where('ukey', $ukey);
        $this->db->delete('tokens'); 
        if($this->db->affected_rows()>0)
        {
            $this->session->set_flashdata('success', 'token deleted successfully!');  
        }
        else
        {
            $this->session->set_flashdata('error', 'invalid token ukey!');  
        }
        redirect(base_url('dashboard/viewtokens'),'refresh');
    }
    function showcart()
    {
//        $status = $this->input->post('status');
        if(isset($_SESSION['test']))
        {
        $cart = $this->session->userdata('test');
            if(count($_SESSION['test'])==0)
            {
                 echo json_encode(array("cart"=>'<center><b>Cart is empty!</b></center>'));
            }
            else
            {
                
                $this->db->select('*');
                $this->db->where_in('id', $cart );
                $query = $this->db->get('tokens');
                $data['cart_items'] =  $query->result_array();
                $cart=$this->load->view('templates/ajax_cart_view',$data,true);
                
                echo json_encode(array("cart"=>$cart));
            }
        }
        else
        {
        echo json_encode(array("cart"=>'empty'));
        }
    }
    function addcart()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        
        $data = $this->session->userdata('test');
        //$data = $this->session->userdata('test');$this->input->post('id')

         
        if(!$data)
        $data = [];
        // $pos = array_search($id, $data);
        // unset($data[$pos]);

        if($status=="remove")
        {
           $pos = array_search($id, $data);
           unset($data[$pos]);
           $status=0;
        }
        else
        {
           array_push($data,$id);
           $status=1;
        }
        // if(!$pos)
        // {
        
        // }
        $this->session->set_userdata('test',$data);
        $cart = $this->session->userdata('test');
        $count_items=count($cart);
        $cart1=$this->load->view('templates/ajax_cart_view','',true);
        echo json_encode(array("status"=>$status,"cart"=>$cart, "count"=>$count_items));



    }
    
}

