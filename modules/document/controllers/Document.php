<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Class Document 
 */
class Document extends AdminController
{
	/**
	 * __construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('document_model');
		$this->load->model('departments_model');
		$this->load->model('clients_model');
		$this->load->model('staff_model');
	}

	/**
	 * manage
	 * @return view
	 */
	public function manage()
	{
		$data['title'] = _l('document');
		$data['tab'] = $this->input->get('tab');
		$data['departments'] = $this->departments_model->get();
		$data['staffs'] = $this->staff_model->get();
		$data['clients'] = $this->clients_model->get();
		$data['client_groups'] = $this->clients_model->get_groups();

		if ($data['tab'] == '') {
			$data['tab'] = 'my_folder';
		}
		if ($data['tab'] == 'my_folder') {
			$data['folder_my_tree'] = $this->document_model->tree_my_folder();
		}
		if ($data['tab'] == 'my_share_folder') {
			$data['folder_my_share_tree'] = $this->document_model->tree_my_folder_share();
		}
		$this->load->view('manage', $data);
	}

	/*
	get_my_folder()
	*/
	public function get_my_folder_list()
	{
		$response = [];
		$response['status'] = 1;

		$folder_my_tree = $this->document_model->tree_my_folder();
		$response['content'] = html_entity_decode($folder_my_tree);

		echo json_encode($response);
	}

	/**
	 * Add edit folder
	 */
	public function add_edit_folder()
	{
		$response = [];
		$response['status'] = 0;

		if ($this->input->post()) {
			$data = $this->input->post();
			if ($data['id'] == '') {
				$data['type'] = 'folder';
				$id = $this->document_model->add_folder($data);
				if (is_numeric($id)) {
					$response['status'] = 1;
					$response['message'] = _l('document_folder_added_successfully');
				} else {
					$response['error'] = _l('added_fail');
				}
			} else {
				$data['type'] = 'folder';
				if ($this->document_model->edit_folder($data)) {
					$response['status'] = 1;
					$response['message'] = _l('document_folder_updated_successfully');
				} else {
					$response['error'] = _l('updated_fail');
				}
			}

			echo json_encode($response);exit;
			// redirect(admin_url('document/manage?tab=my_folder'));
		}
	}

	/**
	 * Add edit file
	 */
	public function add_edit_file()
	{
		$response = [];
		$response['status'] = 0;

		if ($this->input->post()) {
			$data = $this->input->post();
			if ($data['id'] == '') {
				$data['type'] = 'file';
				$id = $this->document_model->add_folder($data);
				if (is_numeric($id)) {
					$response['status'] = 1;
					$response['message'] = _l('document_file_added_successfully');
				} else {
					$response['error'] = _l('added_fail');
				}
			} else {
				$data['type'] = 'file';
				$res = $this->document_model->edit_folder($data);
				if ($res == true) {
					$response['message'] = _l('document_file_updated_successfully');
				} else {
					$response['error'] = _l('updated_fail');
				}
			}
			//redirect(admin_url('document/manage?tab=my_folder'));
		}
		echo json_encode($response);exit;
	}

	public function document_online_setting()
	{
		$data = $this->input->post();
		if (isset($data['document_staff_notification'])) {
			if ($data['document_staff_notification'] = 'on') {
				update_option('document_staff_notification', 1);
			}
		} else {
			update_option('document_staff_notification', 0);
		}

		if (isset($data['document_email_templates_staff'])) {
			if ($data['document_email_templates_staff'] = 'on') {
				update_option('document_email_templates_staff', 1);
			}
		} else {
			update_option('document_email_templates_staff', 0);
		}

		if (isset($data['document_client_notification'])) {
			if ($data['document_client_notification'] = 'on') {
				update_option('document_client_notification', 1);
			}
		} else {
			update_option('document_client_notification', 0);
		}

		if (isset($data['document_email_templates_client'])) {
			if ($data['document_email_templates_client'] = 'on') {
				update_option('document_email_templates_client', 1);
			}
		} else {
			update_option('document_email_templates_client', 0);
		}

		redirect(admin_url('document/manage'));
	}

