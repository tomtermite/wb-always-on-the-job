<!-- / The Context Menu -->
<nav id="context-menu" class="context-menu" data-share="false">
	<ul class="context-menu__items">
		<li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="edit"><i class="fa fa-cog"></i> <?php echo _l('edit') ?></a>
		</li>
		<li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="delete"><i class="fa fa-trash"></i> <?php echo _l('delete') ?></a>
		</li>
		<li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="share"><i class="fa fa-share"></i> <?php echo _l('share') ?></a>
		</li>
		<!-- <li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="related"><i class="fa fa-user"></i> <?php echo _l('related') ?></a>
		</li> -->
		<li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="d_file"><i class="fa fa-download"></i> <?php echo _l('download') ?></a>
		</li>
		<li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="create_file"><i class="fa fa-plus"></i> <?php echo _l('create_file') ?></a>
		</li>
		<li class="context-menu__item">
			<a href="#" class="context-menu__link" data-action="create_folder"><i class="fa fa-plus"></i> <?php echo _l('create_folder') ?></a>
		</li>
	</ul>
</nav>

<div class="popup-overlay">
	<div class="popup-content">
		<header role="banner">
			<nav class="nav-class" role="navigation">
				<ul class="nav__list button-group__mono-colors" data-share="false">
					<li class="select-option-choose" data-option="edit">
						<input id="group-1" type="checkbox" hidden />
						<label for="group-1"><span class="fa fa-angle-right"></span><i class="fa fa-crosshairs"></i> <?php echo _l('edit') ?></label>
					</li>
					<li class="select-option-choose" data-option="delete">
						<input id="group-2" type="checkbox" hidden />
						<label for="group-2"><span class="fa fa-angle-right"></span><i class="fa fa-trash-o"></i> <?php echo _l('delete') ?></label>
					</li>
					<li class="select-option-choose" data-option="share">
						<input id="group-3" type="checkbox" hidden />
						<label for="group-3"><span class="fa fa-angle-right"></span><i class="fa fa-user-plus" aria-hidden="true"></i>
							<?php echo _l('share') ?></label>
					</li>
					<!-- <li class="select-option-choose" data-option="related">
						<input id="group-4" type="checkbox" hidden />
						<label for="group-4"><span class="fa fa-angle-right"></span> <i class="fa fa-user" aria-hidden="true"></i>
							<?php echo _l('related') ?></label>
					</li> -->
					<li class="select-option-choose" data-option="d_file">
						<input id="group-5" type="checkbox" hidden />
						<label for="group-5"><span class="fa fa-angle-right"></span><i class="fa fa-download" aria-hidden="true"></i> <?php echo _l('download') ?></label>
					</li>
					<li class="select-option-choose" data-option="create_file">
						<input id="group-6" type="checkbox" hidden />
						<label for="group-6"><span class="fa fa-angle-right"></span><i class="fa fa-plus" aria-hidden="true"></i> <?php echo _l('create_file') ?></label>
					</li>
					<li class="select-option-choose" data-option="create_folder">
						<input id="group-7" type="checkbox" hidden />
						<label for="group-7"><span class="fa fa-angle-right"></span><i class="fa fa-plus" aria-hidden="true"></i> <?php echo _l('create_folder') ?></label>
					</li>
				</ul>

			</nav>
		</header>
	</div>
</div>

<div class="modal fade" id="RelatedModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title add-new"><?php echo _l('add_new_related') ?></h4>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="relateDetailModal" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title add-new"><?php echo _l('related_detail') ?></h4>
			</div>
			<div class="modal-body">
				<h4>List name: </h4>
				<ul class="content-related"></ul>
			</div>
		</div>
	</div>
</div>

<a href="#" data-toggle="modal" class="btn add_file_button btn-info">
	<i class="fa fa-plus-circle"></i> <?php echo _l('add_file'); ?>
</a>

<a href="#AddFolderModal" data-toggle="modal" class="btn add_folder_button btn-info">
	<i class="fa fa-plus-square-o"></i> <?php echo _l('add_folder'); ?>
</a>

<a href="#ShareModal" data-toggle="modal" class="btn add_share_button btn-info">
	<i class="fa fa-share-square-o"></i> <?php echo _l('share'); ?>
</a>

<!-- <a href="#RelatedModal" data-toggle="modal" class="btn add_related_button btn-info">
	<i class="fa fa-paw"></i> <?php echo _l('related'); ?>
</a> -->

<div class="row">
	<div class="col-sm-12">
		<table id="document-advanced">
			<caption>
				<a href="#" class="btn btn-info caption-a" onclick="jQuery('#document-advanced').treetable('expandAll'); return false;"><span class="expand-all"></span><?php echo _l('expand_all') ?></a>
				<a href="#" class="btn btn-info caption-a" onclick="jQuery('#document-advanced').treetable('collapseAll'); return false;"><span class="collapse-all"></span><?php echo _l('collapse_all') ?></a>
			</caption>
			<thead>
				<tr>
					<th><?php echo _l('name') ?></th>
					<th><?php echo _l('kind') ?></th>
					<th><?php echo _l('owner') ?></th>
					<th><?php echo _l('share_with_staff') ?></th>
					<th><?php echo _l('share_with_client') ?></th>
				</tr>
			</thead>
			<tbody id="my_folder_tbody">
				<?php //echo html_entity_decode($folder_my_tree); ?>
			<tbody>
		</table>
	</div>
</div>



<div id="fsModal" class="modal animated bounceIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">
					close
				</button>
				<button class="btn btn-default">
					Default
				</button>
				<button class="btn btn-primary">
					Primary
				</button>
			</div>
		</div>
	</div>
</div>






<?php 
$this->load->view("models/add_new_folder_model");
$this->load->view("models/add_new_file_model");
$this->load->view("models/add_document_share_model");
$this->load->view("models/share_details_model");
?>


