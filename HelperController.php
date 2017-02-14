<?php

class helperController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper($this->uri->segment(1)); // geo helper in this example

        if($this->uri->segment(2))
        {
            $helper_method = $this->uri->segment(2);
        }
        else
        {
//            show_404();
//            return false;
        }

        // check if helper has function named after segment 2, function citiesNearZip($zip) in this example...
        if(function_exists($helper_method))
        {
            // Execute function with provided uri params, xss filter, secure, etc...
            // You would also want to grab all the remaining uri params and pass them as 
            // arguments to your helper function
            $helper_method();
        }
    }
}
