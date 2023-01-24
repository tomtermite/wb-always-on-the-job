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
				<a href="<?php echo admin_url('document/new_file_view/' . $parent_id); ?>" class="btn  btn-danger">
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
							_l('chapter_version_number'),
							_l('chapter_version_change_role'),
							_l('chapter_version_change_by'),
							_l('chapter_version_description'),
							_l('chapter_version_last_modify'),
						];

						render_datatable($table_data, isset($class) ?  $class : 'chapter_version', [], [
							'data-last-order-identifier' => 'chapter_version',
							'data-default-order'  => get_table_last_order('chapter_version'),
						]);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
    echo $this->load->view('models/chapter_version_view',$data, true);
?>

<?php init_tail(); ?>
<script>
	$(document).ready(function(){
		var editor = init_editor('#description', {
				append_plugins : 'wordcount',
				readonly : 1
		});
	});

	$(function() {
		var chapterVersionParams = {};
		chapterVersionParams['chapter_id'] = '<?php echo $chapter_id; ?>';
		_table_api = initDataTable('.table-chapter_version', admin_url + 'document/chapter_versions_table', undefined, [3], chapterVersionParams);
	});

	function open_description(id){
		$.ajax({
			type : 'GET',
			url : admin_url+'document/get_chapter_version/'+id,
			data : {},
			success: function(result){
				var data = JSON.parse(result);
				if(data.status == 1){
					tinymce.remove("#description");
					var editor = init_editor('#description', {
							append_plugins : 'wordcount',
							readonly : 1
					});
					tinymce.activeEditor.setContent(data.chapter_version_details.description);
					$("#viewVersionDescriptionModal").modal("show");
				}
			}
		})
	}
</script>