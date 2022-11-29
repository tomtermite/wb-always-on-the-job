<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <?php echo form_hidden('opportunity_id', $opportunity->id) ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s project-top-panel panel-full">
                    <div class="panel-body _buttons">
                        <div class="row">
                            <div class="col-md-7 project-heading">
                                <h4 class="project-name"><?php echo $opportunity->solicitation_title; ?> - <small><?php echo get_company_name($opportunity->clientid) ?></small></h4>
                                <div class="visible-xs">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel_s project-menu-panel">
                    <div class="panel-body">
                        <?php
                        $data['id'] = $opportunity->id;
                        $this->load->view('includes/opportunity_tabs', $data);
                        ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_filters _hidden_inputs">
                            <?php echo form_hidden('opportunity_id', $data['id']); ?>
                        </div>
                        <?php
                        $opportunity_tabs_name =  isset($group) ? $group : 'opportunity_overview';
                        $this->load->view('includes/' . $opportunity_tabs_name);
                        ?>
                        <hr class="hr-panel-heading opportunity-area-separation" />
                        <a href="<?php echo site_url('opportunity/index/'); ?>" class="btn btn-primary" data-dismiss="modal"><?php echo htmlspecialchars(_l('go_back')); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    appValidateForm($('#requirement-form'), {
        requirement: 'required',
        risks: 'required',
        impacts: 'required',
        risks_priority: 'required',
        impacts_priority: 'required',
        action_items: 'required',
    });

    $(function() {
        var ProjectsServerParams = {};

        $.each($('._hidden_inputs._filters input'), function() {
            ProjectsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });

        initDataTable('.table-requirement', admin_url + 'opportunity/requirement_table', undefined, undefined, ProjectsServerParams);
    });

    $('.modal').on('hidden.bs.modal', function() {
        $('#requirement-form').trigger("reset");
    })

    function new_requirement() {
        $("#requirement_modal").modal("show");

        $('#requirement_modal input#y_opt_1_GAP').prop('checked', true);
        
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
    }

    function edit_requirement(invoker, id) {
        $("#requirement_modal").modal("show");

        // $('#additional').append(hidden_input('id', id));
        $('#requirement_modal input[name="id"]').val(id);

        $('#requirement_modal input[name="requirement"]').val($(invoker).data('requirement'));

        $('#requirement_modal input[name="risks"]').val($(invoker).data('risks'));

        $('#requirement_modal select[name="risks_priority"]').val($(invoker).data('risks_priority'));
        $('#requirement_modal select[name="risks_priority"]').change();

        $('#requirement_modal input[name="impacts"]').val($(invoker).data('impacts'));

        $('#requirement_modal select[name="impacts_priority"]').val($(invoker).data('impacts_priority'));
        $('#requirement_modal select[name="impacts_priority"]').change();

        $('#requirement_modal input[name="action_items"]').val($(invoker).data('action_items'));

        if ($(invoker).data('gap_yes_no') == 'Yes') {
            $('#requirement_modal input#y_opt_1_GAP').prop('checked', true);
        } else if ($(invoker).data('gap_yes_no') == 'No') {
            $('#requirement_modal input#y_opt_2_GAP').prop('checked', true);
        } else {
            $('#requirement_modal input#y_opt_1_GAP').prop('checked', true);
            $('#requirement_modal input#y_opt_2_GAP').prop('checked', false);
        }

        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');
    }
</script>