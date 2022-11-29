<?php

use Braintree\Xml;

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();


$organization_info = '<div style="color:#424242;">';

$organization_info .=  _l('generated_by');

$organization_info .=   " ";

$organization_info .= get_company_name($opportunity->clientid);

$organization_info .= '</div>';

// Bill to
$invoice_info = pdf_logo_url();

$left_info  = $swap == '1' ? $invoice_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $invoice_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
$pdf->ln(10);

$info_right_column = '';
$info_left_column  = '';

if (isset($opportunity->solicitation_due_date) && isset($opportunity->solicitation_created)) {
    $solicitation_due_date = strtotime($opportunity->solicitation_due_date);
    $solicitation_created = strtotime($opportunity->solicitation_created);
    $total_days = ceil(abs($solicitation_due_date - $solicitation_created) / 86400);
}

$info_left_column .= "<b>Sol. Title: </b>";
$info_left_column .= '<b style="color:#4e4e4e;"> ' . isset($opportunity->solicitation_title) ? $opportunity->solicitation_title : "" . '</b>';
$info_left_column .= '<br/>';
$info_left_column .= "<b>Sol. No.: </b>";
$info_left_column .= '<p>' . isset($opportunity->solicitation_number) ? $opportunity->solicitation_number : "" . '</p>';
$info_left_column .= '<br/>';
$info_left_column .= '<p>' . isset($opportunity->agency) ? $opportunity->agency : "" . '</p>';

$info_right_column .= "<b>Due Date : </b>";
$info_right_column .= isset($opportunity->solicitation_due_date) ? date("M d, Y", strtotime($opportunity->solicitation_due_date)) : "";
$info_right_column .= "<br/>";
$info_right_column .= '<b>' . _l("generated") . ' </b>';
$info_right_column .= isset($opportunity->solicitation_created) ? date("M d, Y H:i A", strtotime($opportunity->solicitation_created)) : "";
$info_right_column .= "<br/>";
$info_right_column .= '<b>' . _l("due_in") . ' ';
$info_right_column .= isset($total_days) ? $total_days : "";
$info_right_column .= " " . _l("days");
$info_right_column .= "</b><br/>";
$info_right_column .= '<p>' . isset($opportunity->solicitation_url) ? $opportunity->solicitation_url : "" . '</p>';

pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
$pdf->ln(9);

$solicitation_description = '<p>' . isset($opportunity->solicitation_description) ? $opportunity->solicitation_description : "" . '</p>';
$pdf->writeHTML($solicitation_description, true, false, false, false, '');
$pdf->ln(8);

$gap_analysis = '<h4><b>' . _l("gap_analysis") . '<b></h4>';

$pdf->writeHTML($gap_analysis, true, false, false, false, '');
$pdf->ln(7);

$tblhtml = '<table cellpadding="4" class="requirements" style="width: 100%;">';
$tblhtml .= '<thead style="width: 100%;background-color: #415164;color: #fff;border: 0;border: 1px solid black;">';
$tblhtml .= '<tr style="background-color: #415164;color: #fff;">';
$tblhtml .= '<th style="width: 50%;background-color: #415164;color: #fff;border: 1px solid black;">' . _l('requirement') . '</th>';
$tblhtml .= '<th style="width: 10%;background-color: #415164;color: #fff;border: 1px solid black;">' . _l('gap_yes_no') . '?</th>';
$tblhtml .= '<th style="width: 10%;background-color: #415164;color: #fff;border: 1px solid black;">' . _l('risk_rating') . '</th>';
$tblhtml .= '<th style="width: 30%;background-color: #415164;color: #fff;border: 1px solid black;">' . _l('action_items') . '</th>';
$tblhtml .= '</tr>';
$tblhtml .= '</thead>';
$tblhtml .= '<tbody>';

$ability_to_respond_to_solicitation = $opportunity->ability_to_respond_to_solicitation;

