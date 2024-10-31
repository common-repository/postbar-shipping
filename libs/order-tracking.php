<?php

// Customer Order Details 
add_action( 'woocommerce_order_items_table', 'postbar_woocommerce_order_items_table', 10, 1 ); 

function postbar_woocommerce_order_items_table( $order ) 
{
    $woo_order_id = $order->get_id();
    $postbar_orderId = get_post_meta( $woo_order_id, 'postbar_orderId', true );
    $postbarTrackingResult = is_numeric($postbar_orderId) ? PostexApi::orderTracking( $postbar_orderId ) : '';
    if( is_object($postbarTrackingResult) )
    {
        if($postbarTrackingResult->success && $postbarTrackingResult->data->data)
        {
            $shipmentStatus = $postbarTrackingResult->data->data[0]->shipmentStatus;
            $TrackingNumber = $postbarTrackingResult->data->data[0]->TrackingNumber;
        }
        else
        {
            $shipmentStatus = $postbarTrackingResult->data->message;
            $TrackingNumber = "-";
        }
    }
    else
    {
        $shipmentStatus = "سفارش شما هنوز در سامانه باربری ثبت نشده است و در حال بررسی جهت ارسال به باربری می‌باشد.";
        $postbar_orderId = "-";
        $TrackingNumber = "-";
    }
    ?>

    <div id='postbar_order_traching'>
        <div id="pshot-header">
            <h5>وضعیت حمل و نقل مرسوله</h5>
        </div>
        <div id="pshot-body">
            <div class="img-container"></div>
            <div>
                <div class="pshot-info-row">
                    <b>آخرین وضعیت :</b>  
                    <span><?php echo $shipmentStatus; ?></span>
                </div>
                <div class="pshot-info-row">
                    <b>شناسه سفارش : </b>  
                    <span><?php echo $postbar_orderId; ?></span>                   
                </div>  
                <div class="pshot-info-row">
                    <b>کد رهگیری پستی :</b>  
                    <span><?php echo $TrackingNumber; ?></span> 
                </div>
                <div class="pshot-info-row">
                    <a href="https://postex.ir" target="_blank">رهگیری مرسوله</a>
                    در سایت باربری
                </div>
            </div>
        </div>
    </div>

    <?php

}