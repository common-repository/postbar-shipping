<?php

// shipping-calculator template
function postbar_locate_template( $template, $template_name, $template_path ) 
{
    $basename = basename( $template );
    if( $basename == 'shipping-calculator.php' )
    {
        $template = PostbarShipping::plugin_path() . '/templates/shipping-calculator.php';
    }
    return $template;
}
add_filter( 'woocommerce_locate_template', 'postbar_locate_template', 10, 3 );


// shipping method label
function postex_shipping_method_label($label, $method){
    $stateTitle = WC()->session->get( 'postbar_reciever_stateTitle', 0 ) ? WC()->session->get( 'postbar_reciever_stateTitle', 0 ) : get_option('postbar_woo_shipping_opts')["reciver_default_stateTitle"];
    $townTitle = WC()->session->get( 'postbar_reciever_townTitle', 0 ) ? WC()->session->get( 'postbar_reciever_townTitle', 0 ) : get_option('postbar_woo_shipping_opts')["reciver_default_townTitle"];
    $destination = "<div>ارسال به $stateTitle - $townTitle </div>";
    return $destination . $label;
}
add_filter( 'woocommerce_cart_shipping_method_full_label', 'postex_shipping_method_label', 10, 2 );