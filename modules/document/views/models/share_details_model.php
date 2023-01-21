<div class="modal fade" id="sharedetailModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title add-new"><?php echo _l('share_detail') ?></h4>
			</div>
			<div class="modal-body">
				<?php echo form_hidden('data_share'); ?>
				<div class="row">
					<ul class="tabs">
						<li class="tab-link current" data-tab="tab-1"><?php echo _l('staff') ?></li>
						<li class="tab-link" data-tab="tab-2"><?php echo _l('clients') ?></li>
					</ul>

					<div id="tab-1" class="tab-content current">
						<table class="content-table">
							<thead>
								<tr>
									<th>NAME</th>
									<th>PERMISSION</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div id="tab-2" class="tab-content">

						<table class="content-table">
							<thead>
								<tr>
									<th>NAME</th>
									<th>PERMISSION</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>