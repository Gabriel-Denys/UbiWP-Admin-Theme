<?php

function edit_for_manager()
{
    global $wp_roles;
    if (!isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }
    $adm = $wp_roles->get_role('administrator');
    remove_role('manager');
    add_role('manager', __(
        'Manager'),
        $adm->capabilities,
    );

    $user = wp_get_current_user();
    if (in_array('shop_manager', (array) $user->roles)) {

        add_action('admin_menu', 'ubi_remove_admin_menus');
        add_action('admin_menu', 'better_menus', 9999);
        function ubi_remove_admin_menus()
        {

            remove_menu_page('edit-comments.php');
            remove_menu_page('link-manager.php');
            remove_menu_page('tools.php');
            remove_menu_page('plugins.php');
            remove_menu_page('users.php');
            remove_menu_page('options-general.php');
            remove_menu_page('edit.php');
            remove_menu_page('index.php');
            remove_menu_page('profile.php');
            remove_menu_page('upload.php');
            remove_menu_page('edit.php?post_type=page');
            remove_menu_page('woocommerce-marketing');
            remove_menu_page('edit.php?post_type=product');
            remove_menu_page('themes.php');

        }

    }

 

}

add_action('init', 'edit_for_manager');

function better_menus()
{
    global $menu;

    add_menu_page(
        __('Content'), // the page title
        __('Content'), //menu title
        'read', //capability
        'content', //menu slug/handle this is what you need!!!
        '', //callback function
        'dashicons-content', //icon_url,
        '1' //position
    );
    add_menu_page(
        __('Settings'), // the page title
        __('Settings'), //menu title
        'read', //capability
        'settings', //menu slug/handle this is what you need!!!
        '', //callback function
        'dashicons-admin-settings', //icon_url,
        '9999' //position
    );
    add_submenu_page(
        'content',
        'Media Library', //page title
        'Media Library', //menu title
        'read', //capability,
        'upload.php', //menu slug
        '' //callback function
    );
    add_submenu_page(
        'content',
        'Pages', //page title
        'Pages', //menu title
        'read', //capability,
        'edit.php?post_type=page', //menu slug
        '' //callback function
    );
    add_submenu_page(
        'content',
        'Post Categories', //page title
        'Post Categories', //menu title
        'read', //capability,
        'edit-tags.php?taxonomy=category', //menu slug
        '' //callback function
    );

    add_submenu_page(
        'content',
        'Post Tags', //page title
        'Post Tags', //menu title
        'read', //capability,
        'edit-tags.php?taxonomy=post_tag', //menu slug
        '' //callback function
    );
    add_submenu_page(
        'content',
        'Comments', //page title
        'Comments', //menu title
        'read', //capability,
        'edit-comments.php', //menu slug
        '' //callback function
    );
    edit_woocoomerce_item();
    add_submenu_page(
        'woocommerce',
        'Coupons', //page title
        'Coupons', //menu title
        'read', //capability,
        'edit.php?post_type=shop_coupon', //menu slug
        '' //callback function
    );
    add_submenu_page(
        'woocommerce',
        'Products', //page title
        'Products', //menu title
        'read', //capability,
        'edit.php?post_type=product', //menu slug
        '' //callback function
    );
   
    add_submenu_page(
        'settings',
        'Analytics Settings', //page title
        'Analytics Settings', //menu title
        'read', //capability,
        'admin.php?page=wc-admin&path=%2Fanalytics%2Fsettings', //menu slug
        '' //callback function
    );
    global $submenu;
    $submenu["settings"][0][0] = "All Users";
    $submenu["settings"][0][2] = "users.php";
    

    $submenu["content"][0][0] = "Posts";
    $submenu["content"][0][2] = "edit.php";

}

function edit_woocoomerce_item()
{
    global $menu;
    global $submenu;

    // Pinpoint menu item
    $woo = get_woocommerce_item('WooCommerce', $menu);

    // Validate
    if (!$woo) {
        return;
    }

    $menu[$woo][0] = 'Shop';

    $submenu["woocommerce"][0][0] = null;
    $submenu['woocommerce'] = array_splice($submenu['woocommerce'], 0, -4);
    //print_r($submenu);
    $submenu['wc-admin&path=/analytics/overview'] = array_splice($submenu['wc-admin&path=/analytics/overview'], 0, -1);
}

function get_woocommerce_item($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        $current_key = $key;
        if (
            $needle === $value
            or (
                is_array($value)
                && get_woocommerce_item($needle, $value) !== false
            )
        ) {
            return $current_key;
        }
    }
    return false;
}


