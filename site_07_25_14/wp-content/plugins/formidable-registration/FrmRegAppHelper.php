<?php
 
class FrmRegAppHelper{
    
    public static function get_default_options(){
        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
        
        return array(
            'registration' => 0, 
            'login' => 0,
            'reg_avatar' => '',
            'reg_username' => '', 
            'reg_email' => '', 
            'reg_password' => '',
            'reg_last_name' => '',
            'reg_first_name' => '',
            'reg_display_name' => '',
            'reg_role' => 'subscriber',
            'reg_usermeta' => array(),
            'reg_email_subject' => '[sitename] '. __('Your username and password', 'frmreg'),
            'reg_email_msg' => (sprintf(__('Username: %s', 'frmreg'), '[username]') . "\r\n" .
                sprintf(__('Password: %s', 'frmreg'), '[password]') . "\r\n" . wp_login_url()),
            'reg_email_sender' => 'wordpress@'. $sitename,
            'reg_email_from' => 'WordPress',
        );
    }
    
    public static function username_exists($username){
        $username = sanitize_user($username, true);
        
        if(!function_exists('username_exists'))
            require_once(ABSPATH . WPINC . '/registration.php');
        
        return username_exists( $username );
    }
    
    public static function generate_unique_username($username, $count=0){
        $count = (int)$count;
        $new_username = ($count > 0) ? $username . $count : $username;

        if (FrmRegAppHelper::username_exists($new_username))
            $new_username = FrmRegAppHelper::generate_unique_username($username, $count+1);
        
        return sanitize_user($new_username, true);
    }
    
    public static function get_user_meta($user_ID, $meta_key){
        if(function_exists('get_user_meta'))
            get_user_meta($user_ID, $meta_key, true);
        else
            get_usermeta($user_ID, $meta_key, true);
    }
}
