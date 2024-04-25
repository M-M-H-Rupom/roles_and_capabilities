<?php
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo plugin_dir_url( __FILE__ ).'/assets/images/wp_logo.png'?>);
            height:100px;
            width:100px;
            background-size: 100px;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' ); 

add_action( 'login_head', function(){
    add_filter( 'gettext', function($tranlate_text, $to_tranlate){
        if('Username or Email Address' == $to_tranlate){
            $tranlate_text = 'Your login key';
        }elseif('Password' == $to_tranlate){
            $tranlate_text = 'Pass key';
        }
        return $tranlate_text;
    },10,2);
} );
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'My new blog';
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );
add_action( 'register_form', function(){
    $f_name = $_POST['first_name'] ?? '';
    $l_name = $_POST['last_name'] ?? '';
    ?>
    <p>
        <label for="" > First Name</label>
        <input type="text" name="first_name" value="<?php echo esc_attr($f_name) ?>">
    </p>
    <p>
        <label for="" > Last Name</label>
        <input type="text" name="last_name" value="<?php echo esc_attr($l_name) ?>">
    </p>
    <?php
});

add_filter( 'registration_errors', function($errors){
    if(empty($_POST['first_name'])){
        $errors->add('first_name_blank', 'First name can not be blank');
    }
    if(empty($_POST['last_name'])){
        $errors->add('last_name_blank', 'last name can not be blank');
    }
    return $errors;
});
add_action( 'user_register', function($user_id){
    if(!empty($_POST['first_name'])){
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
    }
    if(!empty($_POST['last_name'])){
        update_user_meta($user_id, 'last_name', $_POST['last_name']);
    }
} );
?>