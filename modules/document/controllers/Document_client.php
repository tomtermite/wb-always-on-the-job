<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Team password client controller
 */
class Document_client extends ClientsController
{
  /**
   * __construct
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('document_model');
  }
    /**
     * index 
     * @param  int $page 
     * @param  int $id   
     * @param  string $key  
     * @return view       
     */
    public function index(){  
     if(is_client_logged_in()){
      $data['folder_my_share_tree'] = $this->document_model->tree_my_folder_share_client();
      $data['title'] = _l('document');
      $this->data($data);
      $this->view('clients/client_share');
      $this->layout();
    }else{
      redirect(site_url('authentication'));
    }
  } 

      /**
     * get hash client
     * @param int $id 
     * @return json    
     */
      public function get_hash_client($id){
        $rel_id = get_client_user_id();
        $rel_type = 'client';
        echo json_encode($this->document_model->get_hash($rel_type, $rel_id, $id));
      }

    /**
     * new file view 
     * @param  int $parent_id 
     * @param  int $id        
     * @return  view or json            
     */
    public function file_view_share($hash = ""){
      if(is_client_logged_in()){
        $data_form = $this->input->post();
        $data['tree_save'] = json_encode($this->document_model->get_folder_tree());
        
        if($hash != ""){
          $share_child = $this->document_model->get_share_form_hash($hash);
          $id = $share_child->id_share;
          $file_excel = $this->document_model->get_file_sheet($id);
          $data['parent_id'] = $file_excel->parent_id;
          $data['role'] = $share_child->role;
          if (($share_child->rel_id != get_client_user_id())) {
            access_denied('document');
          }
        }else{
          $id = "";
          $data['parent_id'] = "";
          $data['role'] = 1;
        }

        $data_form = $this->input->post();
        $data['title'] = _l('new_file');
        $data['folder'] = $this->document_model->get_my_folder_all();
        if($data_form || isset($data_form['id'])){
          if($data_form['id'] == ""){
            $success = $this->document_model->add_file_sheet($data_form);
            if(is_numeric($success)){
              $message = _l('added_successfully');
              $file_excel = $this->document_model->get_file_sheet($success);
              echo json_encode(['success' => true, 'message' => $message, 'name_excel' => $file_excel->name ]);
            }
            else{
              $message = _l('added_fail');
              echo json_encode(['success' => false, 'message' => $message]);
            }
          }
        }
        if($id != "" || isset($data_form['id'])){
          if(isset($data_form['id'])){
            if($data_form['id'] != ""){
              $data['id'] = $data_form['id'];
            }
          }else{
            $data['id'] = $id;
            // process hanlde file                                 
            $data_file = process_file($id);
            if(isset($data_file['data_form'])){
              $data['data_form'] = $data_file['data_form'];
              $data['name'] = $data_file['name'];
            }
          }

          if($data_form && $data_form['id'] != ""){
            $success = $this->document_model->edit_file_sheet($data_form);
            if($success == true){
              $message = _l('updated_successfully');
              echo json_encode(['success' => $success, 'message' => $message]);
            }
            else{
              $message = _l('updated_fail');
              echo json_encode(['success' => $success, 'message' => $message]);
            }
          }
        }
        if(!isset($success)){
          $this->data($data);
          $this->view('share_file_view_client');
          $this->layout();
        }
      }else{
        redirect(site_url('authentication'));
      }
      
    }

