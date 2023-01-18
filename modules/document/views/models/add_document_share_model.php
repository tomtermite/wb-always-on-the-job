<div class="modal fade" id="ShareModal" role="dialog">
	<?php echo form_hidden('value-hidden'); ?>

	<?php echo form_open_multipart(admin_url('document/update_share_document_online'), array('id' => 'share-form')) ?>
	<?php echo form_hidden('id'); ?>
	<?php echo form_hidden('update', "false"); ?>
	<?php echo form_hidden('parent_id');  ?>

	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close test_class" data-dismiss="modal">&times;</button>
				<h4 class="modal-title add-new"><?php echo _l('add_new_share') ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="col-md-3">
							<h5><span><?php echo _l('what_do_you_want_to_choose') ?></span></h5>
						</div>
						<div class="pull-left">
							<div class="wrapper">
								<input id="checkbox1" type="checkbox" name="group_share_staff" value="1" checked />
								<label for="checkbox1"><?php echo _l('staff') ?></label>
								<input id="checkbox2" type="checkbox" name="group_share_client" value="2" checked />
								<label for="checkbox2"><?php echo _l('client') ?></label>
							</div>
						</div>
					</div>
				</div>

				<div class="row share-row">
					<div class="col-md-12 choosee-staff">
						<div class="col-md-12">
							<strong><?php echo _l('staff') ?> </strong>
						</div>
						<div class="list_information_fields_review">
							<?php if (!isset($medical_visit->review_result)) { ?>
								<div id="item_information_fields_review">
									<div class="col-md-11 content-share">
										<div class="col-md-4">
											<?php $selected = ''; ?>
											<?php echo render_select('departments_share[0]', $departments, array('departmentid', 'name'), 'department_share', $selected, array()); ?>
										</div>
										<div class="col-md-4">
											<?php
											$selected = '';
											echo render_select('staffs_share[0]', $staffs, array('staffid', array('firstname', 'lastname')), 'staff_share', $selected, array());
											?>
										</div>
										<div class="col-md-4">
											<?php $permission = [['id' => 1, 'name' => _l('view')], ['id' => 2, 'name' => _l('edit')]] ?>
											<?php echo render_select('role_staff[0]', $permission, array('id', 'name'), 'permission', 1, [], [], '', '', false); ?>
										</div>
									</div>

									<div class="col-md-1">
										<span class="pull-bot">
											<button name="add" class="new-btn-clone btn new_box_information_review btn-info" data-ticket="true" type="button">
												<i class="fa fa-plus"></i>
											</button>
										</span>
									</div>
								</div>
							<?php } else { ?>
								<?php foreach ($medical_visit->review_result as $key => $review_result) { ?>
									<div id="item_information_fields_review">

										<div class="col-md-11 content-share">
											<div class="col-md-4">
												<?php $selected = ''; ?>
												<?php echo render_select('departments_share[$key]', $departments, array('departmentid', 'name'), 'department_share', $selected, array()); ?>
											</div>
											<div class="col-md-4">
												<?php
												$selected = $review_result['exam_result'];
												echo render_select('staffs_share[$key]', $staffs, array('staffid', array('firstname', 'lastname')), 'staff_share', $selected, array());
												?>
											</div>
											<div class="col-md-4">
												<?php $permission = [['id' => 1, 'name' => _l('view')], ['id' => 2, 'name' => _l('edit')]] ?>
												<?php echo render_select('role_staff[$key]', $permission, array('id', 'name'), 'permission', $review_result['exam_result'] != '' ? $review_result['exam_result'] : '', [], [], '', '', false); ?>
											</div>
										</div>

										<div class="col-md-1">
											<span class="pull-bot">
												<button name="add" class="new-btn-clone btn <?php if ($key == 0) {
																								echo 'new_box_information_review btn-info';
																							} else {
																								echo 'remove_box_information_review btn-danger';
																							} ?>" data-ticket="true" type="button"><i class="fa <?php if ($key == 0) {
																																																																			echo 'fa-plus';
																																																																		} else {
																																																																			echo 'fa-minus';
																																																																		} ?>"></i>
												</button>
											</span>
										</div>
									</div>
							<?php }
							} ?>
						</div>
					</div>

					<div class="col-md-12 choosee-customer">
						<div class="col-md-12">
						<strong><?php echo _l('client') ?> </strong>
						</div>	
						<div class="list_information_fields_review_client">
							<?php if (!isset($medical_visit->review_result)) { ?>
								<div id="item_information_fields_review_client">
									<div class="col-md-11 content-share">
										<div class="col-md-4">
											<?php echo render_select('client_groups_share[0]', $client_groups, array('id', 'name'), 'client_groups_share', '', array()); ?>
										</div>
										<div class="col-md-4">
											<?php
											$selected = '';
											echo render_select('clients_share[0]', $clients, array('userid', array('company')), 'client_share', $selected, array());
											?>
										</div>

										<div class="col-md-4">
											<?php echo render_select('role_client[0]', $permission, array('id', 'name'), 'permission', 1, array(), array(), '', '', '', false); ?>
										</div>

									</div>

									<div class="col-md-1">
										<span class="pull-bot">
											<button name="add" class="new-btn-clone btn new_box_information_review_client btn-info" data-ticket="true" type="button">
												<i class="fa fa-plus"></i>
											</button>
										</span>
									</div>
								</div>
							<?php } else { ?>
								<?php foreach ($medical_visit->review_result as $key => $review_result) { ?>
									<div id="item_information_fields_review">

										<div class="col-md-11 content-share">

											<div class="col-md-4">
												<?php echo render_select('client_groups_share[$key]', $client_groups, array('id', 'name'), 'client_groups_share', '', array()); ?>
											</div>
											<div class="col-md-4">
												<?php
												$selected = $review_result['exam_result'];
												echo render_select('clients_share[$key]', $clients, array('userid', array('company')), 'client_share', $selected, array());
												?>
											</div>

											<div class="col-md-4">
												<?php echo render_select('role_client[$key]', $permission, array('id', 'name'), 'permission', $review_result['exam_result'] != '' ? $review_result['exam_result'] : '', array(), array(), '', '', '', false); ?>
											</div>
										</div>

										<div class="col-md-1">
											<span class="pull-bot">
												<button name="add" class="new-btn-clone btn <?php if ($key == 0) {
																								echo 'new_box_information_review_client btn-info';
																							} else {
																								echo 'remove_box_information_review_client btn-danger';
																							} ?>" data-ticket="true" type="button"><i class="fa <?php if ($key == 0) {
																																																																						echo 'fa-plus';
																																																																					} else {
																																																																						echo 'fa-minus';
																																																																					} ?>"></i>
												</button>
											</span>
										</div>
									</div>
							<?php }
							} ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default test_class" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>