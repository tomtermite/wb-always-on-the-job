<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    .generated_by {
        margin-top: 30px;
    }

    .report_view_row {
        margin-left: 80px;
        margin-right: 100px;
    }

    .preview-sticky-header {
        z-index: 1;
        background: #fff;
        padding-top: 15px;
        -webkit-box-shadow: 0px 1px 15px 1px rgba(90, 90, 90, 0.08);
        box-shadow: 0px 1px 15px 1px rgba(90, 90, 90, 0.08);
        width: 100% !important;
        left: 0px !important;
    }

    .preview-sticky-header .sticky-hidden {
        display: none !important;
    }

    .preview-sticky-header .sticky-visible {
        display: inherit !important;
    }

    .mobile .preview-sticky-header {
        padding: 15px;
    }

    @media(max-width:767px) {

        .preview-sticky-container .action-button,
        .preview-sticky-container .content-view-status {
            float: none !important;
            display: inline-block;
            width: 100%;
            margin: 0 0 5px 0;
        }
    }

    @media(min-width:767px) {
        div:not(.preview-sticky-header) .preview-sticky-container {
            padding-left: 0px;
            padding-right: 0px;
        }
    }
</style>

<div class="mtop15 preview-top-wrapper" style="margin-bottom: 10px;">
    <div class="top" data-sticky data-sticky-class="preview-sticky-header">
        <div class="container preview-sticky-container">
            <div class="row">
                <div class="col-md-12" style="margin-top: 10px;margin-bottom: 10px;">
                    <div class="pull-left">
                        <div class="invoice-html-logo">
                            <?php echo get_dark_company_logo(); ?>
                        </div>
                    </div>
                    <div class="visible-xs">
                        <div class="clearfix"></div>
                    </div>
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <a href="<?php echo admin_url('opportunity/pdf/' . $opportunity->id); ?>" name="invoicepdf" value="invoicepdf" class="btn btn-default pull-right action-button mtop5">
                        <i class='fa fa-file-pdf-o'></i> <?php echo _l('clients_invoice_html_btn_download'); ?>
                    </a>
                    <?php echo form_close(); ?>
                    <?php if (is_client_logged_in() && has_contact_permission('opportunity')) { ?>
                        <a href="<?php echo site_url('clients/index/'); ?>" class="btn btn-default pull-right mtop5 mright5 action-button go-to-portal">
                            <?php echo _l('client_go_to_dashboard'); ?>
                        </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>

<?php $this->load->view('includes/opportunity_report') ?>

<script>
    $(function() {
        new Sticky('[data-sticky]');
    });
</script>