	/**
	 * [get_client_all description]
	 * @return [type] [description]
	 */
	public function get_related_id($id)
	{
		$data =  $this->document_model->data_related_id($id);
		echo json_encode($data);
	}

	/**
	 * new file view 
	 * @param  int $parent_id 
	 * @param  int $id        
	 * @return  view or json            
	 */
	public function new_file_view($parent_id, $id = "")
	{
		$data_form = $this->input->post();
		$data['title'] = _l('new_file');
		$data['parent_id'] = $parent_id;
		$data['role'] = "";
		// $data['departments'] = $this->departments_model->get();
		// $data['staffs'] = $this->staff_model->get();
		// $data['clients'] = $this->clients_model->get();
		// $data['client_groups'] = $this->clients_model->get_groups();

		if (!isset($success)) {
			$this->load->view('new_file_view', $data);
		}
	}

	/** table for new file */
	public function table()
	{
		$this->app->get_table_data(module_views_path('document', 'table_chapter'));
	}

	public function new_chapter($id)
	{
		$data['parent_id'] = $id;
		$this->load->view('new_chapter_view', $data);
	}

	public function add_chapter()
	{
		$result['status'] = 0;
		$version = 1;
		$data = $_POST;
		if ($data['id'] == '') {
			$id = $this->document_model->add_chapter($data);
			if ($id) {
				//This is to add version
				$version_data = [];
				$version_data['staff_id'] = get_staff_user_id();
				$version_data['document_chapter_id'] = $id;
				$version_data['version'] = $version;
				$version_data['description'] = $data['description'];
				$this->document_model->add_chapter_version($version_data);

				$result['status'] = 1;
				$result['message'] = _l('added_successfully');
				$result['version'] = $version;
				$result['id'] = $id;
			} else {
				$result['message'] = _l('added_fail');
			}
		} else {

			$chapter_data = $this->document_model->get_my_chapter_by_chapter_id($data['id']);

			$data['latest_version'] = $chapter_data->latest_version;
			$res = $this->document_model->edit_chapter($data);
			if ($res == true) {

				//This is to add version
				// if(isset($chapter_data->description) && $chapter_data->description != $data['description'])
				// {
					$version = $chapter_data->latest_version + 1;
					$version_data = [];
					$version_data['staff_id'] = get_staff_user_id();
					$version_data['document_chapter_id'] = $data['id'];
					$version_data['description'] = $data['description'];
					$version_data['version'] = $version;
					$this->document_model->add_chapter_version($version_data);
				// }

				$result['message'] = _l('document_updated_successfully');
				$result['status'] = 1;
				$result['version'] = $version;
				$result['id'] = $data['id'];
			} else {
				$result['message'] = _l('updated_fail');
			}
		}
		echo json_encode($result);
		die;
	}

	public function edit_chapter($id)
	{
		$all_data = $this->document_model->get_my_chapter_by_chapter_id($id);
		$data['id'] = $all_data->id;
		$data['parent_id'] = $all_data->document_folder_id;
		$data['file_name'] = $all_data->name;
		$data['pad_id'] = $all_data->pad_id;
		$data['version'] = $all_data->latest_version;
		$data['type'] = "edit";
		$data['description'] = $all_data->description;
		$this->load->view('new_chapter_view', $data);
	}

