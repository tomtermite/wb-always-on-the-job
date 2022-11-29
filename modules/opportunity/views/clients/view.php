<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s section-heading section-opportunity">
   <div class="panel-body">
      <h4 class="no-margin section-text"><?php echo _l('opportunity'); ?></h4>
   </div>
</div>
<div class="panel_s">
   <div class="panel-body">
      <table class="table dt-table table-opportunity" data-order-col="2" data-order-type="desc">
         <thead>
            <tr>
               <th class="th-solicitation-title"><?php echo _l('solicitation_title'); ?></th>
               <th class="th-solicitation-number"><?php echo _l('solicitation_number'); ?></th>
               <th class="th-agency"><?php echo _l('agency'); ?></th>
               <th class="th-solicitation-due-date"><?php echo _l('solicitation_due_date'); ?></th>
               <th class="th-solicitation-url"><?php echo _l('solicitation_url'); ?></th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($opportunities as $opportunity) { ?>
               <tr>
                  <td data-order="<?php echo $opportunity['solicitation_title']; ?>"><a href="<?php echo admin_url('opportunity/client/report/' . $opportunity['id']) ?>"><?php echo _l($opportunity['solicitation_title']); ?></td></a>
                  <td data-order="<?php echo $opportunity['solicitation_number']; ?>"><?php echo _l($opportunity['solicitation_number']); ?></td>
                  <td data-order="<?php echo $opportunity['agency']; ?>"><?php echo _l($opportunity['agency']); ?></td>
                  <td data-order="<?php echo $opportunity['solicitation_due_date']; ?>"><?php echo _l($opportunity['solicitation_due_date']); ?></td>
                  <td data-order="<?php echo $opportunity['solicitation_url']; ?>"><a href="//<?php echo _l($opportunity['solicitation_url']) ?>"><?php echo _l($opportunity['solicitation_url']); ?></a></td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>