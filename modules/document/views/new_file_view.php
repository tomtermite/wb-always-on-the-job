<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="panel_s">
			<div class="panel-body">
				<div class="_filters _hidden_inputs">
					<?php echo form_hidden('parent_id', $parent_id); ?>
				</div>
				<?php if (is_admin()) { ?>
					<span class="label label-default cursor setting-sent-notifications"><i class="fa fa-cog" aria-hidden="true"></i></span>
				<?php } ?>
				</h4>
				<a href="<?php echo admin_url('document/new_chapter/' . $parent_id); ?>" class="btn add_chapter btn-info">
					<i class="fa fa-plus"></i> <?php echo _l('add_chapter'); ?>
				</a>
				<a href="<?php echo admin_url('document/manage'); ?>" class="btn  btn-danger">
					<i class="fa fa-times"></i> <?php echo _l('close'); ?>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="small-table">
				<div class="panel_s">
					<div class="panel-body">
						<?php
						$table_data = [];

						$table_data = [
							_l('chapter_number'),
							_l('chapter_title'),
							_l('number_of_words'),
							_l('latest_version'),
							_l('date_last_edited'),
							_l('date_last_edited'),
						];

						render_datatable($table_data, isset($class) ?  $class : 'document', [], [
							'data-last-order-identifier' => 'document',
							'data-default-order'  => get_table_last_order('document'),
						]);
						?>
					</div>
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
		
		_table_api = initDataTable('.table-document', admin_url + 'document/table', undefined, undefined, ProjectsServerParams,[5,'asc']);

		// $('.table-document').column(5).visible(false, false).columns.adjust();
		_table_api.column(5).visible(false, false).columns.adjust();
	});

	function delete_chapter(id) {
		$.ajax({
			type: "post",
			url: admin_url + 'document/delete_chapter/' + id,
			success: function(response) {
				response = JSON.parse(response);
				if (response.status == 1) {
					alert_float('success', response.message);
					$('.table-document').DataTable().ajax.reload();
				} else {
					alert_float('danger', response.message);
				}
			}
		});
	}


	$(function() {
		var parent_id = <?php  echo $parent_id ?>;
		$("tbody").sortable({
			cursor: "move",
			update: function(event, ui) {
				let ids = [];
				$(this).children().each(function(index) {
					ids.push($(this).find('.chapter_id').data("id"));  		
				});
				$.ajax({
						type: "post",
						url: admin_url + 'document/orderd_chapter',
						dataType: 'json',
						data: {
							parent_id:parent_id,
							ids:ids,
						},
						cache: false,
						success: function(response) {
							if(response.status == true){
								$('.table-document').DataTable().ajax.reload();
							}
							
						}
					});
			},
		});
	});
</script>