<?php /**
 * Plugin Name: UbiWP Admin Panel Theme
 * Plugin URI: https://vertadigital.com
 * Description: A Better WP Admin Panel
 * Version: 0.0.1
 * Author: Gabriel Denys
 * Author URI: https://vertadigital.com
 * License: Closed source and private (for time of beta testing)
 **/

include plugin_dir_path(__FILE__) . 'includes/managerrole.php';
/* UI for Theme */

add_filter('admin_bar_menu', 'replace_wordpress_howdy', 25);
function replace_wordpress_howdy($wp_admin_bar)
{
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtext = str_replace('Howdy,', '', $my_account->title);
    $wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => $newtext,
    ));
}

function my_admin_page_contents()
{
    ?>
        <h1>
            <?php esc_html_e('Welcome to my custom admin page.', 'my-plugin-textdomain');?>
        </h1>
    <?php
}

// Load Custom CSS

/* ADD CSS STYLES TO PAGE */
function ubi_dash_wp_admin_style()
{
    wp_enqueue_style('uicons-rr', plugins_url('/uicons/css/uicons-regular-rounded.css', __FILE__));
    wp_enqueue_style('ubi_main_css', plugins_url('/styles/styles.css', __FILE__));
    wp_enqueue_style('ubi_admin_menu_css', plugins_url('/styles/AdminMenus.css', __FILE__));
    wp_enqueue_style('ubi_wpcontent_css', plugins_url('/styles/WPContent.css', __FILE__));
    wp_enqueue_style('ubi_woocommerce', plugins_url('/styles/woocommerce.css', __FILE__));
    $user = wp_get_current_user();
    wp_enqueue_script('ubi_js', plugins_url('/js/main.js', __FILE__));
    if (in_array('shop_manager', (array) $user->roles)) {
        //wp_enqueue_script('ubi_js', plugins_url('/js/main.js', __FILE__));
    }
}

add_action('admin_print_styles', 'ubi_dash_wp_admin_style');

add_action('admin_head', 'fouc');
function fouc()
{
    $user = wp_get_current_user();
    ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;600&display=swap" rel="stylesheet">
      <style type="text/css">
            .hidden {display:none;}
        </style>

        <style>
            .dashicons-current-profile
            {
                background-image: url(<?php echo plugin_dir_url(__FILE__) . 'img/profilepic.jpeg'; ?>);
            }
            </style>


    <?php

}
//Later need to enable screen meta
/*
add_action('admin_head', 'edit_screen_meta');

function blur_back()
{
    require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
    require_once ABSPATH . 'wp-admin/includes/screen.php';
    $screen = get_current_screen();

// This actually works, it's just hidden via css
    WP_Screen::get('')->render_screen_meta();

}*/
add_action('admin_menu', 'linked_url');
function linked_url()
{
    global $current_user;
    wp_get_current_user();
    add_menu_page('linked_url', $current_user->user_login, 'read', 'admin_profile', '', 'dashicons-current-profile', 1);
    add_submenu_page(
        'admin_profile',
        '', //page title
        'Log Out', //menu title
        'read', //capability,
        'lougout', //menu slug
        'log_out' //callback function
    );
}

add_action('admin_menu', 'linkedurl_function');
function linkedurl_function()
{
    global $submenu;
    $submenu["admin_profile"][0][0] = "Your Profile";
    $submenu["admin_profile"][0][2] = "profile.php";
    $submenu["admin_profile"][1][2] = wp_logout_url(home_url());

}

?>