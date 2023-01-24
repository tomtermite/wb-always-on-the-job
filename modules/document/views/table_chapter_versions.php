<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'version',
    'IF(staff_id > 0, "Staff", "Client") as user_type',
    'IF(staff_id > 0,  CONCAT('.db_prefix() . 'staff.firstname, " ", '.db_prefix().'staff.lastname), CONCAT('.db_prefix() . 'contacts.firstname, " ", '.db_prefix().'contacts.lastname)) as user',
    'description',
    'inserted_at'
];


$sIndexColumn   = 'version';
$sTable         = db_prefix() . 'document_chapter_version';
$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ', db_prefix() . 'document_chapter_version.staff_id = ' . db_prefix() . 'staff.staffid',
    'LEFT JOIN ' . db_prefix() . 'contacts ON ', db_prefix() . 'document_chapter_version.client_id = ' . db_prefix() . 'contacts.userid'
];
$where          = [];


$row_document_id = 0;


if ($this->ci->input->post('chapter_id') > -1) {
    array_push($where, 'AND ' . db_prefix() . 'document_chapter_version.document_chapter_id = ' . $this->ci->input->post('chapter_id'));
}



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
    db_prefix() . 'document_chapter_version.id as id',
    
],'');

$output  = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    
    $name = '<span class="chapter_id" data-id="'. $aRow['version'] .'">'. $aRow['version'] .'</span>';
    $description = '<a href="javascript:void(0);" onclick="open_description('. $aRow['id'] .');" class="btn btn-default"><i class="fa fa-eye"></i></a>';
    $row[] = $name;
    $row[] = $aRow['user_type'];
    $row[] = $aRow['user'];
    $row[] = $description;
    $row[] = date("Y-m-d", strtotime($aRow['inserted_at']));
    
    $output['aaData'][] = $row;
}
