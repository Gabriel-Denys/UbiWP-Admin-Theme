<?php 
/**
 * Plugin Name: UbiWP Admin Panel Theme
 * Plugin URI: https://vertadigital.com
 * Description: A Better WP Admin Panel
 * Version: 0.0.1
 * Author: Gabriel Denys
 * Author URI: https://vertadigital.com
 * License: Closed source and private (for time of beta testing)
 **/

include plugin_dir_path(__FILE__) . 'includes/managerrole.php';

//Replace wordpress howdy with a custom welcome message
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


/* ADD CSS STYLES TO PAGE */
function ubi_dash_wp_admin_style()
{
    wp_enqueue_style('uicons-rr', plugins_url('/uicons/css/uicons-regular-rounded.css', __FILE__));
    wp_enqueue_style('ubi_main_css', plugins_url('/styles/styles.css', __FILE__));
    wp_enqueue_style('ubi_admin_menu_css', plugins_url('/styles/AdminMenus.css', __FILE__));
    wp_enqueue_style('ubi_wpcontent_css', plugins_url('/styles/WPContent.css', __FILE__));
    wp_enqueue_style('ubi_woocommerce', plugins_url('/styles/woocommerce.css', __FILE__));
    wp_enqueue_style('ubi_customizer', plugins_url('/styles/customizer.css', __FILE__));
    $user = wp_get_current_user();
    wp_enqueue_script('ubi_js', plugins_url('/js/main.js', __FILE__));
    if (in_array('shop_manager', (array) $user->roles)) {
        //wp_enqueue_script('ubi_js', plugins_url('/js/main.js', __FILE__));
    }
}

// Styles for the log in page
function ubi_login() {
    wp_enqueue_style('ubi_main_css', plugins_url('/styles/styles.css', __FILE__));
    wp_enqueue_style('custom-login', plugins_url('/styles/ubi-login.css', __FILE__)  );
    wp_enqueue_style('uicons-rr', plugins_url('/uicons/css/uicons-regular-rounded.css', __FILE__));

}
add_action( 'login_enqueue_scripts', 'ubi_login' );

//styles for front-end admin bar
function ubi_admin_bar()
{
    wp_enqueue_style('ubi_main_css', plugins_url('/styles/styles.css', __FILE__));
    wp_enqueue_style('ubi_admin_bar_css', plugins_url('/styles/adminbar.css', __FILE__));
    wp_enqueue_style('uicons-rr', plugins_url('/uicons/css/uicons-regular-rounded.css', __FILE__));
}
add_action('wp_head', 'ubi_admin_bar');



add_action('admin_print_styles', 'ubi_dash_wp_admin_style');

//styles for the theme customizer
function customizer_preview() {
    // Register my custom stylesheet
    wp_register_style('customizer_preview', plugins_url('/styles/customizer-preview.css', __FILE__));
    // Load my custom stylesheet
    wp_enqueue_style('customizer_preview');
  }
  add_action('wp_enqueue_scripts', 'customizer_preview');

//prevent FOUC on admin pages
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

// Add a menu page that displays user profile and other options
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
        'Your Profile', //menu title
        'read', //capability,
        'profile', //menu slug
        'visit_profile' //callback function
    );
}
add_action('admin_menu', 'linkedurl_function');

//edit the profile menu to include visit site and log out
function linkedurl_function()
{
    global $submenu;
    $menu_visit = $submenu["admin_profile"][2];
    unset($submenu["admin_profile"][2]);
    array_unshift($submenu["admin_profile"], $menu_visit);

    $submenu["admin_profile"][0][0] = "Your Profile";
    $submenu["admin_profile"][0][2] = "/wp-admin/profile.php";
    $submenu["admin_profile"][2][2] = wp_logout_url(home_url());
    $submenu["admin_profile"][1][0] = "Visit Site";
    $submenu["admin_profile"][1][2] = home_url();

}

// disable the full page experience of the block editor
if (is_admin()) {
    function jba_disable_editor_fullscreen_by_default()
    {
        $script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
        wp_add_inline_script('wp-blocks', $script);
    }
    add_action('enqueue_block_editor_assets', 'jba_disable_editor_fullscreen_by_default');
}

/* Custom Mobile Nav Bar*/
function render_mobile_admin_bar()
{
    $home_url = home_url();
    echo <<<HTML

<div id="mobile-admin-menu">
<div class="ubi-admin-menu-toggle">
</div>

<a class="ubi-home-icon" href="$home_url"></a>
</div>

HTML;
}
add_action('admin_head', 'render_mobile_admin_bar');



?>