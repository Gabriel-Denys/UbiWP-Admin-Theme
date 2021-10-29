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
                background-image: url(<?php echo plugin_dir_url(__FILE__) . 'img/profilepic.jpg'; ?>);
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
    add_submenu_page(
        'admin_profile',
        '', //page title
        'Visit Site', //menu title
        'read', //capability,
        'visitsite', //menu slug
        'visit_site' //callback function
    );
}

add_action('admin_menu', 'linkedurl_function');
function linkedurl_function()
{
    global $submenu;
    $menu_visit = $submenu["admin_profile"][2];
    unset($submenu["admin_profile"][2]);
    array_unshift($submenu["admin_profile"], $menu_visit);

    $submenu["admin_profile"][1][0] = "Your Profile";
    $submenu["admin_profile"][1][2] = "profile.php";
    $submenu["admin_profile"][2][2] = wp_logout_url(home_url());
    $submenu["admin_profile"][0][2] = home_url();
    
    
}

if (is_admin()) { 
    function jba_disable_editor_fullscreen_by_default() {
    $script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
    wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'jba_disable_editor_fullscreen_by_default' );
}

function custom_notification_helper($message, $type)
{

    if(!is_admin()) {
        return false;
    }

    // todo: check these are valid
    if(!in_array($type, array('error', 'info', 'success', 'warning'))) {
        return false;
    }

    // Store/retrieve a transient associated with the current logged in user
    $transientName = 'admin_custom_notification_'.get_current_user_id();

    // Check if this transient already exists. We can use this to add
    // multiple notifications during a single pass through our code
    $notifications = get_transient($transientName);

    if(!$notifications) {
        $notifications = array(); // initialise as a blank array
    }

    $notifications[] = array(
        'message' => $message,
        'type' => $type
    );

    set_transient($transientName, $notifications);  // no need to provide an expiration, will
                                                    // be removed immediately

}
/**
 * The handler to output our admin notification messages
 */
function custom_admin_notice_handler() {

    if(!is_admin()) {
        // Only process this when in admin context
        return;
    }

    $transientName = 'admin_custom_notification_'.get_current_user_id();

    // Check if there are any notices stored
    $notifications = get_transient($transientName);

    if($notifications):
        foreach($notifications as $notification):
            echo <<<HTML

                <div class="notice notice-custom notice-{$notification['type']} is-dismissible">
                    <p>{$notification['message']}</p>
                </div>

HTML;
        endforeach;
    endif;

    // Clear away our transient data, it's not needed any more
    delete_transient($transientName);

}
add_action( 'admin_notices', 'custom_admin_notice_handler' );

function test_custom_admin_notices()
{
    if(isset($_GET['test_admin_notices'])) {
        custom_notification_helper('Custom error notice', 'error');
        custom_notification_helper('Custom success notice', 'success');
        custom_notification_helper('Custom warning notice', 'warning');
        custom_notification_helper('Custom info notice', 'info');
        // Simulate a redirect
        header('Location: index.php');
        exit;
    }
}
add_action('admin_init', 'test_custom_admin_notices');


/* Custom Mobile Menu*/
function render_mobile_admin_bar()
{
   ?>
<div id="mobile-admin-menu">
<div class="ubi-admin-menu-toggle">
</div>
</div>
    <?php
}
add_action('admin_head', 'render_mobile_admin_bar');
?>