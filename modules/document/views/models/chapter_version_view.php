<div class="modal fade" id="viewVersionDescriptionModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close test_class" data-dismiss="modal">&times;</button>
				<h4 class="modal-title add-new"><?php echo _l('add_new_share') ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<textarea id="description" name="description"><?php echo $contents; ?></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default test_class" data-dismiss="modal"><?php echo _l('close'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>