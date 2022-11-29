<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin: 5px 0;
        padding: 0;
        color: #415164;
    }

    table thead {
        background: #415164;
        color: #fff;
        border: 0;
        border: 1px solid black;
    }

    .requirements td {
        color: black;
    }

    .requirements td,
    .requirements th {
        padding: 6px 8px;
    }

    .requirements td {
        border: 1px solid #415164;
    }

    .p-win {
        width: 16.6%;
        padding: 5px;
    }

    .p-win .pwin_class {
        text-align: center;
        width: 90%;
        height: 50px;
        vertical-align: middle;
        margin: 0 5%;
        color: #878480;
        border: 1px dashed #333;
    }

    .active .p_active {
        border: 3px solid #333;
        color: #111;
        padding-top: 6px;
        height: 68px;
        width: 152px;
        background-color: #fff !important;
    }

    .active .pwin_class {
        color: black;
    }

    .p-win-1 .pwin_class {
        background-color: #77d122;
    }

    .p-win-2 .pwin_class {
        background-color: #98e239;
    }

    .p-win-3 .pwin_class {
        background-color: #98e239;
    }

    .p-win-4 .pwin_class {
        background-color: #fffe31;
    }

    .p-win-5 .pwin_class {
        background-color: #fd7b48;
    }

    .p-win-6 .pwin_class {
        background-color: #fd2118;
    }

    .top-table h3 span {
        font-size: 16px;
        color: #878480;
    }

    .dueindays {
        color: red;
    }
