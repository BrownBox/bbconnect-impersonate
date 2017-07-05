<?php
ob_start();
define('WP_USE_THEMES', false);
require_once(dirname(__FILE__).'/../../../wp-load.php');

define('BB_SALT', 'bb_awesome_salt_string');

extract($_GET);
if (empty($action) || !is_user_logged_in()) {
    die('No can do.');
}

switch ($action) {
    case 'impersonate':
        if (empty($user_id) || !current_user_can('list_users'))
            die('No can do!');

        // Set cookie with the info we need
        setcookie('wp_bb_admin_user', get_current_user_id().':'.md5(get_current_user_id().BB_SALT), time()+3600, '/');

        // Log in as user
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        if (class_exists('WC')) { // WooCommerce
            WC()->cart->empty_cart();
        }
        session_destroy();
        wp_redirect('/');
        exit;
        break;
    case 'cease_impersonation':
        if (!empty($_COOKIE['wp_bb_admin_user'])) {
            list($user_id, $hash) = explode(':', $_COOKIE['wp_bb_admin_user']);
            if ($hash == md5($user_id.BB_SALT)) { // Security check
                // Clear cookie
                setcookie('wp_bb_admin_user', '', time()-3600, '/');

                // Log admin user back in
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                if (class_exists('WC')) { // WooCommerce
                    WC()->cart->empty_cart();
                }
                session_destroy();
                wp_redirect('/wp-admin/users.php?page=bbconnect_reports');
                exit;
            }
        }
        break;
    default:
        die('No can do');
        break;
}
