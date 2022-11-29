<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php echo form_hidden('opportunity_id', isset($opportunity_id) ? $opportunity_id : ''); ?>
                <div class="panel_s mbot10">
                    <div class="panel-body">
                        <div class="_buttons">
                            <div class="display-block text-right">
                                <?php
                                if (has_permission('opportunity', '', 'create') || is_admin()) {
                                ?>
                                    <a href="<?php echo admin_url('opportunity/add'); ?>" class="btn btn-info pull-left display-block mright5">
                                        <?php echo _l('new_opportunity'); ?>
                                    </a>
                                <?php
                                }
                                ?>
                                <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-filter" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right width300">
                                        <li class="<?php if ($opportunity_status_id = '') {
                                                        echo '';
                                                    } else {
                                                        echo 'active';
                                                    } ?> all_opportunity">
                                            <a id="all_opportunity" href="#" data-cview="all" onclick="dt_custom_view('','.table-opportunity','opportunity_status_id', true); return false;">
                                                <?php echo _l('all'); ?>
                                            </a>
                                        </li>

                                        <li class="divider"></li>

                                        <?php
                                        if (isset($statuses)) {
                                            foreach ($statuses as $status) {
                                        ?>
                                                <li class="opportunity_status_li">
                                                    <a href="#" data-cview="opportunity_status_<?php echo $status['id']; ?>" onclick="dt_custom_view('opportunity_status_<?php echo $status['id']; ?>','.table-opportunity','opportunity_status_<?php echo $status['id']; ?>'); return false;">
                                                        <?php echo opportunity_status_translate($status['id']); ?>
                                                    </a>
                                                </li>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                        </div>

                        <hr class="hr-panel-heading" />

                        <?php $this->load->view('includes/opportunity_summary'); ?>

                        <hr class="hr-panel-heading" />
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php
                        $table_data = [];

                        $table_data = [
                            _l('id'),
                            _l('solicitation_title'),
                            _l('solicitation_number'),
                            [
                                'name'     => _l('solicitation_customer'),
                                'th_attrs' => ['class' => isset($client) ? 'not_visible' : ''],
                            ],
                            _l('agency'),
                            _l('solicitation_due_date'),
                            _l('solicitation_url'),
                            _l('status'),
                        ];

                        render_datatable($table_data, isset($class) ?  $class : 'opportunity', [], [
                            'data-last-order-identifier' => 'opportunity',
                            'data-default-order'  => get_table_last_order('opportunity'),
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-7 small-table-right-col">
                <div id="opportunity_report" class="hide">
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(function() {
        var ProjectsServerParams = {};

        $.each($('._hidden_inputs._filters input'), function() {
            ProjectsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });

        initDataTable('.table-opportunity', admin_url + 'opportunity/table', undefined, undefined, ProjectsServerParams);
    });
</script>

<script>
    var hidden_columns = [0, 5, 6];
    $(function() {
        initDataTable('.table-opportunity', admin_url + 'opportunity/table');
    });

    function init_opportunity_report(id) {
        load_small_table_item_opportunity(id, '#opportunity_report', 'opportunity_id', 'opportunity/get_opportunity_data_ajax', '.table-opportunity');
    }

    function load_small_table_item_opportunity(pr_id, selector, input_name, url, table) {
        var _tmpID = $('input[name="' + input_name + '"]').val();
        // Check if id passed from url, hash is prioritized becuase is last
        if (_tmpID !== '' && !window.location.hash) {
            pr_id = _tmpID;
            // Clear the current id value in case user click on the left sidebar credit_note_ids
            $('input[name="' + input_name + '"]').val('');
        } else {
            // check first if hash exists and not id is passed, becuase id is prioritized
            if (window.location.hash && !pr_id) {
                pr_id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
            }
        }
        if (typeof(pr_id) == 'undefined' || pr_id === '') {
            return;
        }
        if (!$("body").hasClass('small-table')) {
            toggle_small_view_opportunity(table, selector);
        }
        $('input[name="' + input_name + '"]').val(pr_id);
        do_hash_helper(pr_id);
        $(selector).load(admin_url + url + '/' + pr_id);
        if (is_mobile()) {
            $('html, body').animate({
                scrollTop: $(selector).offset().top + 150
            }, 600);
        }
    }

    function toggle_small_view_opportunity(table, main_data) {

        $("body").toggleClass('small-table');
        var tablewrap = $('#small-table');
        if (tablewrap.length === 0) {
            return;
        }
        var _visible = false;
        if (tablewrap.hasClass('col-md-5')) {
            tablewrap.removeClass('col-md-5').addClass('col-md-12');
            _visible = true;
            $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
        } else {
            tablewrap.addClass('col-md-5').removeClass('col-md-12');
            $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
        }
        var _table = $(table).DataTable();

        // Show hide hidden columns
        _table.columns(hidden_columns).visible(_visible, false);
        _table.columns.adjust();
        $(main_data).toggleClass('hide');
        $(window).trigger('resize');
    }

    $(".all_opportunity").on('click', function() {
        document.getElementById("all_opportunity").style.color = "white";
        document.getElementById("all_opportunity").style.backgroundColor = "#03a9f4";
    });

    $(".opportunity_status_li").on('click', function() {
        document.getElementById("all_opportunity").style.color = "";
        document.getElementById("all_opportunity").style.backgroundColor = "";
    });
</script>