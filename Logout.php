<?php

class Logout extends CI_Controller
{
   public function index()
   {
       $this->session->unset_userdata('loggedIn');
       $this->session->sess_destroy();
       $this->session->set_flashdata('success', 'logged out!');
       redirect('ceo','refresh');
   }
}

