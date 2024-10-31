<?php

class WC_Shipping_Postbar extends WC_Shipping_Method
{
    public function __construct() 
    {
        $this->id = 'postbar_shipping'; 
        $this->method_title = 'پستِکس';  
        $this->method_description = '<h3>حمل و نقل شرکت پستِکس برای ووکامرس</h3>'; 
        $this->enabled = $this->get_option( 'enabled' );
        $this->title = 'حمل و نقل پستِکس';
        $this->current_currency = get_woocommerce_currency(); // IRR or IRT or IRHT

        $this->init();
    }

    function init() 
    {
        $this->init_form_fields(); 
        $this->init_settings();

        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' 		=> 'فعال/غیر فعال',
                'type' 			=> 'checkbox',
                'label' 		=> 'فعال کردن این روش حمل و نقل',
                'default' 		=> 'no',
            ),
        );
    }

    public function calculate_shipping( $package = array() ) 
    {
        global $woocommerce;
        $shipping_total = 0;

        $shippingPriceCalcType = get_option('postbar_woo_shipping_opts')["shippingPriceCalcType"];
        $fixedShippingPriceValue = get_option('postbar_woo_shipping_opts')["fixedShippingPriceValue"];

        // انتخاب توسط مشتری
        if( $shippingPriceCalcType == "1" )
        {            
            $reciever_stateId = WC()->session->get( 'postbar_reciever_stateId', 0 );
            $reciever_townId = WC()->session->get( 'postbar_reciever_townId', 0 );
            $cart_total_price = floatval( preg_replace('#[^\d.]#', '', $woocommerce->cart->get_cart_total()) );
            $check_conditionalShipping_result = postex_isThereAShippingCondition($reciever_stateId, $reciever_townId, $cart_total_price);
            
            if( $check_conditionalShipping_result->is_there_a_condition )
            {
                $shipping_total = 0;
                $shipping_title = $check_conditionalShipping_result->shipping_title;
            }
            else
            {
                if(!WC()->session->get( 'postbar_UserSelected_ServiceId', 0 ))
                {
                    setDefaultServiceSession();
                }

                // shipping Price
                $postbar_UserSelected_ServicePrice = WC()->session->get( 'postbar_UserSelected_ServicePrice', 0 ) ? WC()->session->get( 'postbar_UserSelected_ServicePrice', 0 ) : $fixedShippingPriceValue;
                $postbar_UserSelected_ServiceTitle = WC()->session->get( 'postbar_UserSelected_ServiceTitle', 0 ) ? WC()->session->get( 'postbar_UserSelected_ServiceTitle', 0 ) : 'نرخ ثابت';
                
                $shipping_total = $postbar_UserSelected_ServicePrice;
                $shipping_title = $postbar_UserSelected_ServiceTitle;
            }          
            
        } // End if shippingPriceCalcType == "1"

        // حمل رایگان
        if( $shippingPriceCalcType == "2" )
        {
            $shipping_total = 0;
            $shipping_title = 'حمل و نقل رایگان';
        }

        // نرخ ثابت
        if( $shippingPriceCalcType == "3" )
        {
            $shipping_total = $fixedShippingPriceValue;
            $shipping_title = 'نرخ ثابت';
        }


        // convert currency to current selected currency
        if ( $this->current_currency == 'IRT' ) {
            $shipping_total = ceil ( $shipping_total / 10 );
        } elseif ( $this->current_currency == 'IRHT' ) {
            $shipping_total = ceil ( $shipping_total / 10000 );
        }            
        
        // Register the rate
        $rate = array(
            'id' => $this->id,
            'label' => $shipping_title,
            'cost' => $shipping_total,
            'calc_tax' => 'per_order'
        );
        $this->add_rate( $rate );                        
        
    }
}