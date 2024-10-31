<?php

/***** admin enqueue scripts and styles *****/
function postbar_shipping_admin_enqueue() {
	// styles
	wp_enqueue_style( 'postbaradminStyle', PostbarShipping::plugin_url().'/assets/css/postbar-admin.css', array() , "2.2.2", "all" );
	wp_enqueue_style( 'postbarStyles', PostbarShipping::plugin_url().'/assets/css/postbar-shipping.css', array() , "2.2.2", "all" );
    
    //js
    wp_enqueue_script( 'postbarJS', PostbarShipping::plugin_url().'/assets/js/postbar-shipping.js', array('jquery'), "2.2.2", true );
}
add_action('admin_enqueue_scripts', 'postbar_shipping_admin_enqueue');

function postex_shop_order_admin_script() {
    global $post_type;
    if( $post_type == 'shop_order' )
    {
        wp_enqueue_script( 'postexShopOrderAdminScript', PostbarShipping::plugin_url().'/assets/js/postbar-shipping-shop-order.js', array('jquery'), "2.2.2", true );
    }
}
add_action( 'admin_print_scripts-post-new.php', 'postex_shop_order_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'postex_shop_order_admin_script', 11 );
/***** End: admin enqueue scripts and styles *****/

/***** public enqueue scripts and style *****/
function postbar_public_enqueue() {
    // styles
    wp_enqueue_style( 'postbarStyles', PostbarShipping::plugin_url().'/assets/css/postbar-shipping.css', array() , "2.2.2", "all" );
    
    //js
    wp_enqueue_script( 'postbarJS', PostbarShipping::plugin_url().'/assets/js/postbar-shipping.js', array('jquery'), "2.2.2", true );
}
add_action( 'wp_enqueue_scripts', 'postbar_public_enqueue' );
/***** public enqueue scripts and style *****/

/***** check payments availability *****/
function enabled_payments(){
    $is_COD_Enabled = false;
    $is_Online_Enabled = false;
    $available_payment_methods = WC()->payment_gateways->get_available_payment_gateways();
    foreach($available_payment_methods as $method_id => $method)
    {
        if($method_id == 'cod')
        {
            $is_COD_Enabled = true;
        }
        else
        {
            $is_Online_Enabled = true;
        }
    }
    $result = new stdClass();
    $result->bothEnabled =  ($is_COD_Enabled && $is_Online_Enabled) ? true : false;
    if($is_COD_Enabled && $is_Online_Enabled)
        $result->singleEnabled = '';
    else    
        $result->singleEnabled = $is_COD_Enabled ? 'cod' : 'online';
    return $result;
}
/***** End: check payments availability *****/

/***** Check is there a shipping condition *****/
function postex_isThereAShippingCondition($reciever_stateId, $reciever_townId, $cart_total_price){
    $check_result = new stdClass;
    $check_result->is_there_a_condition = false;
    $check_result->shipping_title = '';
    if( get_option('postbar_woo_shipping_opts')["conditionalShipping"] )
    {
        $shippingConditions = get_option('postbar_woo_shipping_opts')["shippingConditions"];
        if( is_array($shippingConditions) && count($shippingConditions) > 0 )
        {
            foreach($shippingConditions as $shippingCondition_json)
            {
                $shippingCondition_obj = json_decode($shippingCondition_json);
                $conditionType = $shippingCondition_obj->conditionType;
                if($conditionType == "receiverLocation")
                {                    
                    if( $reciever_stateId==$shippingCondition_obj->stateId && $reciever_townId==$shippingCondition_obj->townId )
                    {
                        $check_result->is_there_a_condition = true;
                        $check_result->shipping_title = $shippingCondition_obj->shippingTitle;
                    }
                }
                else if($conditionType == "cartTotalPrice")
                {
                    if( $cart_total_price >= $shippingCondition_obj->minPrice*1 )
                    {
                        $check_result->is_there_a_condition = true;
                        $check_result->shipping_title = $shippingCondition_obj->shippingTitle;
                    }
                }
            }
        }
    }
    return $check_result;
}
/***** End: Check is there a shipping condition *****/

/***** Inclide Functions *****/
include_once( PostbarShipping::plugin_path() . '/inc/postbar-api.php' );  
include_once( PostbarShipping::plugin_path() . '/inc/postbar-api-views.php' );
include_once( PostbarShipping::plugin_path() . '/inc/postbar-admin-settings.php' );
include_once( PostbarShipping::plugin_path() . '/inc/postbar-shipping-modal-selector.php' );
include_once( PostbarShipping::plugin_path() . '/inc/woo-methods.php' );
include_once( PostbarShipping::plugin_path() . '/inc/woo-methods-cart.php' );
include_once( PostbarShipping::plugin_path() . '/inc/woo-methods-checkout.php' );
include_once( PostbarShipping::plugin_path() . '/inc/new-order.php' );
include_once( PostbarShipping::plugin_path() . '/inc/order-tracking.php' );
/***** End: Include Functions *****/