	public function view_chapter($id)
	{
		$all_data = $this->document_model->get_my_chapter_by_chapter_id($id);
		$data['id'] = $all_data->id;
		$data['parent_id'] = $all_data->document_folder_id;
		$data['file_name'] = $all_data->name;
		$data['pad_id'] = $all_data->pad_id;
		$data['type'] = "view";
		$data['description'] = $all_data->description;
		$data['version'] = $all_data->latest_version;
		
		$this->load->view('new_chapter_view', $data);
	}
	public function delete_chapter($id)
	{
		$result['status'] = 0;
		if ($id) {
			$this->document_model->delete_chapter($id);
			$result['message'] = _l('delete_successfully');
			$result['status'] = 1;
		} else {
			$result['message'] = _l('delete_fail');
		}
		echo json_encode($result);
		die;
	}
	/**
	 * [get_my_folder description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_my_folder($id) {
		echo json_encode($this->document_model->get_my_folder($id));
	}


	/**
	 * [get_my_folder_get_hash description]
	 * @param  [type] $rel_type [description]
	 * @param  [type] $rel_id   [description]
	 * @param  [type] $id_share [description]
	 * @return [type]           [description]
	 */
	public function get_my_folder_get_hash($rel_type, $rel_id, $id_share) {
		echo json_encode($this->document_model->get_hash($rel_type, $rel_id, $id_share));
	}
	/**
	 * update share document online
	 * @return redirect
	 */
	public function update_share_document_online()
	{

		$response = [];
		$response['status'] = 0;
		$data = $this->input->post();
		if(!empty($data))
		{
			$success = $this->document_model->update_share($data);

			$staff_notification = get_option('document_staff_notification');
			$staff_sent_email = get_option('document_email_templates_staff');
			$client_notification = get_option('document_client_notification');
			$client_sent_email = get_option('document_email_templates_client');

			if ($success == true) {
				// $message = _l('updated_successfully');
				// set_alert('success', $message);
				if (count($data['staffs_share']) > 0) {
					if ($data['staffs_share'][0] != '') {
						foreach ($data['staffs_share'] as $key => $value) {
							$this->db->where('id', $data['id']);
							$share = $this->db->get(db_prefix() . 'document_online_my_folder')->row();

							$share->receiver = document_email_staff_document($value);
							$share->staff_share_id = $value;

							$share->type_template = "staff_template";

							if ($staff_sent_email == 1) {
								$template = mail_template('document_share', 'document', array_to_object($share));
								$template->send();
							}

							if ($staff_notification == 1) {
								$link = '';
								$link = 'document_online/new_file_view/' . $data['parent_id'] . '/' . $data['id'];
								$string_sub = get_staff_full_name($value) . ' ' . _l('share') . ' ' . $share->type . ' ' . $share->name . ' ' . _l('for_you');
								$this->document_model->notifications($value, $link, strtolower($string_sub));
							}
						}
					}
				}

				if (count((array)$data['clients_share'])) {
					if ($data['clients_share'][0] != '') {
						foreach ($data['clients_share'] as $key => $value) {
							$this->db->where('id', $data['id']);
							$share = $this->db->get(db_prefix() . 'document_online_my_folder')->row();

							$this->db->where('id', $value);
							$contact = $this->db->get(db_prefix() . 'contacts')->row();


							// if($contact){
							// 	$contact_email = $contact->email;
							// }
							if ($contact != null && isset($contact->email) && $contact->email != '') {
								$share->receiver = $contact->email;
								$share->client_share_id = $value;
								$share->type_template = "client_template";
								if ($client_sent_email == 1) {
									$template = mail_template('document_share_client', 'document', array_to_object($share));
									$template->send();
								}

								if ($client_notification == 1) {
									$link_client = '';
									$link_client = 'document/new_file_view/' . $data['parent_id'] . '/' . $data['id'];
									$string_sub = get_staff_full_name($value) . ' ' . _l('share') . ' ' . $share->type . ' ' . $share->name . ' ' . _l('for_you');
									$this->document_model->notifications($value, $link_client, strtolower($string_sub));
								}
							}
						}
					}
				}
				$response['status'] = 1;
				$response['message'] = _l('document_share_updated_successfully');
			} else {
				$response['error'] = _l('add_share_data');
				// $message = _l('updated_fail');
				// set_alert('warning', $message);
			}
		}
		echo json_encode($response);exit;
		// redirect(admin_url('document/manage?tab=my_folder'));
	}


