<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="horizontal-scrollable-tabs">
    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs no-margin project-tabs nav-tabs-horizontal" role="tablist">
            <li class="opportunity_tab_opportunity_overview active">
                <a data-group="opportunity_overview" role="tab" href="<?php echo admin_url('opportunity/view/' . $id . '?group=opportunity_overview') ?>">
                    <i class="fa fa-th" aria-hidden="true"></i>
                    <?php echo _l('overview') ?>
                </a>
            </li>
            <li class="opportunity_tab_opportunity_requirement">
                <a data-group="opportunity_requirement" role="tab" href="<?php echo admin_url('opportunity/view/' . $id . '?group=opportunity_requirement') ?>">
                    <i class="fa fa-asterisk" aria-hidden="true"></i>
                    <?php echo _l('requirement_heading') ?>
                </a>
            </li>
            <li class="opportunity_tab_opportunity_report">
                <a data-group="opportunity_report" role="tab" href="<?php echo admin_url('opportunity/view/' . $id . '?group=opportunity_report') ?>">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <?php echo _l('report') ?>
                </a>
            </li>
        </ul>
    </div>
</div>