    /**
     * Add edit folder
    */
    public function add_edit_folder_client(){
      if($this->input->post()){
        $data = $this->input->post();    
        if($data['id'] == ''){
          $id = $this->document_model->add_folder($data);
          if(is_numeric($id)){
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          else{
            $message = _l('added_fail');
            set_alert('warning', $message);
          }
        }
        else{
          $res = $this->document_model->edit_folder($data);
          if($res == true){
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          else{
            $message = _l('updated_fail');
            set_alert('warning', $message);
          }
        }
        redirect(site_url('document/document_client'));
      }    
    }
    
    /**
     * new file view 
     * @param  int $parent_id 
     * @param  int $id        
     * @return  view or json            
     */
    public function file_view_share_related($hash = ""){
      $data_form = $this->input->post();
      $data['tree_save'] = json_encode($this->document_model->get_folder_tree());
      
      if($hash != ""){
        $share_child = $this->document_model->get_share_form_hash_related($hash);
        $id = $share_child->parent_id;
        $file_excel = $this->document_model->get_file_sheet($id);
        $data['parent_id'] = $file_excel->parent_id;
        $data['role'] = $share_child->role;
      }else{
        $id = "";
        $data['parent_id'] = "";
        $data['role'] = 1;
      }

      $data_form = $this->input->post();
      $data['title'] = _l('new_file');
      $data['folder'] = $this->document_model->get_my_folder_all();
      if($data_form || isset($data_form['id'])){
        if($data_form['id'] == ""){
          $success = $this->document_model->add_file_sheet($data_form);
          if(is_numeric($success)){
            $message = _l('added_successfully');
            $file_excel = $this->document_model->get_file_sheet($success);
            echo json_encode(['success' => true, 'message' => $message, 'name_excel' => $file_excel->name ]);
          }
          else{
            $message = _l('added_fail');
            echo json_encode(['success' => false, 'message' => $message]);
          }
        }
      }
      if($id != "" || isset($data_form['id'])){
        if(isset($data_form['id'])){
          if($data_form['id'] != ""){
            $data['id'] = $data_form['id'];
          }
        }else{  
          $data['id'] = $id;
          // process hanlde file                                 
          $data_file = process_file($id);
          if(isset($data_file['data_form'])){
            $data['data_form'] = $data_file['data_form'];
            $data['name'] = $data_file['name'];
          }
        }

        if($data_form && $data_form['id'] != ""){
          $success = $this->document_model->edit_file_sheet($data_form);
          if($success == true){
            $message = _l('updated_successfully');
            echo json_encode(['success' => $success, 'message' => $message]);
          }
          else{
            $message = _l('updated_fail');
            echo json_encode(['success' => $success, 'message' => $message]);
          }
        }
      }
      if(!isset($success)){
        $this->data($data);
        $this->view('share_file_view_client');
        $this->layout();
      }
    }



     /**
     * [check_file_exits description]
     * @param  [type] $id_set [description]
     * @return [type]         [description]
     */
    public function check_file_exits($id_set){
      if(is_numeric($id_set)){
        $data = $this->document_model->get_my_folder($id_set);
        if($data->realpath_data != '' && $data->realpath_data != NULL){
          if(!file_exists(SPREAD_ONLINE_MODULE_UPLOAD_FOLDER.$data->realpath_data)){
            echo json_encode(['message' => _l('physical_file_have_been_deleted'), 'success' => false ]);
          }else{
            echo json_encode(['message' => "success", 'success' => true ]);
          }
        }else{
          if($data->realpath_data == '' && $data->data_form != NULL && $data->data_form != ''){
            $path = SPREAD_ONLINE_MODULE_UPLOAD_FOLDER . '/spreadsheet_online/' . $id_set . '-'.$data->name.'.txt';
            $realpath_data = '/spreadsheet_online/' . $id_set . '-'.$data->name.'.txt';
            file_force_contents($path, $data->data_form);
            $this->db->where('id', $id_set);
            $this->db->update(db_prefix() . 'spreadsheet_online_my_folder', ['realpath_data' => $realpath_data]);
            echo json_encode(['message' => "success", 'success' => true ]);
          }else{
            echo json_encode(['message' => _l('physical_file_have_been_deleted'), 'success' => false ]);
          }
        }
      }else{
        echo json_encode(['message' => _l('physical_file_have_been_deleted'), 'success' => false ]);
      }
    }

    	/**
       * new file view 
       * @param  int $parent_id 
       * @param  int $id        
       * @return  view or json            
       */
      public function client_file_view($parent_id)
      {
        $data['title'] = _l('new_file');
        $data['parent_id'] = $parent_id;

        if (!isset($success)) {
          $this->data($data);
          $this->view('clients/client_file_view');
          $this->layout();
        }
      }

      /** table for new file */
      public function table()
      {
        $response = array();

        ## Read value

        $postData = $this->input->post();
      
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $parent_id = $postData['parent_id'];
        $searchValue = $postData['search']['value'];


      ## Search 
      $searchQuery = "";
      if ($searchValue != '') {
          $searchQuery = " (name like '%" . $searchValue . "%' or number_of_words like '%" . $searchValue . "%' or latest_version like'%" . $searchValue . "%' ) ";
      }

        ## Total number of records without filtering
        // $this->db->select('count(*) as allcount');
        $this->db->where(db_prefix() . 'document_chapter.document_folder_id', $parent_id);
        $this->db->group_by(db_prefix() . 'document_chapter.id');
        $records = $this->db->get(db_prefix() . 'document_chapter')->num_rows();

        $totalRecords = $records;

        ## Total number of record with filtering
        // $this->db->select('count(*) as allcount');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        
        $this->db->where(db_prefix() . 'document_chapter.document_folder_id', $parent_id);
        $this->db->group_by(db_prefix() . 'document_chapter.id');
        $records = $this->db->get(db_prefix() . 'document_chapter')->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select(db_prefix() . 'document_chapter.*');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }

        $this->db->where(db_prefix() . 'document_chapter.document_folder_id', $parent_id);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->group_by(db_prefix() . 'document_chapter.id');
        $records = $this->db->get(db_prefix() . 'document_chapter')->result();

        $data = array();
        foreach ($records as $record) {
          $action_tag = '';
          if($record->role == 1){
                $action_tag .= '<a href="'.site_url('document/document_client/view_chapter/'.$record->id.'/'.$record->pad_id).'">View</a><br>'; 
          }else{
            $action_tag .= '<a href="'.site_url('document/document_client/edit_chapter/'.$record->id.'/'.$record->pad_id).'">Edit</a><br>'; 
          }
            $data[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "number_of_words" => $record->number_of_words,
                "latest_version" => $record->latest_version,
                "updated_at" => $record->updated_at,
              
                "action" => $action_tag,
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
      }


      public function edit_chapter($id)
      {
        $all_data = $this->document_model->get_my_chapter_by_chapter_id($id);

        $data['id'] = $all_data->id;
        $data['parent_id'] = $all_data->document_folder_id;
        $data['file_name'] = $all_data->name;
        $data['pad_id'] = $all_data->pad_id;
        $data['description'] = $all_data->description;
        $data['version'] = $all_data->latest_version;
        $data['type'] = "edit";
        $this->data($data);
        $this->view('clients/client_chapter_view');
        $this->layout();
        
      }

      public function view_chapter($id)
      {
        $all_data = $this->document_model->get_my_chapter_by_chapter_id($id);
        $data['id'] = $all_data->id;
        $data['parent_id'] = $all_data->document_folder_id;
        $data['file_name'] = $all_data->name;
        $data['pad_id'] = $all_data->pad_id;
        $data['description'] = $all_data->description;
        $data['version'] = $all_data->latest_version;
        $data['type'] = "view";

        $this->data($data);
        $this->view('clients/client_chapter_view');
        $this->layout();
      }

      public function update_chapter()
      {
        $result['status'] = 0;
        $data = $this->input->post();
        if(!empty($data))
        {
          $chapter_data = $this->document_model->get_my_chapter_by_chapter_id($data['id']);

          $data['latest_version'] = $chapter_data->latest_version;
          $res = $this->document_model->edit_chapter($data);
          if ($res == true) {

            $version = $chapter_data->latest_version + 1;
            $version_data = [];
            $version_data['client_id'] = get_client_user_id();
            $version_data['document_chapter_id'] = $data['id'];
            $version_data['version'] = $version;
            $version_data['description'] = $data['description'];
            $this->document_model->add_chapter_version($version_data);
            
            $result['message'] = _l('updated_successfully');
            $result['status'] = 1;
            $result['version'] = $version;
          } else {
            $result['message'] = _l('updated_fail');
          }
        }
        
        echo json_encode($result);
        die;
      }

  }