<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade email-template" data-editor-id=".<?php echo 'tinymce-' . $opportunity->id; ?>" id="invoice_send_to_client_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open('opportunity/send_to_email/' . $opportunity->id); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo _l('send_mail'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php
                            $selected = array();
                            $contacts = $this->clients_model->get_contacts($opportunity->clientid, array('active' => 1, 'invoice_emails' => 1));

                            foreach ($contacts as $contact) {
                                array_push($selected, $contact['id']);
                            }
                            if (count($selected) == 0) {
                                echo '<p class="text-danger">' . _l('sending_email_contact_permissions_warning', _l('customer_permission_invoice')) . '</p><hr />';
                            }
                            echo render_select('sent_to[]', $contacts, array('id', 'email', 'firstname,lastname'), 'invoice_estimate_sent_to_email', $selected, array('multiple' => true), array(), '', '', false);
                            ?>
                        </div>
                        <?php echo render_input('cc', 'CC'); ?>
                        <p>use commas(,) for multiple email</p>
                  
                        <hr />
                        <div class="form-group">
                        <label for="subject"><?php echo _l('subject'); ?></label>
                            <input type="text" name="subject" class="form-control" id="subject">
                        </div>
                        <hr />
                        <h5 class="bold"><?php echo _l('message'); ?></h5>
                        <hr />
                        <?php echo render_textarea('email_template_custom', '', $template->message, [], [], '', 'tinymce-' . $opportunity->id); ?>
                    
                    </div>
                </div>
           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('send'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(function() {

        init_selectpicker();
        $('#invoice_send_to_client_modal form').submit(function() {
            var $statementPeriod = $('#range');
            var value = $statementPeriod.selectpicker('val');
            var period = new Array();
            if (value != 'period') {
                period = JSON.parse(value);
            } else {
                period[0] = $('input[name="period-from"]').val();
                period[1] = $('input[name="period-to"]').val();
            }

            $(this).find('input[name="statement_from"]').val(period[0]);
            $(this).find('input[name="statement_to"]').val(period[1]);

            return true;
        })
    })
</script>