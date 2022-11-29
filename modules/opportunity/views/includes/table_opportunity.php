<?php



defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'opportunity.id as opportunity_id',
    'solicitation_title',
    'solicitation_number',
    get_sql_select_client_company(),
    'agency',
    'solicitation_due_date',
    'solicitation_url',
    db_prefix() . 'opportunity_status.name as status_name',
];

$row_opportunity_id = 0;
$row_company = 3;
$row_opportunity_status = 7;

$sIndexColumn   = 'id';
$sTable         = db_prefix() . 'opportunity';
$join = [
    'JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'opportunity.clientid',
    'LEFT JOIN '  . db_prefix() . 'opportunity_status ON '  . db_prefix() . 'opportunity_status.id = ' . db_prefix() . 'opportunity.opportunity_status_id',
];
$where          = [];
$filter         = [];

if (isset($opportunity_status_id) && $opportunity_status_id != '') {
    array_push($where, 'AND ' . db_prefix() . 'opportunity.opportunity_status_id = ' . $opportunity_status_id);
} 

if ($this->ci->input->post('clientid')) {
    array_push($where, 'AND ' . db_prefix() . 'opportunity.clientid = ' . $this->ci->input->post('clientid'));
}

$statuses  = $this->ci->opportunity_model->get_opportunity_status();
$_statuses = [];

foreach ($statuses as $__status) {
    if ($this->ci->input->post('opportunity_status_' . $__status['id'])) {
        array_push($_statuses, $__status['id']);
    }
}

if (count($_statuses) > 0) {
    array_push($filter, 'AND opportunity_status_id IN (' . implode(', ', $_statuses) . ')');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'clientid',
]);

$output  = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    for ($i = 0; $i < count($aColumns); ++$i) {

        if ($row_opportunity_id == $i) {
            $_data = $aRow['opportunity_id'];
        } else if ($row_company == $i) {
            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
        } else if ('solicitation_url' == $aColumns[$i]) {
            $_data = ' <a href="//' . $aRow['solicitation_url'] . '" target="_blank">' . $aRow['solicitation_url'] . '</a>';
        } else if ('solicitation_due_date' == $aColumns[$i]) {
            $_data = _d($aRow['solicitation_due_date']);
        } else if ('solicitation_title' == $aColumns[$i]) {
            $name =  '<a href="' . admin_url('opportunity/view/' . $aRow['opportunity_id']) . '" onclick="init_opportunity(' . $aRow['opportunity_id'] . '); return false;">' . $aRow['solicitation_title'] . '</a>';

            $name .= '<div class="row-options">';

            if (!$this->ci->input->post('client_flag') == 1) {
                $name .= '<a href="javascript:void(0);" onclick="init_opportunity_report(' . $aRow['opportunity_id'] . '); return false;">' . _l('view_report') . '</a>';
            } else {
                $name .= '<a href="' . admin_url('opportunity/view/' . $aRow['opportunity_id'] . '?group=opportunity_report') . '" onclick="init_opportunity_report(' . $aRow['opportunity_id'] . '); return false;">' . _l('view_report') . '</a>';
            }

            if (has_permission('opportunity', '', 'edit') || is_admin()) {
                $name .= ' |<br> <a href="' . admin_url('opportunity/add/' . $aRow['opportunity_id']) . '" onclick="edit_opportunity(' . $aRow['opportunity_id'] . '); return false;">' . _l('edit') . '</a>';
            }

            if (has_permission('opportunity', '', 'delete') || is_admin()) {
                $name .= ' |<br> <a href="' . admin_url('opportunity/delete/' . $aRow['opportunity_id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        } else if ($row_opportunity_status == $i) {
            $_data = $aRow['status_name'];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
