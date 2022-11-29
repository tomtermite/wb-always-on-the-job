<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin text-uppercase">
                            <?php echo isset($title) ? $title : ''; ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open_multipart(admin_url('opportunity/insert'), ['id' => 'opportunity-form']); ?>
                        <?php echo form_hidden('id', isset($opportunity) ? $opportunity->id : '') ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group select-placeholder">
                                    <label for="clientid" class="control-label"><?php echo _l('solicitation_customer'); ?></label>
                                    <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <?php
                                        $selected_client_id = (isset($_GET['client_id']) ? $_GET['client_id'] : '');
                                        if ($selected_client_id != '') {
                                            $rel_data = get_relation_data('customer', $selected_client_id);
                                            $rel_val = get_relation_values($rel_data, 'customer');
                                            echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                        }
                                        $selected = (isset($opportunity->clientid) ? $opportunity->clientid : '');
                                        if ($selected != '') {
                                            $rel_data = get_relation_data('customer', $selected);
                                            $rel_val = get_relation_values($rel_data, 'customer');
                                            echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $solicitation_number = isset($opportunity->solicitation_number) ? $opportunity->solicitation_number : '';
                                echo render_input('solicitation_number', 'solicitation_number', $solicitation_number, 'text', array('placeholder' => _l('solicitation_number')));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $solicitation_title = isset($opportunity->solicitation_title) ? $opportunity->solicitation_title : '';
                                echo render_input('solicitation_title', 'solicitation_title', $solicitation_title, 'text', array('placeholder' => _l('solicitation_title'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $agency = isset($opportunity->agency) ? $opportunity->agency : '';
                                echo render_input('agency', 'agency', $agency, 'text', array('placeholder' => _l('agency')));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $solicitation_due_date = isset($opportunity->solicitation_due_date) ? $opportunity->solicitation_due_date : '';
                                echo render_date_input('solicitation_due_date', 'solicitation_due_date', $solicitation_due_date, array('placeholder' => _l('solicitation_due_date_format'))); ?>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php
                                        $solicitation_url = isset($opportunity->solicitation_url) ? $opportunity->solicitation_url : '';
                                        echo render_input('solicitation_url', 'solicitation_url', $solicitation_url, 'text', array('placeholder' => _l('solicitation_url')));
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox checkbox-primary" style="position: relative;top: 25px;">
                                            <?php
                                            $solicitation_url = isset($opportunity->solicitation_url) ? $opportunity->solicitation_url : '';
                                            if (str_contains($solicitation_url, 'https://sam.gov/')) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                            ?>
                                            <input class="solicitation_checkbox" type="checkbox" name="solicitation_url_checkbox" id="solicitation_url_checkbox" <?php echo $checked ?> value="<?php echo _l('solicitation_url_checkbox'); ?>">
                                            <label for="solicitation_url_checkbox"><?php echo _l('solicitation_url_checkbox'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $solicitation_description = isset($opportunity->solicitation_description) ? $opportunity->solicitation_description : '';
                                echo render_textarea('solicitation_description', 'solicitation_description', $solicitation_description); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <?php
                                $recommendation = isset($opportunity->recommendation) ? $opportunity->recommendation : '';
                                echo render_input('recommendation', 'recommendation', $recommendation, 'text');
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $recommendation_select = isset($opportunity->recommendation_select) ? $opportunity->recommendation_select : 'yes';
                                echo render_select('recommendation_select', array(
                                    array(
                                        'value' => 'yes',
                                        'name'  => 'Yes',
                                    ),
                                    array(
                                        'value' =>  'maybe',
                                        'name'  =>  'Maybe',
                                    ),
                                    array(
                                        'value' => 'no',
                                        'name'  => 'No',
                                    )
                                ), array('value', 'name'), _l('recommendation_select_type'), $recommendation_select);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <?php
                                $additional_notes = isset($opportunity->additional_notes) ? $opportunity->additional_notes : '';
                                echo render_textarea('additional_notes', 'additional_notes', $additional_notes); ?>
                            </div>
                            <div class="col-md-3" style="margin-top: 35px;">
                                <?php $ability_to_respond_to_solicitation = isset($opportunity->ability_to_respond_to_solicitation) ? $opportunity->ability_to_respond_to_solicitation : '0' ?>
                                <label for="ability_to_respond_to_solicitation" class="form-label"><?php echo _l('ability_to_respond_to_solicitation') ?></label>
                                <input type="range" id="ability_to_respond_to_solicitation" name="ability_to_respond_to_solicitation" min="0" max="100" value="<?php echo $ability_to_respond_to_solicitation ?>" />
                                <label class="range" for="range"><?php echo isset($ability_to_respond_to_solicitation) ? $ability_to_respond_to_solicitation : '0'; ?><span>%</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $opportunity_status_id = isset($opportunity->opportunity_status_id) ? $opportunity->opportunity_status_id : '1';
                                echo render_select('opportunity_status_id', array(
                                    array(
                                        'value' =>  '1',
                                        'name'  =>  'Draft'
                                    ),
                                    array(
                                        'value' =>  '2',
                                        'name'  =>  'In Review'
                                    ),
                                    array(
                                        'value' =>  '3',
                                        'name'  =>  'Bid'
                                    ),
                                    array(
                                        'value' =>  '4',
                                        'name'  =>  'No Bid'
                                    ),
                                    array(
                                        'value' =>  '5',
                                        'name'  =>  'Cancelled'
                                    ),
                                    array(
                                        'value' =>  '6',
                                        'name'  =>  'Archived'
                                    ),
                                ), array('value', 'name'), _l('set_status'), $opportunity_status_id);
                                ?>
                            </div>
                        </div>
                        <button id="sm_btn" type="submit" class="btn btn-info"><?php echo htmlspecialchars(_l('submit')); ?></button>
                        <a href="<?php echo site_url('opportunity/index/'); ?>" class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></a>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    appValidateForm($('#opportunity-form'), {
        clientid: 'required',
        solicitation_number: 'required',
        solicitation_title: 'required',
        agency: 'required',
        solicitation_due_date: 'required',
        solicitation_url: 'required',
    });

    document.querySelector("#ability_to_respond_to_solicitation").addEventListener("change", function(e) {
        document.querySelector(".range").textContent = e.currentTarget.value + '%';
    })

    $("input:checkbox.solicitation_checkbox").click(function() {
        if ($(this).is(":checked")) {
            $("#solicitation_url").val('https://sam.gov/');
        } else {
            $("#solicitation_url").val('');
        }
    });
</script>