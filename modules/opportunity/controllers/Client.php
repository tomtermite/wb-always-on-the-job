<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('opportunity_model');

        hooks()->do_action('after_clients_area_init', $this);
    }

    public function index()
    {
        if (!is_client_logged_in() && !has_contact_permission('opportunity')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }

        $data                           = [];
        $data['title']                  = _l('opportunity');
        /********
         * 
         * We have can Comany id to Contact id as employee
         */
        $clientid                       = $this->session->userdata('client_user_id');
        
        $data['opportunities']          = $this->opportunity_model->get_opportunity($clientid);
       
        $this->data($data);
        $this->view('opportunity/clients/view');
        $this->layout();
    }

    public function report($id)
    {
        if (!is_client_logged_in() && !has_contact_permission('opportunity')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }

        $data                           = [];
        $data['opportunity']            = $this->opportunity_model->get_opportunity_data($id);
        $data['requirements']           = $this->opportunity_model->get_requirement_data($id);
        $data['title']                  = _l('report');
        $data['bodyclass']              = 'viewreport';
        $data['client_flag']            = 1;

        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $this->disableNavigation();
        $this->disableSubMenu();
        $this->data($data);
        $this->view('opportunity/clients/report');
        no_index_customers_area();
        $this->layout();
        // $this->load->view('opportunity/clients/report', $data); 
    }
}
