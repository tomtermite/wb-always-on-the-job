<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s section-heading">
   <div class="panel-body">
      <h4 class="no-margin section-text"></h4>
   </div>
</div>
<div class="panel_s">
   <div class="panel-body">
      <div class="top_button" style="text-align: end;">
         <a href="<?php echo site_url('document/document_client'); ?>" class="btn  btn-danger">
            <i class="fa fa-times"></i> <?php echo _l('close'); ?>
         </a>
      </div>

      <hr />
      <table id="table-client-chapter" class="table table-client-chapter">
         <thead>
            <tr>
               <th class="th-id"><?php echo _l('chapter_number'); ?></th>
               <th class="th-chapter_title"><?php echo _l('chapter_title'); ?></th>
               <th class="th-number_of_words"><?php echo _l('number_of_words'); ?></th>
               <th class="th-latest_version"><?php echo _l('latest_version'); ?></th>
               <th class="th-date_last_edited"><?php echo _l('date_last_edited'); ?></th>
               <th class="th-action"><?php echo _l('action'); ?></th>
            </tr>
         </thead>
         <tbody></tbody>
      </table>
   </div>

</div>


<script>
   $(document).ready(function() {
      var table_news = $('#table-client-chapter').DataTable({
         'processing': true,
         'serverSide': true,
         'ajax': {
            'type': "POST",
            'url': site_url + 'document/document_client/table',
            "data": function(d) {
               d.parent_id = <?php echo $parent_id ?>;
            }
         },
         "columnDefs": [{
            "targets": [0],
            "orderable": false,
         }, ],
         'columns': [{
               data: 'id'
            },
            {
               data: 'name'
            },
            {
               data: 'number_of_words'
            },
            {
               data: 'latest_version'
            },
            {
               data: 'updated_at'
            },
            {
               data: 'action'
            }
         ]
      });
   });
</script>