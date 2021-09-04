<?php /**
 * Plugin Name: Ubi WP Dashboard
 * Plugin URI: https://vertadigital.com
 * Description: Manage & Secure websites remotely
 * Version: 0.0.1
 * Author: Gabriel Denys
 * Author URI: https://vertadigital.com
 * License: GPL2
 **/

 /** Functional */
/* add_role('manager', __(
    'Manager'),
    array(
        'read' => true, // Allows a user to read
        'create_posts' => false, // Allows user to create new posts
        'edit_posts' => false, // Allows user to edit their own posts
    )
);

add_action('admin_menu', 'ubi_remove_admin_menus');
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
}


function my_admin_menu()
{
    add_menu_page(
        __('Status', 'my-textdomain'),
        __('Status', 'my-textdomain'),
        'read',
        'status',
        'my_admin_page_contents',
        'dashicons-status',
        3
    );
}
function dashboard_redirect()
{
    wp_redirect(admin_url('admin.php?page=status'));
}
add_action('load-index.php', 'dashboard_redirect');

function login_redirect($redirect_to, $request, $user)
{
    return admin_url('admin.php?page=status');
}
add_filter('login_redirect', 'login_redirect', 10, 3);

add_action('admin_menu', 'my_admin_menu');
*/






/* UI for Theme */



function fl_dashboard()
{
    wp_enqueue_style('uicons', 'https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css');
}

add_action('admin_init', 'fl_dashboard');

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
    wp_enqueue_style('ubi_admin_css', plugins_url('/styles/styles.css', __FILE__));
}

add_action('admin_print_styles', 'ubi_dash_wp_admin_style');

add_action('admin_head', 'fouc');
function fouc()
{
    $user = wp_get_current_user();
        ?>
      <style type="text/css">
            .hidden {display:none;}
        </style>
        <script type="text/javascript">
         jQuery('html').addClass('hidden');

	 jQuery(document).ready(function($) {
	    $('html').removeClass('hidden');
	 });
        </script>
  

    <?php

}


add_action('adminmenu', 'ubi_admin_menu' );

function ubi_admin_menu(){
		
    //echo '<a class="ab-item" aria-haspopup="true" href="http://test-site.local/wp-admin/profile.php"> <span class="display-name">admin</span><img alt="" src="http://1.gravatar.com/avatar/4bf699f30aaf1c5c6332f56334fb413f?s=26&amp;d=mm&amp;r=g" srcset="http://1.gravatar.com/avatar/4bf699f30aaf1c5c6332f56334fb413f?s=52&amp;d=mm&amp;r=g 2x" class="avatar avatar-26 photo" height="26" width="26" loading="lazy"></a>';
  }







?>
