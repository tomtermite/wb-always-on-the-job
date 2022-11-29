<?php

defined('BASEPATH') or exit('No direct script access allowed');

class opportunity_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->insert(db_prefix() . 'opportunity', $data);

        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'opportunity');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function get($id)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $opportunity = $this->db->get(db_prefix() . 'opportunity')->row();

            if ($opportunity) {
                return $opportunity;
            }
            return null;
        }
    }

    public function edit($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'opportunity')->row();
    }

    public function update($data, $id)
    {
        if (!empty($data)) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'opportunity', $data);
            $update_id = $id;
            return $update_id;
        }
    }

    public function add_requirement($data)
    {
        $requirement_data = [];
        $requirement_data['requirement']        = $data['requirement'];
        $requirement_data['risks']              = $data['risks'];
        $requirement_data['risks_priority']     = $data['risks_priority'];
        $requirement_data['impacts']            = $data['impacts'];
        $requirement_data['impacts_priority']   = $data['impacts_priority'];
        $requirement_data['action_items']       = $data['action_items'];
        $requirement_data['gap_yes_no']         = $data['settings']['gap_yes_no'];
        $requirement_data['opportunity_id']     = $data['opportunity_id'];

        $this->db->insert(db_prefix() . 'requirement', $requirement_data);

        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    public function delete_requirement($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'requirement');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function update_requirement($data, $id)
    {
        if (!empty($data)) {

            $requirement_data = [];
            $requirement_data['requirement']        = $data['requirement'];
            $requirement_data['risks']              = $data['risks'];
            $requirement_data['risks_priority']     = $data['risks_priority'];
            $requirement_data['impacts']            = $data['impacts'];
            $requirement_data['impacts_priority']   = $data['impacts_priority'];
            $requirement_data['action_items']       = $data['action_items'];
            $requirement_data['gap_yes_no']         = $data['settings']['gap_yes_no'];
            $requirement_data['opportunity_id']     = $data['opportunity_id'];

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'requirement', $requirement_data);
            $update_id = $id;
            return $update_id;
        }
    }

    public function get_requirement($id)
    {
        if (is_numeric($id)) {
            $this->db->where('opportunity_id', $id);
            $requirement = $this->db->get(db_prefix() . 'requirement')->result_array();

            if ($requirement) {
                return $requirement;
            }
            return null;
        }
    }

    public function get_opportunity($clientid)
    {
        if (is_numeric($clientid)) {
            $this->db->where('clientid', $clientid);

            $opportunity = $this->db->get(db_prefix() . 'opportunity')->result_array();
            if ($opportunity) {
                return $opportunity;
            } else {
                return null;
            }
        }
    }

    public function get_opportunity_data($id)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $opportunity_data = $this->db->get(db_prefix() . 'opportunity')->row();

            if ($opportunity_data) {
                return $opportunity_data;
            } else {
                return null;
            }
        }
    }

    public function get_requirement_data($id)
    {
        if (is_numeric($id)) {
            $this->db->where('opportunity_id', $id);
            $requirement = $this->db->get(db_prefix() . 'requirement')->result_array();

            if ($requirement) {
                return $requirement;
            }
            return null;
        }
    }

    public function get_opportunity_status()
    {
        $opportunity_status = $this->db->get(db_prefix() . 'opportunity_status')->result_array();
        if ($opportunity_status) {
            return $opportunity_status;
        }
        return null;
    }

    public function get_opportunity_status_id_data()
    {
        return $this->db->query('SELECT opportunity_status_id FROM ' . db_prefix() . 'opportunity WHERE opportunity_status_id != 0')->result_array();
    }
}
