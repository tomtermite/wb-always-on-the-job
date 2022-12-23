<?php
defined('BASEPATH') or exit('No direct script access allowed');
hooks()->add_action('after_email_templates', 'add_document_share_email_templates');

/**
 * Get all staff by department
 * @param  string $departmentid Optional
 * @return array
 */
function get_all_staff_by_department_document($departmentid) {
	$CI = &get_instance();
	if ($departmentid) {
		$CI->db->where('departmentid', $departmentid);
		$staffids = $CI->db->select('staffid')->from(db_prefix() . 'staff_departments')->get()->result_array();
	} else {
		$staffids = [];
	}

	return $staffids;
}

/**
 * Get all client by group
 * @param  string $groupid Optional
 * @return array
 */
function get_all_client_by_group_document($groupid) {
	$CI = &get_instance();
	if ($groupid) {
		$CI->db->where('groupid', $groupid);
		$clientids = $CI->db->get(db_prefix() . 'customer_groups')->result_array();
	} else {
		$clientids = [];
	}
	return $clientids;
}

if (!function_exists('add_document_share_email_templates_document')) {
	/**
	 * Init appointly email templates and assign languages
	 * @return void
	 */
	function add_document_share_email_templates_document() {
		$CI = &get_instance();

		$data['document_share'] = $CI->emails_model->get(['type' => 'document_online', 'language' => 'english']);

		$CI->load->view('document/email_templates', $data);
	}
}

/**
 * { email staff }
 *
 * @param        $staffid  The staffid
 *
 * @return      ( description_of_the_return_value )
 */
function document_email_staff_document($staffid) {
	$CI = &get_instance();
	$CI->db->where('staffid', $staffid);
	return $CI->db->get(db_prefix() . 'staff')->row()->email;
}

/**
 * replace document value
 * @param  $string
 * @return string
 */
function replace_document_value_document($string) {

	$findme = 'images';
	$pos = strpos($string, $findme);
	$data_string = "";
	if ($pos) {
		$data_string = str_replace('""', '"', $string);
	} else {
		$data_string = $string;
	}

	$data_string = str_replace('"color":",', '"color":"",', $data_string);
	$data_string = str_replace('"value2":",', '"value2":"",', $data_string);
	$data_string = str_replace('":",', '":"",', $data_string);
	$data_string = str_replace('":"},', '":""},', $data_string);
	$data_string = str_replace('":"}},', '":""}},', $data_string);
	$data_string = str_replace('"\'', '"\\\'', $data_string);
	$data_string = str_replace(':"\"', ':"', $data_string);
	$data_string = str_replace('\","fc', '","fc', $data_string);
	$data_string = str_replace('"fa":"$\"#,', '"fa":"$#,', $data_string);
	$data_string = str_replace(':"}}},{"', ':""}}},{"', $data_string);

	$data_string = str_replace('\","', '","', $data_string);
	$data_string = str_replace(')\","', ')","', $data_string);
	$data_string = str_replace('":"}}}]', '":""}}}]', $data_string);
	$data_string = str_replace('":"}}]', '":""}}]', $data_string);
	$data_string = str_replace('\\" #', ' #', $data_string);
	$data_string = str_replace('€\\"', '€', $data_string);

	return $data_string;
}
/**
 * file force contents
 * @param string $filename <p>file name including folder.
 * example :: /path/to/file/filename.ext or filename.ext</p>
 * @param string $data <p> The data to write.
 * </p>
 * @param int $flags same flags used for file_put_contents.
 * @return bool <b>TRUE</b> file created succesfully <br> <b>FALSE</b> failed to create file.
 */
function file_force_contents_document($filename, $data, $flags = 0) {
	if (!is_dir(dirname($filename))) {
		mkdir(dirname($filename) . '/', 0777, TRUE);
	}

	return file_put_contents($filename, $data, $flags);
}