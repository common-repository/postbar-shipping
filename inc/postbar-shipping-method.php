<?php

require_once('postbar-shipping-method-class.php');

add_action('admin_menu', 'register_postbar_shipping_menu');

function register_postbar_shipping_menu() 
{
    add_submenu_page(
    	'admin.php?page=wc-settings&tab=shipping',
    	'حمل و نقل پستِکس',
    	'حمل و نقل پستِکس',
    	'manage_options',
    	'admin.php?page=wc-settings&tab=shipping&section=postbar_shipping',
    	''
    );
}

function postbar_shipping( $methods ) {
	$methods[] = 'WC_Shipping_Postbar';
	return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'postbar_shipping' );