	/**
	 * [append_value_department description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function append_value_department($id) {
		$data = get_all_staff_by_department($id);
		$html = '';
		if (count($data) > 0) {
			$html .= '<option value=""></option>';
			foreach ($data as $key => $value) {
				$html .= '<option value="' . $value['staffid'] . '">' . get_staff_full_name($value['staffid']) . '</option>';
			}
		}
		echo json_encode($html);
	}


		/**
	 * [get_staff_all description]
	 * @return [type] [description]
	 */
	public function get_staff_all() {
		$staffs = $this->staff_model->get();
		$html = '';
		if (count($staffs) > 0) {
			$html .= '<option value=""></option>';
			foreach ($staffs as $key => $value) {
				$html .= '<option value="' . $value['staffid'] . '">' . get_staff_full_name($value['staffid']) . '</option>';
			}
		}
		echo json_encode($html);
	}
	/**
	 * [append_value_group description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function append_value_group($id)
	{
		$data = get_all_client_by_group($id);
		$html = '';

		if (isset($data)) {
			if (count($data) > 0) {
				$html .= '<option value=""></option>';
				foreach ($data as $key => $value) {
					$client = $this->clients_model->get($value['customer_id']);
					$html .= '<option value="' . $client->userid . '">' . $client->company . '</option>';
				}
			}
		}
		echo json_encode($html);
	}

	/**
	 * delete folder file
	 * @param  int $id
	 * @return  json
	 */
	public function delete_folder_file($id)
	{
		$success = false;
		$message = _l('deleted_fail');
		if ($id == 1) {
			echo json_encode(['success' => false, 'message' => _l('cannot_deleted _root_directory')]);
		} else {
			if ($id != '') {
				$success = $this->document_model->delete_folder_file($id);
				$message = _l('file_folder_deleted');
			}
			echo json_encode(['success' => $success, 'message' => $message]);
		}
	}


	/**
	 * get share staff client
	 * @param  int $id
	 * @return json
	 */
	public function get_share_staff_client($id)
	{

		$data = $this->document_model->get_share_detail($id);

		$html_staff = "";
		$html_client = "";
		if (count($data['staffs_share']) > 0) {
			foreach ($data['staffs_share'] as $key => $value) {
				$html_staff .= '
    			<tr>
    			<td>' . $value . '</td>
    			<td>' . ($data['staffs_role'][$key] == 1 ? "View" : "Edit") . '</td>
    			</tr>
    			';
			}
		}

		if (count($data['clients_share']) > 0) {
			foreach ($data['clients_share'] as $key => $value) {
				$html_client .= '
    			<tr>
    			<td>' . $value . '</td>
    			<td>' . ($data['clients_role'][$key] == 1 ? "View" : "Edit") . '</td>
    			</tr>
    			';
			}
		}
		echo json_encode(['staffs' => $html_staff, 'clients' => $html_client]);
	}


	public function document_setting()
	{
		$data = $this->input->post();
		if (isset($data['document_staff_notification'])) {
			if ($data['document_staff_notification'] = 'on') {
				update_option('document_staff_notification', 1);
			}
		} else {
			update_option('document_staff_notification', 0);
		}

		if (isset($data['document_email_templates_staff'])) {
			if ($data['document_email_templates_staff'] = 'on') {
				update_option('document_email_templates_staff', 1);
			}
		} else {
			update_option('document_email_templates_staff', 0);
		}

		if (isset($data['document_client_notification'])) {
			if ($data['document_client_notification'] = 'on') {
				update_option('document_client_notification', 1);
			}
		} else {
			update_option('document_client_notification', 0);
		}

		if (isset($data['document_email_templates_client'])) {
			if ($data['document_email_templates_client'] = 'on') {
				update_option('document_email_templates_client', 1);
			}
		} else {
			update_option('document_email_templates_client', 0);
		}

		redirect(admin_url('document/manage'));
	}

	public function orderd_chapter()
	{
		$ids = $this->input->post('ids');
		foreach ($ids as $key => $value) {
			$ordered = $key + 1;
			$res = $this->document_model->add_ordered($value, $ordered);
		}
		if ($res == true) {
			$result['status'] =  true;
		}else{
			$result['status'] =  false;
		}
		
		echo json_encode($result);
		die;
	}
}
