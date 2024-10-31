<?php


// checkout fields
add_filter( 'woocommerce_checkout_fields' , 'postbar_manage_woo_checkout_fields' );
function postbar_manage_woo_checkout_fields( $fields )
{
    // billing fields
    unset($fields['billing']['billing_country']);

    $default_stateId = WC()->session->get( 'postbar_reciever_stateId', 0 ) ? WC()->session->get( 'postbar_reciever_stateId', 0 ) : get_option('postbar_woo_shipping_opts')["reciver_default_stateId"];
    $default_townId = WC()->session->get( 'postbar_reciever_townId', 0 ) ? WC()->session->get( 'postbar_reciever_townId', 0 ) : get_option('postbar_woo_shipping_opts')["reciver_default_townId"];
    
    $fields['billing']['billing_postbar_reciever_stateId'] = array(   
        'type'     => 'select',
        'class'    => array( 'form-row-first', 'wps-drop', 'postex-textlike-select' ),
        'label'    => 'استان',
        'options'  => postbarWooStateOptions(true), // isDisabled = true, false
        'required' => true,
        'default'  => $default_stateId,
        'custom_attributes' => array( 'disabled' => 'disabled' )
    );
    
    $fields['billing']['billing_postbar_reciever_townId'] = array(   
        'type'     => 'select',
        'class'    => array( 'form-row-last', 'wps-drop', 'postex-textlike-select' ),
        'label'    => 'شهرستان',
        'options'  => postbarWooTownOptions(true, $default_stateId), // isDisabled = true, false
        'required' => true,
        'default'  => $default_townId,
        'custom_attributes' => array( 'disabled' => 'disabled' )
    );
    
    $fields['billing']['postex_shipping_open_modal'] = array(
        'type'     => 'checkbox',
        'class'    => array( 'form-row-wide', 'postex-checkout-change-statecity', 'postex-shipping-open-modal' ),
        'label'     => 'انتخاب شهر و سرویس پستی',
        'required'  => false
    );
    
    $fields['billing']['billing_postbar_reciever_city'] = array(   
        'type'     => 'text',
        'class'    => array( 'form-row-wide' ),
        'label'     => 'نام شهر/بخش/روستا',
        'placeholder'   => '',
        'required'  => false
    );

    $fields['billing']['billing_state'] = array(   
        'type'     => 'text',
        'class'    => array( 'postex-hide-checkout-field' ),
        'label'     => 'استان',
        'placeholder'   => '',
        'priority'   => 107,
        'required'  => false
    );

    $fields['billing']['billing_city'] = array(   
        'type'     => 'text',
        'class'    => array( 'postex-hide-checkout-field' ),
        'label'     => 'شهرستان',
        'placeholder'   => '',
        'priority'   => 108,
        'required'  => false
    );

    $fields['billing']['billing_address_1']['label'] = 'آدرس';
    $fields['billing']['billing_address_2']['label'] = 'آدرس - قسمت دوم';
    $fields['billing']['billing_address_2']['placeholder'] = 'آدرس - قسمت دوم';
    $fields['billing']['billing_phone']['label'] = 'تلفن همراه';

    $fields['billing']['billing_phone']['priority'] = 98;
    $fields['billing']['billing_email']['priority'] = 99;
    $fields['billing']['billing_postbar_reciever_stateId']['priority'] = 100;
    $fields['billing']['billing_postbar_reciever_townId']['priority'] = 101;
    $fields['billing']['postex_shipping_open_modal']['priority'] = 102;
    $fields['billing']['billing_postbar_reciever_city']['priority'] = 103;
    $fields['billing']['billing_address_1']['priority'] = 104;
    $fields['billing']['billing_address_2']['priority'] = 105;
    $fields['billing']['billing_postcode']['priority'] = 106;

    // shipping fields
    unset($fields['shipping']['shipping_country']);
    $fields['shipping']['shipping_state'] = array(   
        'required'  => false
    );
    $fields['shipping']['shipping_city'] = array(   
        'required'  => false
    );
    
    return $fields;
}


// after order notes in checkout page
add_action('woocommerce_after_order_notes', 'postbar_after_checkout_fields');
function postbar_after_checkout_fields($checkout)
{    
    $billing_postbar_reciever_stateId = WC()->session->get( 'postbar_reciever_stateId', 0 ) ? WC()->session->get( 'postbar_reciever_stateId', 0 ) : get_option('postbar_woo_shipping_opts')["reciver_default_stateId"];
    $billing_postbar_reciever_townId = WC()->session->get( 'postbar_reciever_townId', 0 ) ? WC()->session->get( 'postbar_reciever_townId', 0 ) : get_option('postbar_woo_shipping_opts')["reciver_default_townId"];
    $billing_postbar_UserSelected_ServiceId = WC()->session->get( 'postbar_UserSelected_ServiceId', 0 ) ? WC()->session->get( 'postbar_UserSelected_ServiceId', 0 ) : '';

    ?>
    <input type='hidden' name='billing_postbar_reciever_stateId' value='<?php echo $billing_postbar_reciever_stateId; ?>' />
    <input type='hidden' name='billing_postbar_reciever_townId' value='<?php echo $billing_postbar_reciever_townId; ?>' />
    <input type='hidden' name='billing_postbar_UserSelected_ServiceId' value='<?php echo $billing_postbar_UserSelected_ServiceId; ?>' />
    <?php
}


