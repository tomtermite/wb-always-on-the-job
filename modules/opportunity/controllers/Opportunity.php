<?php

use Braintree\Xml;

defined('BASEPATH') or exit('No direct script access allowed');

class Opportunity extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('opportunity_model');
    }

    public function index()
    {
        if (!has_permission('opportunity', '', 'index')) {
            access_denied('Opportunity View');
        }
        $data['title']                  = _l('opportunity');
        $data['statuses']               = $this->opportunity_model->get_opportunity_status();

        $this->load->view('opportunity', $data);
    }

    public function table()
    {
        $this->app->get_table_data(module_views_path('opportunity', 'includes/table_opportunity'), ['opportunity_status_id' => $this->input->post('opportunity_status_id')]);
    }

    public function requirement_table()
    {
        $this->app->get_table_data(module_views_path('opportunity', 'includes/table_requirement'), ['opportunity_id' => $this->input->post('opportunity_id')]);
    }

    public function add($id = '')
    {
        if ($id == '') {
            $data['title']       = _l('Add');
        } else {
            $data['title']       = _l('Edit');
            $data['opportunity'] = $this->opportunity_model->edit($id);
        }

        $this->load->view('opportunity_form', $data);
    }

    public function insert()
    {
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();

            $data['solicitation_created'] = date('Y-m-d H:i:s');

            unset($data['solicitation_url_checkbox']);

            if (!$this->input->post('id')) {

                $id = $this->opportunity_model->add($data);

                if ($id) {

                    $success = true;
                    $message = _l('added_successfully', _l('opportunity'));
                    set_alert('success', $message);

                    redirect(admin_url('opportunity/view/' . $id));
                }
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
            } else {
                $id = $this->input->post('id');

                $id = $this->opportunity_model->update($data, $id);

                if ($id) {

                    $success = true;
                    $message = _l('updated_successfully', _l('opportunity'));
                    set_alert('success', $message);

                    redirect(admin_url('opportunity/view/' . $id));
                }
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
            }
            die;
        }
    }

    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('opportunity'));
        }
        $response = $this->opportunity_model->delete($id);

        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('opportunity_location')));
        } elseif (true == $response) {
            set_alert('success', _l('deleted', _l('opportunity_location')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('opportunity_location')));
        }
        redirect(admin_url('opportunity'));
    }

    public function view($id)
    {
        if (!has_permission('opportunity', '', 'view')) {
            access_denied('Opportunity View');
        }
        if ($id) {
            $data['opportunity'] = $this->opportunity_model->get($id);
            $data['title']       = _l($data['opportunity']->solicitation_title);
            $data['group']              = $_GET['group'];
            $data['requirements'] = $this->opportunity_model->get_requirement($id);
            $data['default_opportunity_list_statuses'] = [1, 2, 4];
            $this->load->view('view', $data);
        } else {
            access_denied('Opportunity View');
        }
    }

    public function get_opportunity_data_ajax($opportunity_id)
    {
        $data['opportunity'] = $this->opportunity_model->get($opportunity_id);
        $data['title']       = _l($data['opportunity']->solicitation_title);
        $data['requirements'] = $this->opportunity_model->get_requirement($opportunity_id);

        $this->load->view('includes/opportunity_report', $data);
    }

    public function requirement_insert()
    {
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();
            $gap_yes_no = '';
            if (isset($data['settings']['gap_yes_no'])) {
                $gap_yes_no = $data['settings']['gap_yes_no'];

                if ($gap_yes_no == 1) {
                    unset($gap_yes_no);
                    $gap_yes_no = 'Yes';
                } else {
                    unset($gap_yes_no);
                    $gap_yes_no = 'No';
                }
            }

            $data['settings']['gap_yes_no'] = $gap_yes_no;

            if (!$this->input->post('id')) {

                $id = $this->opportunity_model->add_requirement($data);

                if ($id) {

                    $success = true;
                    $message = _l('added_successfully', _l('requirement'));
                    set_alert('success', $message);

                    redirect(admin_url('opportunity/view/' . $data['opportunity_id'] . '?group=opportunity_requirement'));
                }
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
            } else {

                $id = $this->input->post('id');

                $id = $this->opportunity_model->update_requirement($data, $id);

                if ($id) {

                    $success = true;
                    $message = _l('updated_successfully', _l('requirement'));
                    set_alert('success', $message);

                    redirect(admin_url('opportunity/view/' . $data['opportunity_id'] . '?group=opportunity_requirement'));
                }
                echo json_encode([
                    'success'              => $success,
                    'message'              => $message,
                ]);
            }
            die;
        }
    }

    public function delete_requirement($id, $opportunity_id)
    {
        if (!$id) {
            redirect(admin_url('opportunity/view/' . $opportunity_id . '?group=opportunity_requirement'));
        }
        $response = $this->opportunity_model->delete_requirement($id);

        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('requirement_location')));
        } elseif (true == $response) {
            set_alert('success', _l('deleted', _l('requirement_location')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('requirement_location')));
        }
        redirect(admin_url('opportunity/view/' . $opportunity_id . '?group=opportunity_requirement'));
    }
    public function pdf($id)
    {
        if (!$id) {
            redirect(admin_url('opportunity'));
        }


        $data['opportunity'] = $this->opportunity_model->get($id);
        $data['title']       = _l($data['opportunity']->solicitation_title);
        $data['requirements'] = $this->opportunity_model->get_requirement($id);

        try {
            // $pdf = invoice_pdf($invoice);
            $pdf = app_pdf('invoice', module_libs_path('opportunity', 'Opportunity_pdf'), $data, '');
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(mb_strtoupper(slug_it($data['title'])) . '.pdf', $type);
    }
    public function send_to_email($id)
    {
        if ($this->input->post()) {


            $data = $this->input->post();
            $data['opportunity'] = $this->opportunity_model->get($id);
            $data['title']       = _l($data['opportunity']->solicitation_title);
            $data['requirements'] = $this->opportunity_model->get_requirement($id);

            $pdf = app_pdf('invoice', module_libs_path('opportunity', 'Opportunity_pdf'), $data);
         
            $statementPdfFileName = str_replace(' ','_',$data['opportunity']->solicitation_title)  .'_'. $data['opportunity']->solicitation_number;
            $attachStatementPdf = $pdf->Output($statementPdfFileName . '.pdf', 'S');


            $sender_id = $data['sent_to'];
            $email = $this->clients_model->get_contact($sender_id[0]);
            $staff_id = get_staff_user_id();
            $old_cc = $data['cc'];
            $sender_name = get_staff($staff_id);
            $inbox = array();

            $inbox['to'] = $email->email;
            $inbox['sender_name'] = $sender_name->full_name;
            $inbox['subject'] = _strip_tags($data['subject']);
            $inbox['body'] = _strip_tags($data['email_template_custom']);
            $inbox['cc'] = $old_cc;
            $inbox['date_received'] = date('Y-m-d H:i:s');
            $inbox['from_email'] = $sender_name->email;

            if (strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0) {


                $this->email->initialize();
                $this->load->library('email');
                $this->email->clear(true);
                $this->email->from($inbox['from_email'], $inbox['sender_name']);
                $this->email->to($inbox['to']);
                $this->email->cc($inbox['cc']);
                $this->email->subject($inbox['subject']);
                $this->email->message($inbox['body']);
                $this->email->attach($attachStatementPdf, 'attachment', $statementPdfFileName . '.pdf', 'application/pdf');
                $this->email->send(true);
                set_alert('success', _l('email', _l('requirement_location')));
                
                $data_insert = array(
                    'opportunity_id' => $id,
                    'cc' =>$inbox['cc']
                ); 
            
                $this->db->insert('tblopportunity_mail', $data_insert);

            } else {
                set_alert('warning', _l('email_deleting', _l('requirement_location')));
            }
            redirect(admin_url('opportunity'));
        }
    }
}
