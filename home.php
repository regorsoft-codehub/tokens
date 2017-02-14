<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function index()
    {
       
        $this->load->view('templates/home_view');
    }
    public function registration()
    {
        $this->load->view('header/header');
        $this->load->view('templates/registration');
    }
}