// Checkout check required fields
add_action('woocommerce_checkout_process', 'postbar_checkout_field_process');
function postbar_checkout_field_process()
{
    // check state and town
    $billing_postbar_reciever_stateId = $_POST['billing_postbar_reciever_stateId'] ? intval($_POST['billing_postbar_reciever_stateId']) : 0;
    $billing_postbar_reciever_townId = $_POST['billing_postbar_reciever_townId'] ? intval($_POST['billing_postbar_reciever_townId']) : 0;
    if (!$billing_postbar_reciever_stateId) wc_add_notice(__('انتخاب استان الزامی است.') , 'error');
    if (!$billing_postbar_reciever_townId) wc_add_notice(__('انتخاب شهرستان الزامی است.') , 'error');

    // check mobile format
    if ( !(preg_match('/(0|\+98)?([ ]|-|[()]){0,2}9[0|1|2|3|4|9]([ ]|-|[()]){0,3}(?:[0-9]([ ]|-|[()]){0,2}){8}/D', $_POST['billing_phone'])) )
    {
        wc_add_notice( "شماره تلفن همراه وارد شده نامعتبر است."  ,'error' );
    }

    // Check the coordination of 'postex selected payment' and 'woo selected payment'
    $shippingPriceCalcType = get_option('postbar_woo_shipping_opts')["shippingPriceCalcType"];

    $is_there_a_condition = false;
    if($shippingPriceCalcType == "1")
    {
        global $woocommerce;
        $cart_total_price = floatval( preg_replace('#[^\d.]#', '', $woocommerce->cart->get_cart_total()) );
        $check_conditionalShipping_result = postex_isThereAShippingCondition($billing_postbar_reciever_stateId, $billing_postbar_reciever_townId, $cart_total_price);
        $is_there_a_condition = $check_conditionalShipping_result->is_there_a_condition;
    }    

    if( enabled_payments()->bothEnabled && $shippingPriceCalcType == "1" && !$is_there_a_condition )
    {
        $is_COD = WC()->session->get( 'postbar_IsCOD', 0 ) ? WC()->session->get( 'postbar_IsCOD', 0 ) : 'false';
        $postex_selected_payment_method = $is_COD == 'true' ? 'cod' : 'online';
        $woo_selected_payment_method = $_POST["payment_method"] == 'cod' ? 'cod' : 'online';
        if($postex_selected_payment_method != $woo_selected_payment_method)
        {
            $cod_txt = "پس کرایه";
            $online_txt = "پرداخت آنلاین";
            $postex_selected_payment_method_txt = $is_COD == 'true' ? $cod_txt : $online_txt;
            $woo_selected_payment_method_txt = $_POST["payment_method"] == 'cod' ? $cod_txt : $online_txt;
            $paymntErrMsg = 'تفاوت در سرویس حمل و نقل و روش پرداخت انتخابی. سرویس حمل و نقل : ';
            $paymntErrMsg = $paymntErrMsg . $postex_selected_payment_method_txt;
            $paymntErrMsg = $paymntErrMsg . " ، روش پرداخت : ";
            $paymntErrMsg = $paymntErrMsg . $woo_selected_payment_method_txt;
            wc_add_notice($paymntErrMsg , 'error');
        }
    }
    
}


// Checkout Update value of fields
add_action('woocommerce_checkout_update_order_meta', 'postbar_checkout_field_update_order_meta');
function postbar_checkout_field_update_order_meta($order_id)
{
    $billing_postbar_reciever_stateId = $_POST['billing_postbar_reciever_stateId'] ? intval($_POST['billing_postbar_reciever_stateId']) : 0;
    $billing_postbar_reciever_townId = $_POST['billing_postbar_reciever_townId'] ? intval($_POST['billing_postbar_reciever_townId']) : 0;
    $billing_postbar_reciever_city = wp_kses($_POST['billing_postbar_reciever_city'] , array());
    $billing_postbar_UserSelected_ServiceId = $_POST['billing_postbar_UserSelected_ServiceId'] ? intval($_POST['billing_postbar_UserSelected_ServiceId']) : 0;
    
    if (!empty($billing_postbar_reciever_stateId)) {
        update_post_meta($order_id, 'billing_postbar_reciever_stateId', $billing_postbar_reciever_stateId);
    }
    if (!empty($billing_postbar_reciever_townId)) {
        update_post_meta($order_id, 'billing_postbar_reciever_townId', $billing_postbar_reciever_townId);
    }
    if (!empty($billing_postbar_reciever_city)) {
        update_post_meta($order_id, 'billing_postbar_reciever_city', $billing_postbar_reciever_city);
    }
    if (!empty($billing_postbar_UserSelected_ServiceId)) {
        update_post_meta($order_id, 'billing_postbar_UserSelected_ServiceId', $billing_postbar_UserSelected_ServiceId);
    }
}