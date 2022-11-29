<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'requirement',
    'risks',
    'risks_priority',
    'impacts',
    'impacts_priority',
    'action_items',
    'gap_yes_no',
];

$sIndexColumn   = 'id';
$sTable         = db_prefix() . 'requirement';
$join           = [];
$where          = [];
$filter         = [];

if (isset($opportunity_id)) {
    array_push($where, 'AND ' . db_prefix() . 'requirement.opportunity_id = ' . $opportunity_id);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    for ($i = 0; $i < count($aColumns); ++$i) {
        $_data = $aRow[$aColumns[$i]];

        if ('requirement' == $aColumns[$i]) {
            $name = $aRow['requirement'];

            $name .= '<div class="row-options">';
            if (has_permission('opportunity', '', 'edit') || is_admin()) { 
            $name .= ' <a href="javascript:void(0)" onclick="edit_requirement(this,'.$aRow['id'].'); return false;" data-requirement="'.$aRow['requirement'].'" data-risks="'.$aRow['risks'].'" data-risks_priority="'.$aRow['risks_priority'].'" data-impacts="'.$aRow['impacts'].'" data-impacts_priority="'.$aRow['impacts_priority'].'" data-action_items="'.$aRow['action_items'].'" data-gap_yes_no="'.$aRow['gap_yes_no'].'">' . _l('edit') . '</a>';
            }
            if (has_permission('opportunity', '', 'delete') || is_admin()) { 
            $name .= ' |<br> <a href="' . admin_url('opportunity/delete_requirement/' . $aRow['id']) . '/'. $opportunity_id . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $name .= '</div>';

            $_data = $name;
        }

        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
