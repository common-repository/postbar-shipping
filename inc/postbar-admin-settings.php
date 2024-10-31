<?php

function postbar_woo_shipping_menu() {
    add_menu_page(
        'حمل و نقل پستِکس',
        'پستِکس',
        'manage_options',
        'woo-postbar-settings',
        'postbar_woo_shipping_options',
        PostbarShipping::plugin_url() . '/assets/images/postbar-small.png',
        56
    );
    add_submenu_page( 'woo-postbar-settings', 'تنظیمات پستِکس', 'تنظیمات', 'manage_options', 'woo-postbar-settings', "postbar_woo_shipping_options" );
    add_submenu_page( 'woo-postbar-settings', 'راهنمای پستِکس', 'راهنما', 'manage_options', 'woo-postbar-help', "postbar_woo_shipping_help" );

	add_action( 'admin_init', 'register_postbar_woo_shipping_settings' );
}
function register_postbar_woo_shipping_settings() {
	register_setting( 'postbar_woo_shipping-settings-group', 'postbar_woo_shipping_opts' );
}
add_action( 'admin_menu', 'postbar_woo_shipping_menu' );


// Postbar Settings
function postbar_woo_shipping_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'شما سطح دسترسی لازم برای مشاهده این قسمت را ندارید.' ) );
	}
    ?>

    <!-- wrap -->
	<div class="wrap">
        <form method="post" action="options.php">
        
            <?php 
                settings_fields( 'postbar_woo_shipping-settings-group' ); 
                do_settings_sections( 'postbar_woo_shipping-settings-group' ); 

                $postbar_username = get_option('postbar_woo_shipping_opts')["postbar_username"];
                $postbar_password = get_option('postbar_woo_shipping_opts')["postbar_password"];
                $postbar_token = get_option('postbar_woo_shipping_token');


                $postbar_user = new stdClass();
                if($postbar_username && $postbar_password)
                {
                    $postbar_user_data = Postbar_API::login($postbar_username, $postbar_password);
                    if($postbar_user_data->success && $postbar_user_data->data->Token)
                    {
                        update_option( 'postbar_woo_shipping_token', $postbar_user_data->data->Token );
                        $postbar_user = $postbar_user_data->data;
                    }
                    else
                    {
                        ?>
                        <script>
                            jQuery(function($){
                                $(document).ready(function(){
                                    postbarErrNotify(<?php echo json_encode($postbar_user_data->errors); ?>);
                                });
                            });
                        </script>
                        <?php
                        update_option( 'postbar_woo_shipping_token', '' );
                    }
                }
                else
                {
                    update_option( 'postbar_woo_shipping_token', '' );
                }

                $postbar_token = get_option('postbar_woo_shipping_token');
                
            
                include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-settings-header.php' ); 
                include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-settings-user-data.php' ); 
                include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-settings-sender.php' ); 
                include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-settings-receiver.php' ); 
                include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-settings-defaults.php' ); 
            ?>

        </form>
    </div>
    <!-- End: wrap -->
    
	<?php
}

include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-help.php' );