<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Document
Version: 1.1.0
Author: Palladium Hub
Author URI: https://palladiumhub.com/
*/

define('DOCUMENT_MODULE_NAME', 'document');
define('VERSION_DOCUMENT', 1101);

hooks()->add_action('admin_init', 'document_module_init_menu_items');
hooks()->add_action('customers_navigation_end', 'document_module_init_client_menu_items');
hooks()->add_action('client_pt_footer_js', 'document_client_foot_js');

//email theme
register_merge_fields('document/merge_fields/document_share_merge_fields');

/**
 * Injects needed CSS.
 */


// Register activation module hook
register_activation_hook(DOCUMENT_MODULE_NAME, 'document_module_activation_hook');
/**
 * Load the module helper.
 */
$CI = &get_instance();
$CI->load->helper(DOCUMENT_MODULE_NAME . '/document');

function document_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/install.php';
}

// Register language files, must be registered if the module is using languages
register_language_files(DOCUMENT_MODULE_NAME, [DOCUMENT_MODULE_NAME]);



/**
 * Init goals module menu items in setup in admin_init hook.
 *
 * @return null
 */
function document_module_init_menu_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('document', [
        'slug'     => 'document',
        'name'     => _l('document'),
        'icon'     => 'fa fa-file-text',
        'href'     => admin_url('document/manage'),
        'position' => 40,
    ]);
}

// hooks()->add_action('customers_navigation_start', 'add_document_menu');
// function add_document_menu()
// {
//     $CI = &get_instance();
//     if (is_client_logged_in()) {
//         if (has_contact_permission('document')) {
//             echo '<li class="customers-nav-item-contracts">
//                 <a href="' . site_url('document/client') . '">' . _l('document') . '</a>
//             </li>';
//         }
//     }
// }


hooks()->add_action('admin_init', 'document_permissions');
function document_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view_own'   => _l('permission_view') . '(' . _l('permission_view_own') . ')',
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('document', $capabilities, _l('document'));
}

/**
 * init add head component
 */
hooks()->add_action('app_admin_head', 'document_head_component');
function document_head_component()
{
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];



    echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/firepad-userlist.js') . '"></script>';
    if (!(strpos($viewuri, 'admin/document/manage') === false) || !(strpos($viewuri, 'admin/projects/view') === false)) {
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.theme.default.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/screen.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/manage_style.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, 'admin/proposals') === false) || !(strpos($viewuri, 'admin/estimates') === false) || !(strpos($viewuri, 'admin/invoices') === false) || !(strpos($viewuri, 'admin/expenses') === false) || !(strpos($viewuri, 'admin/leads') === false)) {
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.theme.default.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/screen.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/custom.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, 'admin/proposals') === false) || !(strpos($viewuri, 'admin/estimates') === false) || !(strpos($viewuri, 'admin/invoices') === false) || !(strpos($viewuri, 'admin/expenses') === false) || !(strpos($viewuri, 'admin/leads') === false)) {
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.theme.default.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/screen.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/custom.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
    }

    if (!(strpos($viewuri, 'admin/document/new_file_view') === false) || !(strpos($viewuri, 'admin/document/file_view_share') === false)) {
        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ComboTree/style.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';

        echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/manage.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
    }
}


/**
 * init add footer component
 */
hooks()->add_action('app_admin_footer', 'document_online_load_js');
function document_online_load_js()
{
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if (!(strpos($viewuri, 'admin/document/manage') === false)) {
        echo '<script>';
        echo 'var download_file = "' . _l('download') . '";';
        echo 'var create_file = "' . _l('create_file') . '";';
        echo 'var create_folder = "' . _l('create_folder') . '";';
        echo 'var edit = "' . _l('edit') . '";';
        echo '</script>';
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/jquery.treetable.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/manage.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/context_menu.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
    }
    if (!(strpos($viewuri, 'admin/projects/view') === false)  || !(strpos($viewuri, 'admin/estimates') === false) || !(strpos($viewuri, 'admin/proposals') === false) || !(strpos($viewuri, 'admin/invoices') === false) || !(strpos($viewuri, 'admin/expenses') === false) || !(strpos($viewuri, 'admin/leads') === false)) {
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/manage.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/jquery.treetable.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/relate_to.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
    }

    if (!(strpos($viewuri, 'admin/document/new_file_view') === false) || !(strpos($viewuri, 'admin/document/file_view_share') === false)) {
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ComboTree/comboTreePlugin.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
        echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ComboTree/icontains.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
    }
}

/**
 *  add menu item and js file to client
 */
function document_module_init_client_menu_items() {
	$CI = &get_instance();

	$viewuri = $_SERVER['REQUEST_URI'];
    echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/firepad-userlist.js') . '"></script>';
	if (!(strpos($viewuri, 'document/document_client/file_view_share_related') === false)) {
		echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/custom.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.theme.default.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/screen.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/manage.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
	}
	if (is_client_logged_in()) {
		if (!(strpos($viewuri, 'document/document_client/file_view_share') === false)) {
			echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/custom.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
		}
		if (!(strpos($viewuri, 'document/document_client') === false)) {
			echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
			echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/jquery.treetable.theme.default.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
			echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/css/screen.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
			echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/manage.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';
			echo '<link href="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/css/custom.css') . '?v=' . VERSION_DOCUMENT . '"  rel="stylesheet" type="text/css" />';

		} 
		
        if (has_contact_permission('document')) {
            $client_id = get_client_user_id();
		$CI->load->model('document/document_model');
		$check_share = $CI->document_model->get_my_folder_by_client_share_folder_view($client_id);
            if ($check_share) {
                echo '
        <li class="customers-nav-item-Insurances-plan">
        <a href="' . site_url('document/document_client') . '">' . _l('document') . '</a>
        </li>
        ';
            }
        }

	}
}


/**
 * add element for footer portal
 */
function document_client_foot_js() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];

	if (!(strpos($viewuri, 'document/document_client') === false)) {
		echo '<script>';
		echo 'var site_url = "' . site_url() . '";';
		echo 'var admin_url = "' . admin_url() . '";';
		echo 'var create_file = "' . _l('create_file') . '";';
		echo 'var create_folder = "' . _l('create_folder') . '";';
		echo 'var download_file = "' . _l('download') . '";';
		echo 'var edit = "' . _l('edit') . '";';
		echo '</script>';
		echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/jquery-ui.min.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
		echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ludo-jquery-treetable/jquery.treetable.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
		echo '<script  src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/client_sheet.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
		echo '<script  src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/js/context_menu_client.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
	}
	if (!(strpos($viewuri, 'document/document_client/file_view_share') === false)) {
		echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ComboTree/comboTreePlugin.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
		echo '<script src="' . module_dir_url(DOCUMENT_MODULE_NAME, 'assets/plugins/ComboTree/icontains.js') . '?v=' . VERSION_DOCUMENT . '"></script>';
	}
}

/**
 * Initializes the project item relate.
 *
 * @param        $project  The project
 */
function init_document_project_item_relate_so($project) {
	$CI = &get_instance();
	if (is_admin() || is_staff_logged_in()) {
		$CI->load->model('document/document_model');
		$folder_my_tree = $CI->document_model->tree_my_folder_related('project', $project->id);
		require "modules/document/views/view_related_general.php";
	}
}


hooks()->add_filter('get_contact_permissions', 'add_document_permission');
function add_document_permission($permissions)
{
    $permissions[] = [
        'id'         => 10,
        'name'       => _l('document'),
        'short_name' => 'document',
    ];

    return $permissions;
}