</style>
<div class="panel_s">
    <div class="panel-body">
        <div class="row mtop10">
            <div class="col-md-12 _buttons">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-file-pdf-o"></i>
                            <?php if (is_mobile()) {
                                echo ' PDF';
                            } ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="hidden-xs">
                                <a href="<?php echo admin_url('opportunity/pdf/' . $opportunity->id . '?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a>
                            </li>
                            <li class="hidden-xs">
                                <a href="<?php echo admin_url('opportunity/pdf/' . $opportunity->id . '?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('opportunity/pdf/' . $opportunity->id); ?>"><?php echo _l('download'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('opportunity/pdf/' . $opportunity->id . '?print=true'); ?>" target="_blank">
                                    <?php echo _l('print'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="#" class="invoice-send-to-client btn-with-tooltip btn btn-default" data-toggle="tooltip" title="" data-placement="bottom"><span data-toggle="tooltip" data-title=""><i class="fa fa-envelope"></i></span></a>
                    <?php
                    if ($_GET['group'] != 'opportunity_report' && $client_flag == 0) {
                    ?>
                        <a href="#" class="btn-with-tooltip btn btn-default" onclick="small_table_full_view(); return false;">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-opportunity','#opportunity_report'); return false;" data-toggle="tooltip" title="<?php echo _l('opportunity_toggle_table_tooltip'); ?>">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr class="hr-panel-heading" />
        <div class="report_view">
            <div class="row">
                <div class="col-md-9">
                    <h5> <?php echo _l('generated_by') . " " . get_company_name($opportunity->clientid) ?></h5>
                </div>
                <div class="col-md-3">
                    <?php echo get_company_logo(); ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr class="hr-panel-heading" />
        </div>
        <div class="row" style="margin-right: 10px;margin-left: 10px;">
            <?php
            if (isset($opportunity->solicitation_due_date) && isset($opportunity->solicitation_created)) {
                $solicitation_due_date = strtotime($opportunity->solicitation_due_date);
                $solicitation_created = strtotime($opportunity->solicitation_created);
                $total_days = ceil(abs($solicitation_due_date - $solicitation_created) / 86400);
            }
            ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;" class="top-table">
                <tbody>
                    <tr>
                        <td style="width: 50%;">
                            <h5 class="bold"><b>Sol. Title:</b> <?php echo isset($opportunity->solicitation_title) ? $opportunity->solicitation_title : ''; ?></h5>
                            <p><b>Sol. No.:</b> <?php echo isset($opportunity->solicitation_number) ? $opportunity->solicitation_number : ''; ?></p>
                            <p><?php echo isset($opportunity->agency) ? $opportunity->agency : ''; ?></p>
                        </td>
                        <td style="width: 50%">
                            <p><span class="bold"><?php echo _l('due_date') ?></span> : <?php echo isset($opportunity->solicitation_due_date) ? date("M d, Y", strtotime($opportunity->solicitation_due_date)) : ''; ?></p>
                            <p><span class="bold"><?php echo _l('generated') ?></span> : <?php echo isset($opportunity->solicitation_created) ? date("M d, Y H:i A", strtotime($opportunity->solicitation_created)) : ''; ?></p>
                            <p class="<?php echo $total_days < 7 ? 'dueindays' : '' ?> bold"><?php echo _l('due_in') ?> <?php echo isset($total_days) ? $total_days : ''; ?> <?php echo _l('days') ?></p>
                            <a href="//<?php echo isset($opportunity->solicitation_url) ? $opportunity->solicitation_url : ''; ?>">
                                <p><?php echo isset($opportunity->solicitation_url) ? $opportunity->solicitation_url : ''; ?></p>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: 1px solid #777;padding-bottom: 15px; padding-top: 15px;">
                            <p><?php echo isset($opportunity->solicitation_description) ? $opportunity->solicitation_description : ''; ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%; border-collapse: collapse;margin-top: 15px;">
                <tbody>
                    <tr>
                        <td>
                            <h4 class="bold"><?php echo _l('gap_analysis') ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #777;padding-bottom: 15px;padding-top: 15px;">
                            <table class="requirements" style="width: 100%;">
                                <thead>
                                    <tr style="border: 2px solid #415164;">
                                        <th style="border: 1px solid white;"><?php echo _l('requirement') ?></th>
                                        <th style="border: 1px solid white;"><?php echo _l('gap_yes_no') ?>?</th>
                                        <th style="border: 1px solid white;"><?php echo _l('risk_rating') ?></th>
                                        <th style="border: 1px solid white;"><?php echo _l('action_items') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ability_to_respond_to_solicitation = isset($opportunity->ability_to_respond_to_solicitation) ? $opportunity->ability_to_respond_to_solicitation : '';

                                    if (!empty($requirements)) {
                                        foreach ($requirements as $key => $requirement) {
                                            $gap_yes_no = $requirement['gap_yes_no'];
                                            $calculate_gap = calculate_gap($gap_yes_no, $ability_to_respond_to_solicitation);

                                            $impacts_priority = $requirement['impacts_priority'];
                                            $risks_priority = $requirement['risks_priority'];
                                            $calculate_risk_rating = calculate_risk_rating($impacts_priority, $risks_priority);

                                            $requirement['calculate_gap'] = $calculate_gap;
                                            $requirement['calculate_risk_rating'] = $calculate_risk_rating;
                                    ?>
                                            <tr>
                                                <td style="width: 40%;"><?php echo isset($requirement['requirement']) ? $requirement['requirement'] : '' ?></td>
                                                <td style="width: 10%; background-color: <?php echo isset($calculate_gap) ? $calculate_gap[2] : '' ?>"><?php echo isset($calculate_gap) ? $calculate_gap[1] : '' ?> (<?php echo isset($calculate_gap) ? $calculate_gap[0] : '' ?>)</td>
                                                <td style="width: 15%; background-color: <?php echo isset($calculate_risk_rating) ? $calculate_risk_rating[2] : '' ?>"><?php echo isset($calculate_risk_rating) ? $calculate_risk_rating[1] : '' ?> (<?php echo isset($calculate_risk_rating) ? $calculate_risk_rating[0] : '' ?>)</td>
                                                <td style="width: 35%;"><?php echo isset($requirement['action_items']) ? $requirement['action_items'] : '' ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 100%; padding-top: 10px; padding-bottom: 5px;">
                            <h4 class="bold"><?php echo _l('additional_notes') ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #777;padding-bottom: 15px">
                            <p><?php echo isset($opportunity->additional_notes) ? $opportunity->additional_notes : ''; ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td style="width: 100%; padding-top: 15px; padding-bottom: 5px;">
                            <h4 class="bold"><?php echo _l('recommendation') ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #777;padding-bottom: 15px">
                            <p><?php echo isset($opportunity->recommendation) ? $opportunity->recommendation : ''; ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td style="width: 100%; padding-top: 15px; padding-bottom: 15px;">
                            <h4 class="bold"><?php echo _l('p-win') ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #777;padding-bottom: 15px">
                            <table style="width: 100%;">
                                <tbody>
                                    <?php
                                    $pwin = calculate_pwin($ability_to_respond_to_solicitation);

                                    $pwin_table_index = pwin_table_index($pwin);
                                    ?>
                                    <tr>
                                        <td class="<?php echo isset($pwin_table_index) && $pwin_table_index == 0 ? 'p-win p-win-1 active' : 'p-win p-win-1' ?>">
                                            <div class="p_active">
                                                <div class="pwin_class"><?php echo _l('bid') ?></div>
                                            </div>
                                        </td>
                                        <td class="<?php echo isset($pwin_table_index) && $pwin_table_index == 1 ? 'p-win p-win-2 active' : 'p-win p-win-2' ?>">
                                            <div class="p_active">
                                                <div class="pwin_class"><?php echo _l('agree_or_likely') ?></div>
                                            </div>
                                        </td>
                                        <td class="<?php echo isset($pwin_table_index) && $pwin_table_index == 2 ? 'p-win p-win-3 active' : 'p-win p-win-3' ?>">
                                            <div class="p_active">
                                                <div class="pwin_class"><?php echo _l('agree_or_maybe') ?></div>
                                            </div>
                                        </td>
                                        <td class="<?php echo isset($pwin_table_index) && $pwin_table_index == 3 ? 'p-win p-win-4 active' : 'p-win p-win-4' ?>">
                                            <div class="p_active">
                                                <div class="pwin_class"><?php echo _l('neutral') ?></div>
                                            </div>
                                        </td>
                                        <td class="<?php echo isset($pwin_table_index) && $pwin_table_index == 4 ? 'p-win p-win-5 active' : 'p-win p-win-5' ?>">
                                            <div class="p_active">
                                                <div class="pwin_class"><?php echo _l('low_p-win') ?></div>
                                            </div>
                                        </td>
                                        <td class="<?php echo isset($pwin_table_index) && $pwin_table_index == 5 ? 'p-win p-win-6 active' : 'p-win p-win-6' ?>">
                                            <div class="p_active">
                                                <div class="pwin_class"><?php echo _l('no_bid') ?></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('opportunity_mail_send'); ?>