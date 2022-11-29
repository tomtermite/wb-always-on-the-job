<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">

    <?php
    $opportunity_statuses = $this->opportunity_model->get_opportunity_status();
    ?>

    <div class="_filters _hidden_inputs hidden opportunity_filters">
    <?php echo form_hidden('opportunity_status_id',''); ?>
        <?php
        if (isset($opportunity_statuses)) {
            foreach ($opportunity_statuses as $opportunity_status) {
                echo form_hidden('opportunity_status_' . $opportunity_status['id'], );
            }
        }
        ?>
    </div>

    <div class="col-md-12">
        <h4 class="no-margin"><?php echo _l('opportunity_summary'); ?></h4>
    </div>
    <?php
    if (isset($opportunity_statuses)) {
        foreach ($opportunity_statuses as $opportunity_status) {
            $_where = '';
            $where = '';
            if ($where == '') {
                $_where = 'opportunity_status_id=' . $opportunity_status['id'];
            } else {
                $_where = 'opportunity_status_id=' . $opportunity_status['id'] . ' ' . $where;
            }
    ?>
            <div class="col-md-2 col-xs-6 mbot15 border-right">
                <a href="#" data-cview="opportunity_status_<?php echo $opportunity_status['id']; ?>" onclick="dt_custom_view('<?php echo $opportunity_status['id'] ?>','.table-opportunity','opportunity_status_id'); return false;">

                    <h3 class="bold"><?php echo $data = total_rows(db_prefix() . 'opportunity', $_where); ?></h3>
                    <span>
                        <?php echo opportunity_status_translate($opportunity_status['id']) ?>
                    </span>
                </a>
            </div>
    <?php
        }
    }
    ?>
</div>
