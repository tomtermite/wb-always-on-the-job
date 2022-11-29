<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12 border-right opportunity-overview-left">
        <div class="row">
            
                <div class="col-md-12">
                    <p class="opportunity-info bold font-size-14">
                        <?php echo _l('overview'); ?>
                    </p>
                    <hr class="hr-panel-heading opportunity-area-separation" />
                </div>
            
            <div class="col-md-12">
                <table class="table no-margin opportunity-overview-table">
                    <tbody>
                        <tr class="opportunity-overview-id">
                            <td class="bold"><?php echo _l('solicitation'); ?> <?php echo _l('the_number_sign'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->id : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-solicitation_title">
                            <td class="bold"><?php echo _l('solicitation_title'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->solicitation_title : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-solicitation_number">
                            <td class="bold"><?php echo _l('solicitation_number'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->solicitation_number : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-customer">
                            <td class="bold"><?php echo _l('solicitation_customer'); ?></td>
                            <td>
                                
                                <a href="<?php echo admin_url(); ?>clients/client/<?php echo isset($opportunity) ? $opportunity->clientid : ''; ?>">
                                    <?php echo isset($opportunity) ? get_company_name($opportunity->clientid) : ''; ?>
                                </a>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-agency">
                            <td class="bold"><?php echo _l('agency'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->agency : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-solicitation_due_date">
                            <td class="bold"><?php echo _l('solicitation_due_date'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->solicitation_due_date : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-solicitation_url">
                            <td class="bold"><?php echo _l('solicitation_url'); ?></td>
                            <td>
                                <a href="//<?php echo isset($opportunity) ? $opportunity->solicitation_url : ''; ?>" target="_blank">
                                    <?php echo isset($opportunity) ? $opportunity->solicitation_url : ''; ?>
                                </a>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-solicitation_description">
                            <td class="bold"><?php echo _l('solicitation_description'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->solicitation_description : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-recommendation">
                            <td class="bold"><?php echo _l('recommendation'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->recommendation : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-recommendation_select">
                            <td class="bold"><?php echo _l('recommendation_select'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? ucfirst($opportunity->recommendation_select) : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-additional_notes">
                            <td class="bold"><?php echo _l('additional_notes'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->additional_notes : ''; ?>
                            </td>
                        </tr>
                        <tr class="opportunity-overview-ability_to_respond_to_solicitation">
                            <td class="bold"><?php echo _l('ability_to_respond_to_solicitation'); ?></td>
                            <td>
                                <?php echo isset($opportunity) ? $opportunity->ability_to_respond_to_solicitation : ''; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>