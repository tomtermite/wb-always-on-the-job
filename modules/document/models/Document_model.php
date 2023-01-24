<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Document online model
 */
class Document_model extends App_Model
{
	/**
	 * tree my folder 
	 * @return html
	 */
	public function tree_my_folder()
	{
		$data = $this->get_my_folder_by_staff_my_folder();


		// $tree = "<tbody>";
		$tree = "";
		foreach ($data as $data_key => $data_val) {
			if ($data_val['parent_id'] == 0) {

				$tree .= $this->dq_tree_my_folder($data_val);
			}
		}
		// $tree .= "<tbody>";
		return $tree;
	}
	/**
	 * dq tree my folder 
	 * @param  array $root     
	 * @param  int $parent_id 
	 * @return html     
	 */
	public function dq_tree_my_folder($root, $parent_id = '')
	{
		$share_detail = $this->get_share_detail($root['id']);
		$tree_tr = '';
		$class = "share-status";
		$html_change = '<i class="fa fa-group ' . $class . '"></i>';
		$this->db->where('parent_id', $root['id']);
		$type = $root['type'] == 'folder' ? "folder" : "file";
		if ($root['id'] == $parent_id) {
			$this->db->where('id', $root['id']);
			$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => ""]);
			$parent_id = '';
		}
		$status_share =  $root['flag_share'] == 1  ? $html_change : '';

		if ($parent_id == '') {
			$tree_tr .= '<tr class="right-menu-position file_td" data-tt-id="' . $root['id'] . '" data-tt-name="' . $root['name'] . '" data-tt-type="' . $type . '">';
			if ($root['type'] == 'file') {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '"><a href="' . admin_url('document/new_file_view/' . $root['id'] . '') . '">' . $root['name'] . '</a>' . $status_share . '</span>
				</td>';
			} else {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '">' . $root['name'] . '  ' . $status_share . '</span>
				</td>';
			}


			$tree_tr .= '<td class="qcont">' . $root['type'] . '</td>';
			$tree_tr .= '
			<td>
			<span>' . get_staff_full_name($root['staffid']) . '</span>
			</td>';
			if (!empty($share_detail['staffs_share'])) {
				$tree_tr .= '<td>';
				foreach ($share_detail['staffs_share'] as $key => $value) {
					if ($share_detail['staffs_role'][$key] == 1) {
						$type_role_staff = "View";
					} else {
						$type_role_staff = "Edit";
					}
					$tree_tr .= '<ul><li>' . $value . ':' . $type_role_staff . '</li></ul>';
				}
				$tree_tr .= '</td>';
			}
			if (!empty($share_detail['clients_share'])) {
				$tree_tr .= '<td>';

				foreach ($share_detail['clients_share'] as $key => $value) {
					if ($share_detail['clients_role'][$key] == 1) {
						$type_role = "View";
					} else {
						$type_role = "Edit";
					}
					$tree_tr .= '<ul><li>' . $value . ':' . $type_role . '</li></ul>';
				}
				$tree_tr .= '</td>';
			}
		} else {

			$tree_tr .= '<tr class="right-menu-position" data-tt-id="' . $root['id'] . '" data-tt-name="' . $root['name'] . '" data-tt-parent-id="' . $parent_id . '" data-tt-type="' . $type . '">';
			if ($root['type'] == 'file') {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '"><a href="' . admin_url('document/new_file_view/' . $root['id'] . '') . '">' . $root['name'] . '</a>' . $status_share . '</span>
				</td>';
			} else {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '">' . $root['name'] . '  ' . $status_share . '</span>
				</td>';
			}
			$tree_tr .= '<td class="qcont">' . $root['type'] . '</td>';
			$tree_tr .= '
			<td>
			<span>' . get_staff_full_name($root['staffid']) . '</span>
			</td>';
			if (!empty($share_detail['staffs_share'])) {
				$tree_tr .= '
				<td>';
				foreach ($share_detail['staffs_share'] as $key => $value) {
					if ($share_detail['staffs_role'][$key] == 1) {
						$type_role_staff = "View";
					} else {
						$type_role_staff = "Edit";
					}
					$tree_tr .= '<ul><li>' . $value . ':' . $type_role_staff . '</li></ul>';
				}
				$tree_tr .= '
			</td>';
			}
			if (!empty($share_detail['clients_share'])) {
				$tree_tr .= '<td>';
				foreach ($share_detail['clients_share'] as $key => $value) {
					if ($share_detail['clients_role'][$key] == 1) {
						$type_role = "View";
					} else {
						$type_role = "Edit";
					}
					$tree_tr .= '<ul><li>' . $value . ':' . $type_role . '</li></ul>';
				}
				$tree_tr .= '</td>';
			}
		}

		$data = $this->get_my_folder_by_parent_id($root['id']);
		foreach ($data as $data_key => $data_val) {
			if (($data_val['id'] == $data_val['parent_id'])) {
				$this->db->where('id', $data_val['id']);
				$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => ""]);
				$data_val['parent_id'] = "";
			}
			$tree_tr .= $this->dq_tree_my_folder($data_val, $data_val['parent_id']);
		}
		return $tree_tr;
	}
	/**
	 * get my folder 
	 * @param  int $id 
	 * @return array    
	 */
	public function get_my_folder($id = '')
	{
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'document_online_my_folder')->row();
		}
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}
	/**
	 * get my folder by staff my folder 
	 * @return array
	 */
	public function get_my_folder_by_staff_my_folder()
	{
		$this->db->where('staffid', get_staff_user_id());
		$this->db->where('category', 'my_folder');
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}
	/**
	 * get my folder by parent id 
	 * @param  int $parent_id
	 * @return  array          
	 */
	public function get_my_folder_by_parent_id($parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}
	/**
	 * get my folder by staff share 
	 * @return array
	 */
	public function get_my_folder_by_staff_share()
	{
		$this->db->where('staffid', get_staff_user_id());
		$this->db->where('category', 'share');
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}
	/**
	 * add folder 
	 * @param  $data 
	 */
	public function add_folder($data)
	{
		if (isset($data['id'])) {
			unset($data['id']);
		}
		if (isset($data['parent_id'])) {
			if ($data['parent_id'] == '') {
				$data['parent_id'] = 0;
			}
		}
		$data['staffid'] = get_staff_user_id();
		$data['size'] = "--";
		$data['category'] = "my_folder";
	
		$this->db->insert(db_prefix() . 'document_online_my_folder', $data);
		$insert_id = $this->db->insert_id();
		if($data['parent_id'] > 0){
			$share = $this->get_my_folder($data['parent_id']);
			$share_data = $this->get_share_form_parent($data['parent_id']);
			$staffs_share = [];
			$departments_share = [];
			$clients_share = [];
			$client_groups_share = [];
			if ($share->staffs_share != '') {
				$data_staffs = explode(',', $share->staffs_share);
				if (count($data_staffs) > 0) {
					foreach ($data_staffs as $key => $value) {
						array_push($staffs_share, $value);
					}
				}	
			}
			if ($share->clients_share != '') {
				$data_clients = explode(',', $share->clients_share);
				if (count($data_clients) > 0) {
					foreach ($data_clients as $key => $value) {
						array_push($clients_share, $value);
					}
				}	
			}			
			if ($share->client_groups_share != '') {
				$data_client_groups = explode(',', $share->client_groups_share);
				if (count($data_client_groups) > 0) {
					foreach ($data_client_groups as $key => $value) {
						array_push($client_groups_share, $value);
					}
				}
				$client_groups = $client_groups_share;
				

				if (count($client_groups) != count($client_groups_share)) {
					foreach ($client_groups_share as $key => $value) {
						$clientids = get_all_client_by_group($value);
						if (count($clientids) > 0) {
							foreach ($clientids as $clientid) {
								if (!in_array($clientid['customer_id'], $clients_share)) {
									array_push($clients_share, $clientid['customer_id']);
								}
							}
						}
					}
				}
			}

			if ($share->departments_share != '') {
				$data_departments = explode(',', $share->departments_share);
				if (count($data_departments) > 0) {
					foreach ($data_departments as $key => $value) {
						array_push($departments_share, $value);
					}
				}
				$departments = $departments_share;
				if (count($departments) != count($departments_share)) {
					foreach ($departments_share as $key => $value) {
						$staffids = get_all_staff_by_department_document($value);
						if (count($staffids) > 0) {
							foreach ($staffids as $staffid) {
								if (!in_array($staffid['staffid'], $staffs_share)) {
									array_push($staffs_share, $staffid['staffid']);
								}
							}
						}
					}
				}
			}
			if($share->group_share_staff == 1 && $share->group_share_staff != '') {
				$data_hash['rel_type'] = 'staff';
				$data_hash['id_share'] = $insert_id;
					foreach ($staffs_share as $key => $value) {
						if (strlen($value) == 1) {
							$data_hash['rel_id'] = $value;
							$data_hash['role'] = $share_data[0]['role'];
							$this->tree_my_folder_hash($data_hash);
						}
					}
			}
			if ($share->group_share_client == 2 || $share->group_share_client != '') {
				$data_hash['rel_type'] = 'client';
				$data_hash['id_share'] = $insert_id;
				foreach ($clients_share as $key => $value) {
					$data_hash['rel_id'] = $value;
					$data_hash['role'] = $share_data[0]['role'];
					$this->tree_my_folder_hash($data_hash);
				}
			}

		
			$data['staffs_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($staffs_share))));
			$data['departments_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($departments_share))));
			$data['clients_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($clients_share))));
			$data['client_groups_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($client_groups_share))));
			$data['group_share_staff'] = $share->group_share_staff;
			$data['group_share_client'] = $share->group_share_client;
			$this->db->where('id', $insert_id);
			$this->db->update(db_prefix() . 'document_online_my_folder', $data);
		}
		return $insert_id;
	}
	/**
	 * edit folder 
	 * @param  $data 
	 * @return boolean
	 */
	public function edit_folder($data)
	{
		if (isset($data['parent_id'])) {
			if ($data['parent_id'] == '') {
				$data['parent_id'] = '';
			}
			unset($data['parent_id']);
		}
		
		$this->db->where('id', $data['id']);
		return $this->db->update(db_prefix() . 'document_online_my_folder', $data);
	}



	/**
	 * get folder zip 
	 * @param  string $id   
	 * @param  string $name 
	 * @return $data      
	 */
	public function get_folder_zip($id = "", $name = "download")
	{
		if ($id != "") {
			$this->db->where('id', $id);
			$this->db->where('type', "folder");
			$data['main'] = $this->db->get(db_prefix() . 'document_online_my_folder')->row();
			$this->db->where('parent_id', $id);
			$data['child'] = $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
			foreach ($data['child'] as $key => $child) {
				if ($child['type'] == "folder") {
					$this->db->where('id', $child['id']);
					$this->db->where('type', "folder");
					$data['child']['data_form_main']['main'] = $this->db->get(db_prefix() . 'document_online_my_folder')->row();

					$this->db->where('parent_id', $child['id']);
					$data['child']['data_form_main']['child'] = $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
				}
			}
			return $data;
		}
		$this->db->where('type', "folder");
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}

	/* creates a compressed zip file */
	public function create_zip($files = array(), $destination = '', $overwrite = false)
	{
		//if the zip file already exists and overwrite is false, return false
		if (file_exists($destination) && !$overwrite) {
			return false;
		}

		//vars
		$valid_files = array();
		//if files were passed in...
		if (is_array($files)) {
			//cycle through each file
			foreach ($files as $file) {
				//make sure the file exists
				if (file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}

		//if we have good files...
		if (count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach ($valid_files as $file) {
				$zip->addFile($file, $file);
				echo "numfiles: " . $zip->numFiles . "\n";
				echo "status:" . $zip->status . "\n";
			}
			//debug

			//close the zip -- done!
			$zip->close();
			//check to make sure the file exists
			return file_exists($destination);
		} else {
			return false;
		}
	}
	/**
	 * get my folder all 
	 * @return array
	 */
	public function get_my_folder_all()
	{
		$this->db->where('type', 'folder');
		$this->db->where('staffid', get_staff_user_id());
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}
	/**
	 * update share
	 * @param  $data
	 * @return  boolean
	 */
	public function update_share($data)
	{
		if (count($data['clients_share']) < count($data['client_groups_share'])) {
			$count = count($data['client_groups_share']) - count($data['clients_share']);
			for ($i = 0; $i < $count; $i++) {
				$data['clients_share'][] = '';
			}
		}
		$group_share_staff = isset($data['group_share_staff']) ? $data['group_share_staff'] : '';
		$group_share_client = isset($data['group_share_client']) ? $data['group_share_client'] : '';

		$update = "false";
		if (isset($data['update'])) {
			$update = $data['update'];

			unset($data['update']);
		}
		$role_staff = $data['role_staff'];
		$role_client = $data['role_client'];
		unset($data['role_staff']);
		unset($data['role_client']);
		if ($this->exit_object_share($data, $update)) {
			$share = $this->get_my_folder($data['id']);

			$staffs_share = [];
			$departments_share = [];
			$clients_share = [];
			$client_groups_share = [];
			if ($share->staffs_share != '') {
				$data_staffs = explode(',', $share->staffs_share);

				if (count($data_staffs) > 0) {
					foreach ($data_staffs as $key => $value) {
						array_push($staffs_share, $value);
					}
				}
				array_push($staffs_share, implode(',', $data['staffs_share']));
			} else {
				if (isset($data['staffs_share'])) {
					array_push($staffs_share, implode(',', $data['staffs_share']));
				}
			}

			if ($share->departments_share != '') {
				$data_departments = explode(',', $share->departments_share);
				if (count($data_departments) > 0) {
					foreach ($data_departments as $key => $value) {
						array_push($departments_share, $value);
					}
				}
				$departments = $departments_share;

				array_push($departments_share, implode(',', $data['departments_share']));


				if (count($departments) != count($departments_share)) {
					foreach ($data['departments_share'] as $key => $value) {
						$staffids = get_all_staff_by_department_document($value);
						if (count($staffids) > 0) {
							foreach ($staffids as $staffid) {
								if (!in_array($staffid['staffid'], $staffs_share)) {
									array_push($staffs_share, $staffid['staffid']);
								}
							}
						}
					}
				}
			} else {
				array_push($departments_share, implode(',', $data['departments_share']));
				foreach ($data['departments_share'] as $key => $value) {
					if (isset($data['staffs_share']) && isset($data['staffs_share'][$key])) {
						if ($data['staffs_share'][$key] == '' && !in_array($data['staffs_share'][$key], $staffs_share)) {
							$staffids = get_all_staff_by_department_document($value);

							if (count($staffids) > 0) {
								foreach ($staffids as $key => $staffid) {
									if (!in_array($staffid['staffid'], $staffs_share)) {
										array_push($staffs_share, $staffid['staffid']);
									}
								}
							}
						}
					} else {
						$staffids = get_all_staff_by_department_document($value);
						if (count($staffids) > 0) {
							foreach ($staffids as $key => $staffid) {
								if (!in_array($staffid['staffid'], $staffs_share)) {
									array_push($staffs_share, $staffid['staffid']);
								}
							}
						}
					}
				}
			}
			if ($share->clients_share != '') {
				$data_clients = explode(',', $share->clients_share);
				if (count($data_clients) > 0) {
					foreach ($data_clients as $key => $value) {
						array_push($clients_share, $value);
					}
				}
				array_push($clients_share, implode(',', $data['clients_share']));
			} else {
				if (isset($data['clients_share'])) {
					array_push($clients_share, implode(',', $data['clients_share']));
				}
			}
			
			if ($share->client_groups_share != '') {
				$data_client_groups = explode(',', $share->client_groups_share);
				if (count($data_client_groups) > 0) {
					foreach ($data_client_groups as $key => $value) {
						array_push($client_groups_share, $value);
					}
				}
				$client_groups = $client_groups_share;
				array_push($client_groups_share, implode(',', $data['client_groups_share']));

				if (count($client_groups) != count($client_groups_share)) {
					array_push($client_groups_share, implode(',', $data['client_groups_share']));
					foreach ($data['client_groups_share'] as $key => $value) {

						$clientids = get_all_client_by_group($value);
						if (count($clientids) > 0) {
							foreach ($clientids as $clientid) {
								if (!in_array($clientid['customer_id'], $clients_share)) {
									array_push($clients_share, $clientid['customer_id']);
								}
							}
						}
					}
				}
			} else {
				array_push($client_groups_share, implode(',', $data['client_groups_share']));
				foreach ($data['client_groups_share'] as $key => $value) {
					if (isset($data['clients_share']) && isset($data['clients_share'][$key])) {
						if ($data['clients_share'][$key] == '' && !in_array($data['clients_share'][$key], $clients_share)) {
							$clientids = get_all_client_by_group($value);
							if (count($clientids) > 0) {
								foreach ($clientids as $clientid) {
									if (!in_array($clientid['customer_id'], $clients_share)) {
										array_push($clients_share, $clientid['customer_id']);
									}
								}
							}
						}
					} else {
						$clientids = get_all_client_by_group($value);
						if (count($clientids) > 0) {
							foreach ($clientids as $clientid) {
								if (!in_array($clientid['customer_id'], $clients_share)) {
									array_push($clients_share, $clientid['customer_id']);
								}
							}
						}
					}
				}
			}

			if ($group_share_staff == 1 && $group_share_staff != '') {
				$data_hash['rel_type'] = 'staff';
				$data_hash['id_share'] = $data['id'];
				if ($data['staffs_share'][0] == '') {
					foreach ($staffs_share as $key => $value) {
						if (strlen($value) == 1) {
							$data_hash['rel_id'] = $value;
							$data_hash['role'] = $role_staff[$key];
							$this->tree_my_folder_hash($data_hash);
						}
					}
				} else {
					foreach ($data['staffs_share'] as $key => $value) {
						$data_hash['rel_id'] = $value;
						$data_hash['role'] = $role_staff[$key];
						$this->tree_my_folder_hash($data_hash);
					}
				}
			}

			if ($group_share_client == 2 || $group_share_client != '') {
				$data_hash['rel_type'] = 'client';
				$data_hash['id_share'] = $data['id'];
				foreach ($data['clients_share'] as $key => $value) {
					$data_hash['rel_id'] = $value;
					$data_hash['role'] = $role_client[$key];
					$this->tree_my_folder_hash($data_hash);
				}
			}
			$data['staffs_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($staffs_share))));
			$data['departments_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($departments_share))));
			$data['clients_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($clients_share))));
			$data['client_groups_share'] = $this->array_to_string_unique(explode(',', implode(',', array_unique($client_groups_share))));
			$data['flag_share'] = 1;
			$this->db->where('id', $data['id']);
			$this->db->update(db_prefix() . 'document_online_my_folder', $data);

			if ($this->db->affected_rows() > 0) {
				return true;
			}
			return false;
		} else {

			return false;
		}
	}
	/**
	 * exit object share 
	 * @param  $share
	 * @param  $data 
	 * @return  boolean      
	 */
	public function exit_object_share($data, $update)
	{
		if ($update == "true") {
			$this->db->where('id', $data['id']);
			$this->db->update(db_prefix() . 'document_online_my_folder', ['staffs_share' => '', 'departments_share' => '', 'clients_share' => '', 'client_groups_share' => '', 'group_share_staff' => '', 'group_share_client' => '']);
			$this->db->where('id_share', $data['id']);
			$this->db->delete(db_prefix() . 'document_online_hash_share');
			$list_child = $this->get_my_folder_by_parent_id($data['id']);
			if (count($list_child) > 0) {
				foreach ($list_child as $key => $value) {
					$this->db->where('id_share', $value['id']);
					$this->db->delete(db_prefix() . 'document_online_hash_share');
				}
			}
		}

		return true;
	}


	/**
	 * add hash 
	 * @param  $data 
	 */
	public function add_hash($data)
	{
		$this->db->insert(db_prefix() . 'document_online_hash_share', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	/**
	 * get_hash 
	 * @param  $rel_type
	 * @param  $staffid 
	 * @param  $share_id
	 * @return           
	 */
	public function get_hash($rel_type, $staffid, $share_id)
	{
		$this->db->where('rel_id', $staffid);
		$this->db->where('rel_type', $rel_type);
		$this->db->where('id_share', $share_id);
		return $this->db->get(db_prefix() . 'document_online_hash_share')->row();
	}
	/**
	 * get hash all 
	 * @return array
	 */
	public function get_hash_all()
	{
		return $this->db->get(db_prefix() . 'document_online_hash_share')->result_array();
	}
	/**
	 * exit_hash 
	 * @param  $peopel_id
	 * @param  $share_id 
	 * @param  $rel_type 
	 * @return boolean           
	 */
	public function exit_hash($peopel_id, $share_id, $rel_type)
	{
		$hash = $this->get_hash_all();

		foreach ($hash as $key => $value) {
			if ($peopel_id  == $value['rel_id'] && $share_id == $value['id_share'] && $rel_type == $value['rel_type']) {
				return false;
			}
		}
		return true;
	}
	/**
	 * tree my folder hash 
	 * @param  $share
	 * @return boolean      
	 */
	public function tree_my_folder_hash($share)
	{
		return $this->dq_tree_my_folder_hash($share);
	}
	/**
	 * dq tree my folder hash 
	 * @param  $root
	 * @return boolean      
	 */
	public function dq_tree_my_folder_hash($root)
	{
		if ($this->exit_hash($root['rel_id'], $root['id_share'], $root['rel_type'])) {
			$root['hash'] = app_generate_hash();
			$this->add_hash($root);
			$root_child = $this->get_my_folder_by_parent_id($root['id_share']);
			foreach ($root_child as $data_key => $data_val) {
				$data_hash['rel_type'] = $root['rel_type'];
				$data_hash['rel_id'] = $root['rel_id'];
				$data_hash['id_share'] = $data_val['id'];
				$data_hash['role'] = $root['role'];
				$this->dq_tree_my_folder_hash($data_hash);
			}
		}

		return true;
	}

	/**
	 * get my folder by staff my folder 
	 * @return array
	 */
	public function get_my_folder_by_staff_share_folder($staffid)
	{
		$this->db->where('find_in_set(' . $staffid . ', staffs_share)');
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}
	/**
	 * tree my folder share
	 * @return html
	 */
	public function tree_my_folder_share()
	{
		$staffid = get_staff_user_id();
		$data = $this->get_my_folder_by_staff_share_folder($staffid);
		$tree = "<tbody>";
		foreach ($data as $data_key => $data_val) {
			$tree .= $this->dq_tree_my_folder_share($data_val);
		}
		$tree .= "<tbody>";
		return $tree;
	}
	/**
	 * dq tree my folder share
	 * @param  array $root     
	 * @param  int $parent_id 
	 * @return html     
	 */
	public function dq_tree_my_folder_share($root, $parent_id = '', $test = '')
	{

		$get_hash = $this->get_hash('staff', get_staff_user_id(), $root['id']);
		$tree_tr = '';
		$type = $root['type'] == 'folder' ? "folder" : "file";
		if ($root['id'] == $parent_id) {
			$this->db->where('id', $root['id']);
			$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => ""]);
			$parent_id = '';
		}

		if ($parent_id == '') {
			$tree_tr .= '<tr class="right-menu-position" data-tt-id="' . $root['id'] . '" data-tt-role="' . $get_hash->role . '" data-tt-name="' . $root['name'] . '" data-tt-type="' . $type . '">';
			if ($root['type'] == 'file') {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '"><a href="' . admin_url('document/new_file_view/' . $root['id'] . '') . '">' . $root['name'] . '</a></span>
				</td>';
			} else {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '">' . $root['name'] . '  </span>
				</td>';
			}
			$tree_tr .= '<td class="qcont">' . $root['type'] . '</td>';
			$tree_tr .= '<td>' . $root['size'] . '</td>';
		} else {
			$tree_tr .= '<tr class="right-menu-position" data-tt-id="' . $root['id'] . '" data-tt-role="' . $get_hash->role . '" data-tt-name="' . $root['name'] . '" data-tt-parent-id="' . $parent_id . '" data-tt-type="' . $type . '">';

			if ($root['type'] == 'file') {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '"><a href="' . admin_url('document/new_file_view/' . $root['id'] . '') . '">' . $root['name'] . '</a></span>
				</td>';
			} else {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '">' . $root['name'] . '  </span>
				</td>';
			}
			$tree_tr .= '<td class="qcont">' . $root['type'] . '</td>';
			$tree_tr .= '<td>' . $root['size'] . '</td>';
		}

		$data = $this->get_my_folder_by_parent_id($root['id']);

		foreach ($data as $data_key => $data_val) {
			if (($data_val['id'] == $data_val['parent_id'])) {
				$this->db->where('id', $data_val['id']);
				$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => ""]);
				$data_val['parent_id'] = "";
			}
			$tree_tr .= $this->dq_tree_my_folder_share($data_val, $data_val['parent_id'], $data_key);
		}

		return $tree_tr;
	}
	/**
	 * get share form hash
	 * @param  string $hash 
	 * @return row       
	 */
	public function get_share_form_hash($hash)
	{
		$this->db->where('hash', $hash);
		return $this->db->get(db_prefix() . 'document_online_hash_share')->row();
	}

		/**
	 * get share form hash
	 * @param  string $hash 
	 * @return row       
	 */
	public function get_share_form_parent($parent_id)
	{
		$this->db->where('id_share', $parent_id);
		return $this->db->get(db_prefix() . 'document_online_hash_share')->result_array();
	}

	/**
	 * get my folder by client my folder 
	 * @return array
	 */
	public function get_my_folder_by_client_share_folder($clientid)
	{
		if ($clientid != null || $clientid != '') {
			$this->db->where('find_in_set(' . $clientid . ', clients_share)');
			return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
		}
		return [];
	}

	/**
	 * tree my folder share client
	 * @return html
	 */
	public function tree_my_folder_share_client()
	{
		$clientid = get_client_user_id();
		$data = $this->get_my_folder_by_client_share_folder($clientid);
		$tree = "<tbody>";

		foreach ($data as $data_key => $data_val) {
			$tree .= $this->dq_tree_my_folder_share_client($data_val);
		}
		$tree .= "<tbody>";
		return $tree;
	}

	public function dq_tree_my_folder_share_client($root, $parent_id = '')
	{
		$get_hash = $this->get_hash('client', get_client_user_id(), $root['id']);
		$get_hash_role = 0;
		if ($get_hash) {
			$get_hash_role = $get_hash->role;
		}

		$tree_tr = '';
		$type = $root['type'] == 'folder' ? "folder" : "file";
		if ($root['id'] == $parent_id) {
			$this->db->where('id', $root['id']);
			$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => ""]);
			$parent_id = '';
		}
		if ($parent_id == '') {
			$tree_tr .= '<tr class="right-menu-position" data-tt-id="' . $root['id'] . '" data-tt-role="' . $get_hash_role . '" data-tt-name="' . $root['name'] . '" data-tt-type="' . $type . '">';
			if ($root['type'] == 'file') {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '"><a href="' . site_url('document/document_client/client_file_view/' . $root['id'] . '') . '">' . $root['name'] . '</a></span>
				</td>';
			} else {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '">' . $root['name'] . '</span>
				</td>';
			}
			$tree_tr .= '<td class="qcont">' . $root['type'] . '</td>';
			$tree_tr .= '<td>' . $root['size'] . '</td>';
		} else {
			$tree_tr .= '<tr class="right-menu-position" data-tt-id="' . $root['id'] . '" data-tt-role="' . $get_hash_role . '" data-tt-name="' . $root['name'] . '" data-tt-parent-id="' . $parent_id . '" data-tt-type="' . $type . '">';
			if ($root['type'] == 'file') {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '"><a href="' . site_url('document/document_client/client_file_view/' . $root['id'] . '') . '">' . $root['name'] . '</a></span>
				</td>';
			} else {
				$tree_tr .= '
				<td>
				<span class="tr-pointer ' . $root['type'] . '">' . $root['name'] . '</span>
				</td>';
			}
			$tree_tr .= '<td class="qcont">' . $root['type'] . '</td>';
			$tree_tr .= '<td>' . $root['size'] . '</td>';
		}

		$data = $this->get_my_folder_by_parent_id($root['id']);
		foreach ($data as $data_key => $data_val) {
			if (($data_val['id'] == $data_val['parent_id'])) {
				$this->db->where('id', $data_val['id']);
				$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => ""]);
				$data_val['parent_id'] = "";
			}
			$tree_tr .= $this->dq_tree_my_folder_share_client($data_val, $data_val['parent_id']);
		}
		return $tree_tr;
	}

	/**
	 * update related 
	 * @param  $data 
	 * @return boolean
	 */
	public function update_related($data)
	{
		$rel_type = implode(',', $data['rel_type']);
		$data_rel_id = $data['rel_id'];
		$data_rel_type = $data['rel_type'];
		unset($data['rel_type']);
		unset($data['rel_id']);

		$data['rel_type'] = $rel_type;

		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'document_online_my_folder', $data);
		// if ($this->db->affected_rows() > 0) {
		$this->db->where('parent_id', $data['id']);
		$this->db->delete(db_prefix() . 'document_online_related');

		if (count($data_rel_id) > 0) {
			foreach ($data_rel_id as $keys => $values) {

				$data_s['parent_id'] = $data['id'];
				$data_s['rel_type'] = $data_rel_type[$keys];
				$data_s['rel_id'] = $values;
				$data_s['role'] = 1;
				$data_s['hash'] = app_generate_hash();
				$this->db->insert(db_prefix() . 'document_online_related', $data_s);

				$this->db->select('id');
				$this->db->where('parent_id', $data['id']);
				$speadsheets = $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();

				if (count($speadsheets) > 0) {
					foreach ($speadsheets as $key_speadsheets => $value_speadsheets) {
						$data_speadsheets['parent_id'] = $value_speadsheets['id'];
						$data_speadsheets['rel_type'] = $data_rel_type[$keys];
						$data_speadsheets['rel_id'] = $values;
						$data_speadsheets['role'] = 1;
						$data_speadsheets['hash'] = app_generate_hash();
						$this->db->insert(db_prefix() . 'document_online_related', $data_speadsheets);
						$data_child = [];
						$data_child['rel_type'] = $data['rel_type'];
						$data_child['id'] = $value_speadsheets['id'];
						$this->db->where('id', $value_speadsheets['id']);
						$this->db->update(db_prefix() . 'document_online_my_folder', $data_child);
					}
				}
			}
		}
		return true;
		// }
		// return false;
	}

	public function get_share_detail($id)
	{
		$this->db->where('id', $id);
		$this->load->model('clients_model');
		$data = $this->db->get(db_prefix() . 'document_online_my_folder')->row();

		$staffs_share = explode(',', $data->staffs_share);
		$clients_share = explode(',', $data->clients_share);

		$rs['staffs_share'] = [];
		$rs['staffs_share_id'] = [];
		$rs['clients_share'] = [];
		$rs['clients_share_id'] = [];
		$rs['clients_role'] = [];
		$rs['staffs_role'] = [];

		if (count($staffs_share) > 0) {
			foreach ($staffs_share as $key => $value) {
				if ($value != '') {
					array_push($rs['staffs_share'], get_staff_full_name($value));
					array_push($rs['staffs_share_id'], $value);
					array_push($rs['staffs_role'], ($this->get_hash('staff', $value, $id) ? $this->get_hash('staff', $value, $id)->role : 'Not Role'));
				}
			}
		}

		if (count($clients_share) > 0) {
			foreach ($clients_share as $key => $value) {
				if ($value != '') {
					$name = $this->clients_model->get($value)->company;
					array_push($rs['clients_share'], $name);
					array_push($rs['clients_share_id'], $value);
					array_push($rs['clients_role'], ($this->get_hash('client', $value, $id) ? $this->get_hash('client', $value, $id)->role : 'Not Role'));
				}
			}
		}
		return $rs;
	}

	public function data_related_id($id)
	{
		$this->db->where('parent_id', $id);
		$data_main_type = [];
		$data_main_id = [];
		$data = $this->db->get(db_prefix() . 'document_online_related')->result_array();
		if (count($data) > 0) {
			foreach ($data as $key => $value) {
				array_push($data_main_type, $value['rel_type']);
				array_push($data_main_id, $value['rel_id']);
			}
		}
		$data_s['type'] = $data_main_type;
		$data_s['id'] = $data_main_id;
		return $data_s;
	}
	/**

	 * tree my folder 
	 * @return html
	 */
	public function tree_my_folder_related($rel_type, $rel_id)
	{
		$data = $this->get_my_folder_related($rel_type, $rel_id);
		$tree = "<tbody>";
		if (count($data) > 0) {
			foreach ($data as $data_key => $data_val) {
				$this->db->where('id', $data_val['parent_id']);
				$data_parent = $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
				$tree .= $this->dq_tree_my_folder($data_parent[0]);
			}
		}
		$tree .= "<tbody>";
		return $tree;
	}

	/**
	 * get my folder by staff my folder 
	 * @return array
	 */
	public function get_my_folder_related($type, $id)
	{
		$query = 'select DISTINCT(parent_id) from ' . db_prefix() . 'document_online_related where rel_type = "' . $type . '" and rel_id = "' . $id . '"';
		$data = $this->db->query($query)->result_array();
		if (count($data) > 0) {
			return $data;
		} else {
			return [];
		}
	}

	/**
	 * get my folder by client my folder 
	 * @return array
	 */
	public function get_folder_type_tree()
	{
		$this->db->where('type', 'folder');
		$this->db->where('parent_id', '0');
		$this->db->where('staffid', get_staff_user_id());
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}

	/**
	 * get my folder by parent id and type folder
	 * @param  int $parent_id
	 * @return  array          
	 */
	public function get_my_folder_by_parent_id_and_type_folder($parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->where('type', 'folder');
		return $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
	}

	public function get_folder_tree()
	{
		$department = $this->get_folder_type_tree();
		$dep_tree = array();
		foreach ($department as $key => $dep) {
			$node = array();
			$node['id'] = $dep['id'];
			$node['title'] = $dep['name'];
			$node['subs'] = $this->get_child_node($dep['id'], $dep);
			$dep_tree[] = $node;
		}
		return $dep_tree;
	}

	/**
	 * Get child node of department tree
	 * @param  $id      current department id
	 * @param  $arr_dep department array
	 * @return current department tree
	 */
	private function get_child_node($id, $arr_dep)
	{
		$dep_tree = array();

		$arr =  $this->db->query('select * from ' . db_prefix() . 'document_online_my_folder where parent_id = ' . $id . ' and type = "folder"')->result_array();

		foreach ($arr as $dep) {
			if ($dep['parent_id'] == $id) {
				$node = array();
				$node['id'] = $dep['id'];
				$node['title'] = $dep['name'];
				$node['subs'] = $this->get_child_node($dep['id'], $dep);
				if (count($node['subs']) == 0) {
					unset($node['subs']);
				}
				$dep_tree[] = $node;
			}
		}
		return $dep_tree;
	}
	/**
	 * [droppable_event description]
	 * @param  [type] $id        [description]
	 * @param  [type] $parent_id [description]
	 * @return [type]            [description]
	 */
	public function droppable_event($id, $parent_id)
	{
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'document_online_my_folder', ['parent_id' => $parent_id]);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get hash related
	 * @param  $rel_type
	 * @param  $related_to_id 
	 * @param  $parent_id
	 * @return           
	 */
	public function get_hash_related($rel_type, $related_to_id, $parent_id)
	{
		$this->db->where('rel_id', $related_to_id);
		$this->db->where('rel_type', $rel_type);
		$this->db->where('parent_id', $parent_id);
		return $this->db->get(db_prefix() . 'document_online_related')->row();
	}

	/**
	 * get share form hash related
	 * @param  string $hash 
	 * @return row       
	 */
	public function get_share_form_hash_related($hash)
	{
		$this->db->where('hash', $hash);
		return $this->db->get(db_prefix() . 'document_online_related')->row();
	}

	/**
	 * get my folder by client my folder view
	 * @return array
	 */
	public function get_my_folder_by_client_share_folder_view($clientid)
	{
		$this->db->where('find_in_set(' . $clientid . ', clients_share)');
		$data = $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
		if (count($data) > 0) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * [notifications description]
	 * @param  [type] $id_staff    [description]
	 * @param  [type] $link        [description]
	 * @param  [type] $description [description]
	 * @return [type]              [description]
	 */
	public function notifications($id_staff, $link, $description)
	{
		$notifiedUsers = [];
		$id_userlogin = get_staff_user_id();

		$notified = add_notification([
			'fromuserid'      => $id_userlogin,
			'description'     => $description,
			'link'            => $link,
			'touserid'        => $id_staff,
			'additional_data' => serialize([
				$description,
			]),
		]);
		if ($notified) {
			array_push($notifiedUsers, $id_staff);
		}
		pusher_trigger_notification($notifiedUsers);
	}

	/**
	 * array_to_string_unique 
	 * @param  array $array_values
	 * @return string
	 */
	private function array_to_string_unique($array_values)
	{
		$temp_array = [];
		$res_array = [];
		foreach ($array_values as $value) {
			if (!isset($temp_array[$value]) || $value == '') {
				if ($value != '') {
					$temp_array[$value] = $value;
				}
				$res_array[] = $value;
			}
		}
		return implode(',', $res_array);
	}

	/**
	 * add folder 
	 * @param  $data 
	 */
	public function add_chapter($data)
	{

		if (isset($data['id'])) {
			unset($data['id']);
		}
		if (isset($data['document_folder_id'])) {
			if ($data['document_folder_id'] == '') {
				$data['document_folder_id'] = 0;
			}
		}

		$this->db->where('document_folder_id', $data['document_folder_id']);
		$this->db->order_by('ordered', 'desc');
		$ordered_data = $this->db->get(db_prefix() . 'document_chapter')->row();
		if (!empty($ordered_data)) {
			$data['ordered'] = $ordered_data->ordered + 1;
		} else {
			$data['ordered'] = 1;
		}

		$data['pad_id'] = $data['pad_id'];
		$data['number_of_words'] = $data['number_of_words'];
		$data['latest_version'] = $data['latest_version'];
		$data['user_id'] = $data['user_id'];
		$data['name'] = $data['name'];
		$this->db->insert(db_prefix() . 'document_chapter', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}


	/**
	 * edit folder 
	 * @param  $data 
	 * @return boolean
	 */
	public function edit_chapter($data)
	{
		if (isset($data['document_folder_id'])) {
			if ($data['document_folder_id'] == '') {
				$data['document_folder_id'] = 0;
			}
		}
		$this->db->where('id', $data['id']);
		$ordered_data = $this->db->get(db_prefix() . 'document_chapter')->row();
		$data['ordered'] = $ordered_data->ordered;

		$data['pad_id'] = $data['pad_id'];
		$data['number_of_words'] = $data['number_of_words'];
		$data['latest_version'] = $data['latest_version'] + 1;
		$data['user_id'] = $data['user_id'];
		$data['name'] = $data['name'];
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'document_chapter', $data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}
		return false;
	}


	public function get_my_chapter_by_chapter_id($chapter_id)
	{
		$this->db->where('id', $chapter_id);
		return $this->db->get(db_prefix() . 'document_chapter')->row();
	}

	public function delete_chapter($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'document_chapter');
		if ($this->db->affected_rows() >= 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete folder file
	 * @param  int $id
	 * @return boolean
	 */
	public function delete_folder_file($id)
	{
		$this->db->select('name');
		$this->db->where('id', $id);
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'document_online_my_folder');

		if ($this->db->affected_rows() > 0) {
			$this->db->select('id');
			$this->db->where('parent_id', $id);
			$speadsheets = $this->db->get(db_prefix() . 'document_online_my_folder')->result_array();
			if (count($speadsheets) > 0) {
				foreach ($speadsheets as $key => $value) {
					$this->db->select('name');
					$this->db->where('id', $value['id']);
					$this->db->where('id', $value['id']);
					$this->db->delete(db_prefix() . 'document_online_my_folder');

					$this->db->where('parent_id', $value['id']);
					$this->db->delete(db_prefix() . 'document_online_related');

					$this->db->where('id_share', $value['id']);
					$this->db->delete(db_prefix() . 'document_online_hash_share');

					$this->db->where('document_folder_id', $value['id']);
					$this->db->delete(db_prefix() . 'document_chapter');
				}
			}
			//delete all table child
			$this->db->where('parent_id', $id);
			$this->db->delete(db_prefix() . 'document_online_my_folder');

			$this->db->where('parent_id', $id);
			$this->db->delete(db_prefix() . 'document_online_related');

			$this->db->where('id_share', $id);
			$this->db->delete(db_prefix() . 'document_online_hash_share');

			$this->db->where('document_folder_id', $id);
			$this->db->delete(db_prefix() . 'document_chapter');
			return true;
		}
		return false;
	}

	/**
	 * add_ordered
	 * @param  $data 
	 * @return boolean
	 */
	public function add_ordered($id, $ordered)
	{
		$data['ordered'] = $ordered;
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'document_chapter', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * add_chapter_version()
	 * @param  $data 
	 * @return boolean
	 */
	public function add_chapter_version($data)
	{
		$this->db->insert(db_prefix() . 'document_chapter_version', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	/**
	 * get_chapter_version()
	 * @param  $id
	 * @return array
	 */
	public function get_chapter_version($id)
	{
		$this->db->select("*");
		$this->db->from(db_prefix() . 'document_chapter_version');
		$this->db->where("id", $id);
		$query = $this->db->get();
		return $query->row();
	}
}
