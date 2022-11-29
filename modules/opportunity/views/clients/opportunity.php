<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <div class="_buttons">
                    <a href="<?php echo admin_url('opportunity/add?client_id=' . $client->userid); ?>" class="btn btn-info pull-left display-block mright5">
                        <?php echo _l('new_opportunity'); ?>
                    </a>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="_filters _hidden_inputs">
                        <?php
                        echo form_hidden('clientid', $client->userid);
                        echo form_hidden('client_flag', '1');
                        ?>
                    </div>
                </div>
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
                ];

                $table_data = hooks()->apply_filters('opportunity_table_columns', $table_data);

                render_datatable($table_data, isset($class) ?  $class : 'opportunity', [], [
                    'data-last-order-identifier' => 'opportunity',
                    'data-default-order'  => get_table_last_order('opportunity'),
                ]);
                ?>

            </div>
        </div>
    </div>

    <?php hooks()->add_action('app_admin_footer', 'parse_customer_statement_html');
    function parse_customer_statement_html()
    { ?>
        <script>
            $(function() {
                var ProjectsServerParams = {};

                $.each($('._hidden_inputs._filters input'), function() {
                    ProjectsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
                });

                initDataTable('.table-opportunity', admin_url + 'opportunity/table', undefined, undefined, ProjectsServerParams);

            });
        </script>
    <?php } ?>