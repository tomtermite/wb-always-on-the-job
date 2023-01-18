<div class="modal fade" id="AddFileModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title add-new"><?php echo _l('add_new_file') ?></h4>
				<h4 class="modal-title update-new hide"><?php echo _l('update_file') ?></h4>
			</div>
			<?php echo form_open_multipart(admin_url('document/add_edit_file'), array('id' => 'add-edit-file-form', 'onsubmit' => "return false;")); ?>
			<?php echo form_hidden('id'); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<?php echo render_input('name', 'name_file'); ?>
					</div>
				</div>
				<?php echo form_hidden('parent_id'); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>