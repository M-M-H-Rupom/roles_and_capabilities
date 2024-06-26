<?php 
/**
 * Plugin Name: Roles and capabilities 
 * Author: Rupom
 * Description: Plugin description
 * Version: 1.0
 */

 function callback_enqueue_scripts_user(){
     wp_enqueue_style( 'user_css', plugin_dir_url( __FILE__ ).'/assets/css/style.css' );
     wp_enqueue_script( 'user_js', plugin_dir_url( __FILE__ ).'/assets/js/main.js', array('jquery'), time(), true);
     $n_action = 'user_protected';
     $u_nonce = wp_create_nonce($n_action);
     wp_localize_script('user_js', 'localize_user', array(
         'ajax_url' => admin_url('admin-ajax.php'),
         'user_nonce' => $u_nonce,
         'user_action' => $n_action
     ));
 }
 add_action( 'admin_enqueue_scripts','callback_enqueue_scripts_user');
 add_action( 'login_enqueue_scripts','callback_enqueue_scripts_user');
 add_action( 'admin_menu', function(){
    add_menu_page('user_data', 'User data', 'manage_options', 'userdata', 'user_data_callback');
 } );
 function user_data_callback(){
    ?>
    <button class='user_action' data-task='current_user'> Current user</button>
    <button class='user_action' data-task='any_user'> Any user</button>
    <button class='user_action' data-task='all_roles'> All roles</button>
    <button class='user_action' data-task='add_user'> Add user</button>
    <button class='user_action' data-task='add_role'> Add role</button>
    <button class='user_action' data-task=''> </button>
    <?php
 }
// ajax user data
 add_action( 'wp_ajax_user_data', function(){
   if(wp_verify_nonce($_POST['user_nonce'], $_POST['user_action'])){
      if('current_user' == $_POST['user_task']){
         $user = wp_get_current_user();
         wp_send_json($user->user_email);
      }elseif('any_user' == $_POST['user_task']){
         $any_user = new WP_User(2);
         wp_send_json($any_user->roles);
      }elseif('all_roles' == $_POST['user_task']){
         $wp_roles = get_editable_roles();
         wp_send_json($wp_roles);
      }elseif('add_user' == $_POST['user_task']){
        $add_user = wp_create_user('test', 123456, 'test@gmail.com');
         wp_send_json($add_user);
      }elseif('add_role' == $_POST['user_task']){
         $user = new WP_User(3);
         $user->remove_role('subscriber');
         $user->add_role('author');
         wp_send_json($user);
      }
   }
   die();
 } );
//  blocked user 
add_action( 'init', function(){
   add_role( 'user_blokced', 'blocked', array('blocked' => true) );
   add_rewrite_rule('blocked/?$', 'index.php?blocked=1', 'top');
} );
add_filter( 'query_vars', function($query_vars){
   $query_vars[] = 'blocked';
   return $query_vars;
} );
add_action( 'template_redirect', function(){
   $is_blocked = intval(get_query_var('blocked'));
   if($is_blocked){
      ?>
      <!DOCTYPE html>
      <html lang="en">
      <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>blocked</title>
      </head>
      <body>
         <h2> <?php echo 'you are blocked' ;?> </h2>
      </body>
      </html>
      <?php
   }
   die();
} );
add_action( 'init', function(){
   if(is_admin() && current_user_can('blocked')){
      wp_redirect(get_home_url().'/blocked');
      die();
   }
} );

// wordpress login form
require_once('login_form.php');

?>