if (!empty($requirements)) {
    foreach ($requirements as $key => $requirement) {
        $gap_yes_no = $requirement['gap_yes_no'];

        $calculate_gap =  calculate_gap($gap_yes_no, $ability_to_respond_to_solicitation);

        $impacts_priority = $requirement['impacts_priority'];
        $risks_priority = $requirement['risks_priority'];
        $calculate_risk_rating =   calculate_risk_rating($impacts_priority, $risks_priority);
        $requirement['calculate_gap'] = $calculate_gap;
        $requirement['calculate_risk_rating'] = $calculate_risk_rating;

        $tblhtml .= '<tr>';
        $tblhtml .= '<td style="width: 50%;border: 1px solid black;">';
        $tblhtml .= isset($requirement['requirement']) ? $requirement['requirement'] : "";
        $tblhtml .= '</td>';
        $calculate_gap_color = isset($calculate_gap) ? $calculate_gap[2] : '';
        $tblhtml .= '<td style="width: 10%;border: 1px solid black;background-color:' . $calculate_gap_color . ';">';
        $tblhtml .= isset($calculate_gap) ? $calculate_gap[1] : "";
        $tblhtml .= '( ' . isset($calculate_gap) ? $calculate_gap[0] : "" . ')';
        $tblhtml .= '</td>';
        $calculate_risk_rating_background = isset($calculate_risk_rating) ? $calculate_risk_rating[2] : '';
        $tblhtml .= '<td style="width: 10%;border: 1px solid black;background-color:' . $calculate_risk_rating_background . ';">';
        $tblhtml .= isset($calculate_risk_rating) ? $calculate_risk_rating[1] : "";
        $tblhtml .= '(' . isset($calculate_risk_rating) ? $calculate_risk_rating[0] : "" . ')';
        $tblhtml .= '</td>';
        $tblhtml .= '<td style="width: 30%;border: 1px solid black;">';
        $tblhtml .= isset($requirement['action_items']) ? $requirement['action_items'] : "";
        $tblhtml .= '</td>';
        $tblhtml .= '</tr>';
    }
}
$tblhtml .= '</tbody>';
$tblhtml .= '</table>';

$pdf->writeHTML($tblhtml, true, false, false, false, '');
// The Table
$pdf->Ln(6);

$additional_notes = '<h4><b>' . _l("additional_notes") . '<b></h4>';
$pdf->writeHTML($additional_notes, true, false, false, false, '');
$pdf->Ln(5);

$additional_notes_list = '<p>';
$additional_notes_list .= isset($opportunity->additional_notes) ? $opportunity->additional_notes : '';
$additional_notes_list .= '</p>';
$pdf->writeHTML($additional_notes_list, true, false, false, false, '');

$pdf->Ln(4);

$recommendation = '<h4><b>' . _l('recommendation') . '<b></h4>';
$pdf->writeHTML($recommendation, true, false, false, false, '');

$pdf->Ln(3);

$recommendation_list = '<p>';
$recommendation_list .= isset($opportunity->recommendation) ? $opportunity->recommendation : '';
$recommendation_list .= '</p>';
$pdf->writeHTML($recommendation_list, true, false, false, false, '');

$pdf->Ln(2);

$p_win = '<h4><b>' . _l('p-win') . '<b></h4>';
$pdf->writeHTML($p_win, true, false, false, false, '');

$pdf->Ln(1);

$pwin = calculate_pwin($ability_to_respond_to_solicitation);

$pwin_table_index =  pwin_table_index($pwin);

$p_win_list = '<table style="width: 100%;border-spacing: 10px;">';

$p_win_list .= '<tbody>';

$p_win_list .= '<tr>';

$pwin_table_index_0 = isset($pwin_table_index) && $pwin_table_index == 0  ? 'p-win p-win-1 active' : 'p-win p-win-1';

$active_0 = isset($pwin_table_index) && $pwin_table_index == 0 ? 'border: 3px solid #333;color:black !important' : 'color:#878480';

$p_win_list .= '<td class=" ' . $pwin_table_index_0 . ' " style="width: 16.6%;padding: 5px;">';
$p_win_list .= '<p class="p_active">';
$p_win_list .= '<p class="pwin_class" style="text-align: center;height: 50px;vertical-align: middle;margin: 0 5%;'.$active_0.';background-color: #77d122;">' . _l('bid') . '</p>';
$p_win_list .= '</p>';
$p_win_list .= '</td>';

