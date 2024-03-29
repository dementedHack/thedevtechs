<?php

/*
Plugin Name: Responsive Menu Pro
Plugin URI: https://responsive.menu
Description: Highly Customisable Responsive Menu Plugin for WordPress
Version: 3.1.19
Author: Peter Featherstone
Text Domain: responsive-menu-pro
Author URI: https://peterfeatherstone.com
License: GPL2
Tags: responsive, menu, responsive menu
*/

/* Check correct PHP version first */
add_action('admin_init', 'check_responsive_menu_pro_php_version');
function check_responsive_menu_pro_php_version() {
    if(version_compare(PHP_VERSION, '5.4', '<')):
        add_action('admin_notices', 'responsive_menu_pro_deactivation_text');
        deactivate_plugins(plugin_basename(__FILE__));
    endif;
}

function responsive_menu_pro_deactivation_text() {
    echo '<div class="error"><p>' . sprintf(
            'Responsive Menu Pro requires PHP 5.4 or higher to function and has therefore been automatically disabled.
            You are still on %s.%sPlease speak to your web host about upgrading your PHP version.',
            PHP_VERSION,
            '<br /><br />'
        ) . '</p></div>';
}

if(version_compare(PHP_VERSION, '5.4', '<'))
    return;

include dirname(__FILE__) . '/vendor/autoload.php';
include dirname(__FILE__) . '/config/default_options.php';
include dirname(__FILE__) . '/config/services.php';
include dirname(__FILE__) . '/config/wp/scripts.php';
include dirname(__FILE__) . '/config/routing.php';
include dirname(__FILE__) . '/migration.php';
include dirname(__FILE__) . '/config/polylang.php';

if(is_admin()):
    $updater = new ResponsiveMenuPro\Licensing\Check('https://responsive.menu', __FILE__, array(
        'version' => get_option('responsive_menu_pro_version'),
        'license' => trim(get_option('responsive_menu_pro_license_key')),
        'item_id' => get_option('responsive_menu_pro_license_type') == 'Multi License' ? 1143 : 1175,
        'author' => 'Responsive Menu',
        'url' => home_url()
    ));
endif;