<?php



defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'ordered',
    'name',
    'number_of_words',
    'latest_version',
    db_prefix() . 'document_chapter.updated_at',
   
];


$sIndexColumn   = 'ordered';
$sTable         = db_prefix() . 'document_chapter';
$join = [
    'LEFT JOIN ' . db_prefix() . 'document_online_hash_share ON ', db_prefix() . 'document_online_hash_share.id_share = ' . db_prefix() . 'document_chapter.document_folder_id'
];
$where          = [];


$row_document_id = 0;


if ($this->ci->input->post('parent_id') > -1) {
    array_push($where, 'AND ' . db_prefix() . 'document_chapter.document_folder_id = ' . $this->ci->input->post('parent_id'));
}



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
    'document_folder_id','pad_id', db_prefix().'document_online_hash_share.role','user_id', db_prefix() . 'document_chapter.id as chapter_id'
],'GROUP by ' . db_prefix() . 'document_chapter.id');

$output  = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); ++$i) {
        $name = '';   

        if ($row_document_id == $i) {
            $name .= '<span class="chapter_id" data-id="'. $aRow['chapter_id'] .'">'. $aRow['ordered'] .'</span>';
            $name .= '<div class="row-options">';
            if(get_staff_user_id() ==  $aRow['user_id'] || is_admin()){
                $name .= '<a href="'.site_url('document/view_chapter/'.$aRow['chapter_id'].'/'.$aRow['pad_id']).'">View</a><span class="text-dark"> | </span>';
                $name .= '<a href="'.site_url('document/edit_chapter/'.$aRow['chapter_id'].'/'.$aRow['pad_id']).'">Edit</a>';
                $name .= '<span class="text-dark"> | </span><a href="javascript:void(0);" class="delete_chapter" id="delete_chapter" onclick="delete_chapter('.$aRow['chapter_id'].')">Delete</a>';
            }else{
                if($aRow['role'] == 1){
                    $name .= '<a href="'.site_url('document/view_chapter/'.$aRow['chapter_id'].'/'.$aRow['pad_id']).'">View</a>';
                }else{
                    $name .= '<a href="'.site_url('document/edit_chapter/'.$aRow['chapter_id'].'/'.$aRow['pad_id']).'">Edit</a>';
                    $name .= '<span class="text-dark"> | </span><a href="javascript:void(0);" class="delete_chapter" id="delete_chapter" onclick="delete_chapter('.$aRow['chapter_id'].')">Delete</a>';
                }
            }
        
        
            $name .= '</div>';
            $_data = $name;
        }
        else {
            $_data = $aRow[$aColumns[$i]];
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