$pwin_table_index_1 = isset($pwin_table_index) && $pwin_table_index == 1 ? 'p-win p-win-2 active' : 'p-win p-win-2';

$active_1 = isset($pwin_table_index) && $pwin_table_index == 1 ? 'border: 3px solid #333;color:black !important' : 'color:#878480';

$p_win_list .=  '<td class=" ' . $pwin_table_index_1 . ' " style="width: 16.6%;padding: 5px;height:50px;">';
$p_win_list .= '<p class="p_active" >';
$p_win_list .=  '<p class="pwin_class" style="text-align: center;height: 50px;vertical-align: middle;margin: 0 5%;'.$active_1.';background-color: #98e239;"> ' . _l('agree_or_likely') . ' </p>';
$p_win_list .= '</p>';
$p_win_list .=  '</td>';

$pwin_table_index_2 = isset($pwin_table_index) && $pwin_table_index == 2 ? 'p-win p-win-3 active' : 'p-win p-win-3';

$active_2 = isset($pwin_table_index) && $pwin_table_index == 2 ? 'border: 3px solid #333;color:black !important' : 'color:#878480';

$p_win_list .= '<td class=" ' . $pwin_table_index_2 . ' " style="width: 16.6%;padding: 5px;">';
$p_win_list .= '<p class="p_active">';
$p_win_list .= '<p class="pwin_class" style="text-align: center;height: 50px;vertical-align: middle;margin: 0 5%;'.$active_2.';background-color: #98e239;"> ' . _l('agree_or_maybe') . ' </p>';
$p_win_list .= '</p>';
$p_win_list .= '</td>';

$pwin_table_index_3 = isset($pwin_table_index) && $pwin_table_index == 3 ? 'p-win p-win-4 active' : 'p-win p-win-4';

$active_3 = isset($pwin_table_index) && $pwin_table_index == 3 ? 'border: 3px solid #333;color:black !important' : 'color:#878480';

$p_win_list .= '<td class=" ' . $pwin_table_index_3 . ' " style="width: 16.6%;padding: 5px;">';
$p_win_list .= '<p class="p_active">';
$p_win_list .=  '<p class="pwin_class" style="text-align: center;height: 50px;vertical-align: middle;margin: 0 5%;'.$active_3.';background-color: #fffe31;"> ' . _l('neutral') . ' </p>';
$p_win_list .= '</p>';
$p_win_list .= '</td>';

$pwin_table_index_4 = isset($pwin_table_index) && $pwin_table_index == 4 ? 'p-win p-win-5 active' : 'p-win p-win-5';

$active_4 = isset($pwin_table_index) && $pwin_table_index == 4 ? 'border: 3px solid #333;color:black !important' : 'color:#878480';

$p_win_list .= '<td class=" ' . $pwin_table_index_4 . ' " style="width: 16.6%;padding: 5px;">';
$p_win_list .= '<p class="p_active">';
$p_win_list .= '<p class="pwin_class" style="text-align: center;height: 50px;vertical-align: middle;margin: 0 5%;'.$active_4.';background-color: #fd7b48;"> ' . _l('low_p-win') . ' </p>';
$p_win_list .= '</p>';
$p_win_list .= '</td>';

$pwin_table_index_5 = isset($pwin_table_index) && $pwin_table_index == 5 ? 'p-win p-win-6 active' : 'p-win p-win-6';

$active_5 = isset($pwin_table_index) && $pwin_table_index == 5 ? 'border: 3px solid #333;color:black !important' : 'color:#878480';

$p_win_list .= '<td class=" ' . $pwin_table_index_5 . '" style="width: 16.6%;padding: 5px;">';
$p_win_list .= '<p class="p_active">';
$p_win_list .= '<p class="pwin_class" style="text-align: center;height: 50px;vertical-align: middle;margin: 0 5%;'.$active_5.';background-color: #fd2118;"> ' . _l('no_bid') . ' </p>';
$p_win_list .= '</p>';
$p_win_list .= '</td>';

$p_win_list .= '</tr>';

$p_win_list .= '</tbody>';

$p_win_list .= '</table>';

//echo $p_win_list;exit;

$pdf->writeHTML($p_win_list, true, false, false, false, '');
