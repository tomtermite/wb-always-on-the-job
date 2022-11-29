<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Opportunity
Version: 1.1.0
Author: Palladium Hub
Author URI: https://palladiumhub.com/
*/

define('OPPORTUNITY_MODULE_NAME', 'opportunity');

hooks()->add_action('admin_init', 'opportunity_module_init_menu_items');


/**
 * Injects needed CSS.
 */


// Register activation module hook
register_activation_hook(OPPORTUNITY_MODULE_NAME, 'opportunity_module_activation_hook');
/**
 * Load the module helper.
 */
$CI = &get_instance();

function opportunity_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/install.php';
}

// Register language files, must be registered if the module is using languages
register_language_files(OPPORTUNITY_MODULE_NAME, [OPPORTUNITY_MODULE_NAME]);

$CI = &get_instance();
$CI->load->helper(OPPORTUNITY_MODULE_NAME.'/opportunity');

/**
 * Init goals module menu items in setup in admin_init hook.
 *
 * @return null
 */
function opportunity_module_init_menu_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('opportunity', [
        'slug'     => 'opportunity',
        'name'     => _l('opportunity'),
        'icon'     => 'fa fa-tasks',
        'href'     => admin_url('opportunity'),
        'position' => 40,
    ]);
}

hooks()->add_action('customers_navigation_start', 'add_opportunity_menu');
function add_opportunity_menu()
{
    $CI = &get_instance();
    if (is_client_logged_in()) {
        if (has_contact_permission('opportunity')) {
            echo '<li class="customers-nav-item-contracts">
                <a href="' . site_url('opportunity/client') . '">' . _l('opportunity') . '</a>
            </li>';
        }
    }
}

hooks()->add_filter('customer_profile_tabs', 'add_menu_in_client_tab');
function add_menu_in_client_tab($tabs)
{

    $menu['opportunity'] = array(
        'slug' => 'opportunity',
        "name" => "Opportunity",
        "view" => 'opportunity/clients/opportunity',
        "position" => 11,
        "icon" => 'fa fa-tasks menu-icon',
        "href" => '#',
        "badge" => array(),
        "children" => array()
    );
    $tabs = array_merge($tabs, $menu);

    return $tabs;
}

hooks()->add_action('admin_init', 'opportunity_permissions');
function opportunity_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view_own'   => _l('permission_view') . '(' . _l('permission_view_own') . ')',
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('opportunity', $capabilities, _l('opportunity'));
}

hooks()->add_filter('get_contact_permissions', 'add_opportunity_permission');
function add_opportunity_permission($permissions)
{
    $permissions[] = [
        'id'         => 8,
        'name'       => _l('opportunity'),
        'short_name' => 'opportunity',
    ];

    return $permissions;
}
