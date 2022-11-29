<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="_buttons">
    <?php
    if (has_permission('opportunity', '', 'create') || is_admin()) {
    ?>
        <a class="btn btn-info pull-left display-block mright5" onclick="new_requirement()">
            <?php echo _l('new_requirement'); ?>
        </a>
    <?php
    }
    ?>
    <div class="clearfix"></div>
    <hr class="hr-panel-heading" />
</div>
<?php
$table_data = [];

$table_data = [
    _l('id'),
    _l('requirement_heading'),
    _l('risks'),
    _l('risks_priority'),
    _l('impacts'),
    _l('impacts_priority'),
    _l('action_items'),
    _l('gap_yes_no'),
];

render_datatable($table_data, isset($class) ?  $class : 'requirement', [], [
    'data-last-order-identifier' => 'requirement',
    'data-default-order'  => get_table_last_order('requirement'),
]);

?>

<!-- Requirement Modal -->

<div class="modal fade" id="requirement_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open_multipart(admin_url('opportunity/requirement_insert'), ['id' => 'requirement-form']); ?>
        <?php echo form_hidden('id', '') ?>
        <?php echo form_hidden('opportunity_id', $id) ?>
        <div class="modal-content modalwidth">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo htmlspecialchars(_l('new_requirement')); ?></span>
                    <span class="edit-title"><?php echo htmlspecialchars(_l('edit_requirement')); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><?php echo htmlspecialchars(_l('requirement_information')); ?></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo render_input('requirement', 'requirement', '', 'text', array('placeholder' => _l('requirement'))) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php echo render_input('risks', 'risks', '', 'text', array('placeholder' => _l('risks'))) ?>
                                    </div>
                                    <div class="col-md-4 risks_priority" style="position: relative;top: 24px;">
                                        <?php
                                        echo render_select('risks_priority', array(
                                            array(
                                                'value' => 'high',
                                                'name'  => 'High',
                                            ),
                                            array(
                                                'value' =>  'medium',
                                                'name'  =>  'Medium',
                                            ),
                                            array(
                                                'value' => 'low',
                                                'name'  => 'Low',
                                            )
                                        ), array('value', 'name'), '', 'high');
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <?php echo render_input('impacts', 'impacts', '', 'text', array('placeholder' => _l('impacts'))) ?>
                                    </div>
                                    <div class="col-md-4" style="position: relative;top: 24px;">
                                        <?php
                                        echo render_select('impacts_priority', array(
                                            array(
                                                'value' => 'high',
                                                'name'  => 'High',
                                            ),
                                            array(
                                                'value' =>  'medium',
                                                'name'  =>  'Medium',
                                            ),
                                            array(
                                                'value' => 'low',
                                                'name'  => 'Low',
                                            )
                                        ), array('value', 'name'), '', 'high');
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo render_input('action_items', 'action_items', '', 'text', array('placeholder' => _l('action_items'))) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php render_yes_no_option('gap_yes_no', _l('gap_yes_no')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="sm_btn" type="submit" class="btn btn-info"><?php echo htmlspecialchars(_l('submit')); ?></button>
                <button type="" class="btn btn-default" data-dismiss="modal"><?php echo htmlspecialchars(_l('close')); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- /.Requirement Modal -->