<?php
/**
 * Plugin Name: Connexions Impersonate
 * Plugin URI: n/a
 * Description: An addon to allow CRM users to log in as contacts and perform actions on their behalf
 * Version: 0.1.1
 * Author: Brown Box
 * Author URI: http://brownbox.net.au
 * License: Proprietary Brown Box
 */
define('BBCONNECT_IMPERSONATE_DIR', plugin_dir_path(__FILE__));
define('BBCONNECT_IMPERSONATE_URL', plugin_dir_url(__FILE__));

function bbconnect_impersonate_init() {
    if (!defined('BBCONNECT_VER')) {
        add_action('admin_init', 'bbconnect_impersonate_deactivate');
        add_action('admin_notices', 'bbconnect_impersonate_deactivate_notice');
        return;
    }
    if (is_admin()) {
        new BbConnectUpdates(__FILE__, 'BrownBox', 'bbconnect-impersonate');
    }
    $class_dir = plugin_dir_path(__FILE__).'classes/';
    bbconnect_quicklinks_recursive_include($class_dir);
}
add_action('plugins_loaded', 'bbconnect_impersonate_init');

function bbconnect_impersonate_deactivate() {
    deactivate_plugins(plugin_basename(__FILE__));
}

function bbconnect_impersonate_deactivate_notice() {
    echo '<div class="updated"><p><strong>Connexions Impersonate</strong> has been <strong>deactivated</strong> as it requires Connexions.</p></div>';
    if (isset($_GET['activate'])) {
        unset($_GET['activate']);
    }
}

add_filter('bbconnect_activity_icon', 'bbconnect_impersonate_activity_icon', 10, 2);
function bbconnect_impersonate_activity_icon($icon, $activity_type) {
    if ($activity_type == 'impersonate') {
        $icon = plugin_dir_url(__FILE__).'images/activity-icon.png';
    }
    return $icon;
}
