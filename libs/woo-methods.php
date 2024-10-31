<?php


// set postbar session
add_action( 'wp_ajax_ajaxPostbarSetSessions', 'ajaxPostbarSetSessions' );
add_action( 'wp_ajax_nopriv_ajaxPostbarSetSessions', 'ajaxPostbarSetSessions' );
function ajaxPostbarSetSessions() 
{
    check_ajax_referer( 'nonce-ajaxPostbarSetSessions', 'security' );
    
    $postbar_reciever_stateId = $_POST["postbar_reciever_stateId"] ? intval($_POST["postbar_reciever_stateId"]) : get_option('postbar_woo_shipping_opts')["reciver_default_stateId"];
    $postbar_reciever_stateTitle = $_POST["postbar_reciever_stateTitle"] ? wp_kses($_POST["postbar_reciever_stateTitle"] , array()) : get_option('postbar_woo_shipping_opts')["reciver_default_stateTitle"];
    $postbar_reciever_townId = $_POST["postbar_reciever_townId"] ? intval($_POST["postbar_reciever_townId"]) : get_option('postbar_woo_shipping_opts')["reciver_default_townId"];    
    $postbar_reciever_townTitle = $_POST["postbar_reciever_townTitle"] ? wp_kses($_POST["postbar_reciever_townTitle"] , array()) : get_option('postbar_woo_shipping_opts')["reciver_default_townTitle"];    
    $postbar_reciever_listType = get_option('postbar_woo_shipping_opts')["ListTypeSelectable"] ? intval($_POST["postbar_reciever_listType"]) : get_option('postbar_woo_shipping_opts')["ListType"];
    $postbar_UserSelected_ServiceId = $_POST["postbar_UserSelected_ServiceId"] ? intval($_POST["postbar_UserSelected_ServiceId"]) : '';
    $postbar_UserSelected_ServicePrice = $_POST["postbar_UserSelected_ServicePrice"] ? intval($_POST["postbar_UserSelected_ServicePrice"]) : get_option('postbar_woo_shipping_opts')["fixedShippingPriceValue"];
    $postbar_UserSelected_ServiceTitle = $_POST["postbar_UserSelected_ServiceTitle"] ? wp_kses($_POST["postbar_UserSelected_ServiceTitle"] , array()) : 'نرخ ثابت';
    $postbar_IsCOD = $_POST["postbar_IsCOD"] ? wp_kses($_POST["postbar_IsCOD"] , array()) : '';
    
    WC()->session->set("postbar_reciever_stateId", $postbar_reciever_stateId );
    WC()->session->set("postbar_reciever_stateTitle", $postbar_reciever_stateTitle );
    WC()->session->set("postbar_reciever_townId", $postbar_reciever_townId );
    WC()->session->set("postbar_reciever_townTitle", $postbar_reciever_townTitle );
    WC()->session->set("postbar_reciever_listType", $postbar_reciever_listType );
    WC()->session->set("postbar_UserSelected_ServiceId", $postbar_UserSelected_ServiceId );
    WC()->session->set("postbar_UserSelected_ServicePrice", $postbar_UserSelected_ServicePrice );
    WC()->session->set("postbar_UserSelected_ServiceTitle", $postbar_UserSelected_ServiceTitle );
    WC()->session->set("postbar_IsCOD", $postbar_IsCOD );

    die();
}

// Check Conditional Shipping
add_action( 'wp_ajax_ajaxCheckConditionalShipping', 'ajaxCheckConditionalShipping' );
add_action( 'wp_ajax_nopriv_ajaxCheckConditionalShipping', 'ajaxCheckConditionalShipping' );
function ajaxCheckConditionalShipping()
{
    check_ajax_referer( 'nonce-ajaxCheckConditionalShipping', 'security' );

    global $woocommerce; 

    $reciever_stateId = $_POST["postbar_reciever_stateId"] ? intval($_POST["postbar_reciever_stateId"]) : get_option('postbar_woo_shipping_opts')["reciver_default_stateId"];
    $reciever_townId = $_POST["postbar_reciever_townId"] ? intval($_POST["postbar_reciever_townId"]) : get_option('postbar_woo_shipping_opts')["reciver_default_townId"];  
    $cart_total_price = floatval( preg_replace('#[^\d.]#', '', $woocommerce->cart->get_cart_total()) );

    $check_conditionalShipping_result = postex_isThereAShippingCondition($reciever_stateId, $reciever_townId, $cart_total_price);

    if( $check_conditionalShipping_result->is_there_a_condition )
        echo "true";
    else    
        echo "false";

    die();
}


// Store cart weight in the database
add_action('woocommerce_checkout_update_order_meta', 'postbar_add_cart_weight');
function postbar_add_cart_weight( $order_id )
{
    global $woocommerce;
    
    $weight = $woocommerce->cart->cart_contents_weight;
    update_post_meta( $order_id, 'cart_weight', $weight );
}


// Just use postbar shipping method
function just_postbar_shipping_method( $methods ) 
{
	$filtered_methods = array();
    foreach( $methods as $method_id => $method )
    {
        if( 'postbar_shipping' === $method->id )
        {
			$filtered_methods[ $method_id ] = $method;
			break;
		}
	}
    return !empty( $filtered_methods ) ? $filtered_methods : $methods;
}
add_filter( 'woocommerce_package_rates', 'just_postbar_shipping_method', 100 );


// override 'shipping debug' and 'shipping destination'
add_action( 'admin_init', 'postbar_override_shipping_debug');
function postbar_override_shipping_debug(){
    update_option('woocommerce_shipping_debug_mode','yes');
    update_option('woocommerce_ship_to_destination','billing_only');
}


// dont show debug mode message
add_filter( 'woocommerce_add_message', 'postbar_filtered_woo_messages' );
function postbar_filtered_woo_messages( $message ) {
    if(strpos($message, 'Customer matched zone') !== false)
    {
        return false;
    }  
}