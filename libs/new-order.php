<?php
//print_r($allservice);
add_action( 'add_meta_boxes', 'postbar_new_and_tracking_order_metabox', 10, 2 );

function postbar_new_and_tracking_order_metabox( $post_type, $post )
{
			
    if ( "shop_order" != $post_type ) {
        return;
    }
    
    $order = wc_get_order( $post );
    if ( ! $order ) {
        return;
    }
    
    add_meta_box( 'postbar-order-tracking-information', 'ثبت و پیگیری حمل و نقل مرسوله (پستِکس)', 'postbar_new_and_tracking_order_metabox_HTML', 'shop_order', 'advanced', 'high' );
}

function postbar_new_and_tracking_order_metabox_HTML( $post )
{
    $order = wc_get_order( $post );
    $currency_code = $order->get_currency();
    $weight_unit = get_option('woocommerce_weight_unit');

    //فروشنده
    $Sender_FristName = get_option('postbar_woo_shipping_opts')["Sender_FristName"];
    $Sender_LastName = get_option('postbar_woo_shipping_opts')["Sender_LastName"];
    $Sender_mobile = get_option('postbar_woo_shipping_opts')["Sender_mobile"];
    $Sender_StateId = get_option('postbar_woo_shipping_opts')["Sender_StateId"];
    $Sender_townId = get_option('postbar_woo_shipping_opts')["Sender_townId"];
    $Sender_City = get_option('postbar_woo_shipping_opts')["Sender_City"];
    $Sender_PostCode = get_option('postbar_woo_shipping_opts')["Sender_PostCode"];
    $Sender_Address = get_option('postbar_woo_shipping_opts')["Sender_Address"];
    $Sender_Email = get_option('postbar_woo_shipping_opts')["Sender_Email"];
    $boxType = get_option('postbar_woo_shipping_opts')["boxType"];
    $SenderLat = get_option('postbar_woo_shipping_opts')["SenderLat"];
    $SenderLon = get_option('postbar_woo_shipping_opts')["SenderLon"];
    $paymenttype = get_option('postbar_woo_shipping_opts')["paymenttype"];
    $printlogo = get_option('postbar_woo_shipping_opts')["printlogo"];
    $printinvoice = get_option('postbar_woo_shipping_opts')["printinvoice"];
    $sendsms = get_option('postbar_woo_shipping_opts')["sendsms"];
    // خریدار
    $ServiceId = get_post_meta($order->get_id(), 'billing_postbar_UserSelected_ServiceId', true ); 
    $ServiceId = $ServiceId ? $ServiceId : '';
    $Reciver_FristName = $order->get_billing_first_name();
    $Reciver_LastName = $order->get_billing_last_name();
    $Reciver_mobile = $order->get_billing_phone();
    $receiver_ForeginCountry = 0;
    $receiver_ForeginCountryName = 'ایران';
    $Reciver_StateId = get_post_meta($order->get_id(), 'billing_postbar_reciever_stateId', true );
    $Reciver_townId = get_post_meta($order->get_id(), 'billing_postbar_reciever_townId', true );
    $Reciver_City = get_post_meta($order->get_id(), 'billing_postbar_reciever_city', true );
    $Reciver_PostCode = $order->get_billing_postcode();
    $Reciver_Address = $order->get_billing_address_1() . " " .$order->get_billing_address_2();
    $Reciver_Email = $order->get_billing_email();


    // GoodsType
    $order_products_name_arr = [];
    $Count = 0;
    foreach($order->get_items() as $item) 
    {
        $order_products_name_arr[] = $item['name'];
        $Count++;
    }
    $order_products_name_str = implode(" , " , $order_products_name_arr);

    // ApproximateValue
    $goodsTotalPrice = (float) $order->get_total() - (float) $order->get_total_tax() - (float) $order->get_total_shipping() - (float) $order->get_shipping_tax();
    
    $ApproximateValue = number_format( $goodsTotalPrice, wc_get_price_decimals(), '.', '' );
    if($currency_code == "IRT") { $ApproximateValue = $ApproximateValue * 10; }

    // CodGoodsPrice
    $CodGoodsPrice = $ApproximateValue;
    
    // Weight
    $Weight = get_post_meta( $post->ID, 'cart_weight', true );
    if($weight_unit == "kg") { $Weight = $Weight * 1000; }  
    
    // Dimentions
    $length = '';
    $width = '';
    $height = '';

    // IsCOD
    $payment_method = $order->get_payment_method();
    $COD_payment_selected = $payment_method == "cod" ? "selected" : ""; 
    $Non_COD_payment_selected = $payment_method != "cod" ? "selected" : ""; 
    $IsCOD = "
        <option value='true' $COD_payment_selected>بلی</option>
        <option value='false' $Non_COD_payment_selected>خیر</option>
    ";

    //حمل و نقل سنگین    
    $UbbraTruckType = ""; 
    $VechileOptions = "";
    $UbbarPackingLoad = "";
    $dispatch_date = "";

    // postbar orderId
    $postbar_orderId = get_post_meta( $order->get_id(), 'postbar_orderId', true );

    // رهگیری و وضعیت سفارش
    $postbarTrackingResult = is_numeric($postbar_orderId) ? PostexApi::orderTracking( $postbar_orderId ) : '';
    //print_r( $postbarTrackingResult );exit;
    if( is_object($postbarTrackingResult) )
    {
        if($postbarTrackingResult->success && isset($postbarTrackingResult->data->data))
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
        $shipmentStatus = "این سفارش هنوز برای باربری ارسال نشده است.";
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
                <div class="postex-mt-15">
                    <a class="postex-pdf-btn" href='<?php echo site_url(); ?>/wp-content/plugins/postbar-shipping/libs/orederInvoice.php?orderid=<?php echo $postbar_orderId; ?>&token=<?php echo PostexApi::getToken(); ?>' target='_blank'>
                        دریافت فاکتور
                    </a>
                </div>
            </div>
        </div>
    </div>
       

    <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
    <input type="hidden" id="primary_selected_ServiceId" value="<?php echo $ServiceId ?>" />
    <input type="hidden" id="postex_post_id" value="<?php echo $post->ID; ?>" />
    <input type="hidden" id="nonce_ajaxAdminPostbarServicesHTML" value="<?php echo wp_create_nonce( "nonce-ajaxAdminPostbarServicesHTML" ); ?>" />
    <input type="hidden" id="nonce_ajaxPostbarStateTownsHTML" value="<?php echo wp_create_nonce( "nonce-ajaxPostbarStateTownsHTML" ); ?>" />
    <input type="hidden" id="nonce_ajaxPostbarInsurancesHTML" value="<?php echo wp_create_nonce( "nonce-ajaxPostbarInsurancesHTML" ); ?>" />
    <input type="hidden" id="nonce_ajaxPostbarCartonSizeHTML" value="<?php echo wp_create_nonce( "nonce-ajaxPostbarCartonSizeHTML" ); ?>" />
    <input type="hidden" id="nonce_ajaxPostbarNewOrder" value="<?php echo wp_create_nonce( "nonce-ajaxPostbarNewOrder" ); ?>" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
        integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
        crossorigin=""></script>
    
    <div id="postex_order_details_container">
        <table class="pws-wide-form-table">
            <!-- sender -->
            <tr class="postbar-order-table-title">
                <th colspan="2">اطلاعات فروستنده</th>
            </tr>
            
            <tr>
                <th>
                    <label for="Sender_FristName">نام فرستنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_FristName" id="Sender_FristName" class="required" value="<?php echo $Sender_FristName ? $Sender_FristName : ""; ?>" placeholder="نام فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_LastName">نام خانوادگی فرستنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_LastName" id="Sender_LastName" class="required" value="<?php echo $Sender_LastName ? $Sender_LastName : ""; ?>" placeholder="نام خانوادگی فرستنده"  />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_mobile">موبایل فرستنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_mobile" id="Sender_mobile" class="required validate" data-validate="mobile" value="<?php echo $Sender_mobile ? $Sender_mobile : ""; ?>" placeholder="موبایل فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_Email">ایمیل فرستنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_Email" id="Sender_Email" class="required validate" data-validate="email" value="<?php echo $Sender_Email ? $Sender_Email : ""; ?>" placeholder="ایمیل فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_StateId">استان فرستنده*</label>                                
                </th>
                <td>
                    <select name="Sender_StateId" id="Sender_StateId" class="required">
                        <?php echo postbarStatesHTML($Sender_StateId); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_townId">شهرستان فرستنده*</label>                                
                </th>
                <td>
                    <select name="Sender_townId" id="Sender_townId" class="required">
                        <?php echo postbarStateTownsHTML($Sender_townId , $Sender_StateId); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_City">نام شهر/بخش/روستا فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_City" id="Sender_City" value="<?php echo $Sender_City ? $Sender_City : ""; ?>" placeholder="نام شهر/بخش/روستا فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_PostCode">کدپستی فرستنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_PostCode" id="Sender_PostCode" class="required" value="<?php echo $Sender_PostCode ? $Sender_PostCode : ""; ?>" placeholder="کدپستی فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_Address">آدرس پستی فرستنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Sender_Address" id="Sender_Address" class="required" value="<?php echo $Sender_Address ? $Sender_Address : ""; ?>" placeholder="آدرس پستی فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label>
                        موقعیت فرستنده روی نقشه*
                        <br />
                        <span class="lable-guide">محل جمع آوری (ارسال) مرسوله را با کلیک روی نقشه مشخص کنید.</span>
                    </label>                                
                </th>
                <td>
                    <div id="postexMapContainer"></div>
                    <input type="hidden" name="SenderLat" id="SenderLat" class="required" value="<?php echo $SenderLat ? $SenderLat : 35.78114; ?>" />
                    <input type="hidden" name="SenderLon" id="SenderLon" class="required" value="<?php echo $SenderLon ? $SenderLon : 51.417238; ?>" />
                </td>
            </tr>
            <script>
                jQuery(function($){

                    /***** Map *****/
                    var mapOptions = {
                        center: [$("#SenderLat").val(), $("#SenderLon").val()],
                        zoom: 16
                    }
                    
                    var postexMap = new L.map('postexMapContainer', mapOptions);
                    var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
                    postexMap.addLayer(layer);
                    var postexMapMarker = L.marker(mapOptions.center).addTo(postexMap);

                    postexMap.on('click', function(e){
                        var newLat = e.latlng.lat;
                        var newLng = e.latlng.lng;
                        postexMapMarker.setLatLng([newLat, newLng]);

                        $("#SenderLat").val(newLat);
                        $("#SenderLon").val(newLng);
                    });
                    /***** End: Map *****/

                }); // End jQuery;
            </script>
            
            <!-- End: sender -->
            <!-- Reciver -->
            <tr class="postbar-order-table-title">
                <th colspan="2">اطلاعات گیرنده</th>
            </tr>        
            <tr>
                <th>
                    <label for="Reciver_FristName">نام گیرنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_FristName" id="Reciver_FristName" class="required" value="<?php echo $Reciver_FristName; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_LastName">نام خانوادگی گیرنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_LastName" id="Reciver_LastName" class="required" value="<?php echo $Reciver_LastName; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_mobile">موبایل گیرنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_mobile" id="Reciver_mobile" class="required validate" data-validate="mobile" value="<?php echo $Reciver_mobile; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_StateId">استان گیرنده*</label>                                
                </th>
                <td>
                    <select name="Reciver_StateId" id="Reciver_StateId" class="required">
                        <?php echo postbarStatesHTML($Reciver_StateId); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_townId">شهرستان گیرنده*</label>                                
                </th>
                <td>
                    <select name="Reciver_townId" id="Reciver_townId" class="required">
                        <?php echo postbarStateTownsHTML($Reciver_townId , $Reciver_StateId); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_City">نام شهر/بخش/روستا گیرنده</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_City" id="Reciver_City" value="<?php echo $Reciver_City; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_PostCode">کدپستی گیرنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_PostCode" id="Reciver_PostCode" class="required" value="<?php echo $Reciver_PostCode; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_Address">آدرس پستی گیرنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_Address" id="Reciver_Address" class="required" value="<?php echo $Reciver_Address; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Reciver_Email">ایمیل گیرنده*</label>                                
                </th>
                <td>
                    <input type="text" name="Reciver_Email" id="Reciver_Email" class="required validate" data-validate="email" value="<?php echo $Reciver_Email; ?>" />
                </td>
            </tr>
            <!--End: Reciver -->
            <!--Goods -->
            <tr class="postbar-order-table-title">
                <th colspan="2">اطلاعات مرسوله</th>
            </tr>  
            <tr>
                <th>
                    <label for="GoodsType">محتويات بسته مرسوله*</label>                                
                </th>
                <td>
                    <input type="text" name="GoodsType" id="GoodsType" class="required" value="<?php echo $order_products_name_str;  ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Count">تعداد کالا*</label>                                
                </th>
                <td>
                    <input type="text" name="Count" id="Count" class="required validate" data-validate="number" value="<?php echo $Count;  ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ApproximateValue">ارزش تقریبی مرسوله (ریال)*</label>                                
                </th>
                <td>
                    <input type="text" name="ApproximateValue" id="ApproximateValue" class="required validate" data-validate="number" value="<?php echo $ApproximateValue;  ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="paymentType">نحوه پرداخت</label>                                
                </th>
                <td>
                    <select name="paymentType" id="paymentType">
                    <option value="cod" <?php echo $paymenttype=="cod" ? "selected" : ""; ?> >پس کرایه</option>
                    <option value="prepaid" <?php echo $paymenttype=="prepaid" ? "selected" : ""; ?> >پیش کرایه</option>
                    <option value="safe" <?php echo $paymenttype=="safe" ? "selected" : ""; ?> >پرداخت امن</option>
                    <option value="free" <?php echo $paymenttype=="free" ? "selected" : ""; ?> >پرداخت رایگان</option>
                    </select>
                </td>
            </tr>
            <!--<tr>
                <th>
                    <label for="IsCOD">آیا مرسوله پس کرایه میباشد؟</label>                                
                </th>
                <td>
                    <select name="IsCOD" id="IsCOD">
                        <?php echo $IsCOD; ?>
                    </select>
                </td>
            </tr>-->
            <tr id="CodGoodsPrice_row" <?php echo $paymenttype=="cod" ? 'style="display:table-row"' : ""; ?> >
                <th>
                    <label for="CodGoodsPrice">مبلغ كالا در مرسولات پس كرايه (ریال)</label>                                
                </th>
                <td>
                    <input type="text" name="CodGoodsPrice" id="CodGoodsPrice" value="<?php echo $order->get_total(); ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Weight">وزن به گرم*</label>                                
                </th>
                <td>
                    <input type="text" name="Weight" id="Weight" class="required validate" data-validate="number" value="<?php echo $Weight;  ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="length">طول - سانتیمتر</label>                                
                </th>
                <td>
                    <input type="text" name="length" id="length" value="<?php echo $length;  ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="width">عرض - سانتیمتر</label>                                
                </th>
                <td>
                    <input type="text" name="width" id="width" value="<?php echo $width;  ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="height">ارتفاع - سانتیمتر</label>                                
                </th>
                <td>
                    <input type="text" name="height" id="height" value="<?php echo $height;  ?>" />
                </td>
            </tr>
            <!--End: Goods -->
            <!-- Service Type -->
            <tr class="postbar-order-table-title">
                <th colspan="2">اطلاعات سرویس پستی</th>
            </tr>  
            <tr>
                <th>
                    <label for="ServiceId">نوع سرویس</label>                                
                </th>
                <td>
                    <div id='admin_services_content_container'>
                    <div class="shipping-row">
                        <div class="shipping-selection">
                            <input type="radio" name="ServiceId" value="<?php echo $ServiceId; ?>" checked="">
                        </div>
                        <div class="shipping-details">
                            <div class="details-item">
                                <span class="label">نام سرویس : </span>
                                <span class="value"><?php echo PostexApi::showService($ServiceId); ?></span>
                            </div>
                            
                        </div>
                    </div>
                    
                    </div>
                </td>
            </tr>        
            <tr>
                <th>
                    <label for="InsuranceId">نوع بیمه</label>                                
                </th>
                <td>
                    <select name="InsuranceId" id="InsuranceId">
                        <?php echo postbarInsurancesHTML('', $ServiceId); ?>
                    </select>
                    <input type="hidden" name="InsuranceName" id="InsuranceName" />
                </td>
            </tr>
            	
            <tr>
                <th>
                    <label for="CartonSizeId">سايز كارتون و لفاف بندی</label>                                
                </th>
                <td>
                    <select name="CartonSizeId" id="CartonSizeId">
                        <?php echo postbarCartonSizeHTML('', $ServiceId); ?>
                    </select>
                    <input type="hidden" name="CartonSizeName" id="CartonSizeName" />
                </td>
            </tr>
            <!-- End: Service Type -->
            <!-- Additional Information -->
            <tr class="postbar-order-table-title">
                <th colspan="2">سایر تنظیمات</th>
            </tr> 
            <tr>
                <th>
                    <label for="boxType">نوع بسته بندی</label>                                
                </th>
                <td>
                    <select name="boxType" id="boxType">
                        <option value="1" <?php echo $boxType=="1" ? "selected" : ""; ?> >بسته (کارتون، جعبه)</option>
                        <option value="0" <?php echo $boxType=="0" ? "selected" : ""; ?> >پاکت (اوراق، اسناد)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="HasAccessToPrinter">آيا امکان چاپ رسيد پستی داريد (دسترسی به پرينتر)؟</label>                                
                </th>
                <td>
                    <select name="HasAccessToPrinter" id="HasAccessToPrinter" >
                        <option value="true" <?php echo $printinvoice=="1" ? "selected" : ""; ?>>بلی</option>
                        <option value="false" <?php echo $printinvoice=="0" ? "selected" : ""; ?>>خیر</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="printLogo">
                        چاپ لوگوی تجاری در فاکتور (لولگو بايد در
                        پروفايل شما در سامانه پستِکس ثبت شده و تاييد
                        شده باشد)
                    </label>                                
                </th>
                <td>
                    <select name="printLogo" id="printLogo" >
                        <option value="false" <?php echo $printlogo=="0" ? "selected" : ""; ?>>خیر</option>
                        <option value="true" <?php echo $printlogo=="1" ? "selected" : ""; ?>>بلی</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="notifBySms">
                        اطلاع رسانی پيامکی در مراحل ارسال   
                    </label>                                
                </th>
                <td>
                    <select name="notifBySms" id="notifBySms" >
                        <option value="false" <?php echo $sendsms=="0" ? "selected" : ""; ?>>خیر</option>
                        <option value="true" <?php echo $sendsms=="1" ? "selected" : ""; ?>>بلی</option>
                    </select>
                </td>
            </tr>
            <!--<tr>
                <th>
                    <label for="AgentSaleAmount">ارزش افزوده نمایندگی</label>                                
                </th>
                <td>
                    <select name="AgentSaleAmount" id="AgentSaleAmount" >
                        <option value="0">خیر</option>
                        <option value="1">بلی</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="discountCouponCode">كد كوپن تخفيف</label>                                
                </th>
                <td>
                    <input type="text" name="discountCouponCode" id="discountCouponCode" />
                </td>
            </tr>-->
            <!-- End: Additional Information -->        
        </table>
    </div>

    <br />

    <?php 
        if(!is_numeric($postbar_orderId))
        { 
            $submit_btn_text = "ثبت و ارسال سفارش برای باربری";
            $submit_note_msg = "";
        } 
        else
        {
            $submit_btn_text = "ارسال مجدد";
            $submit_note_msg = "(برای ارسال مجدد لازم است قبلا درخواست لغو سفارش قبلی را به پشتیبانی پستکس ارسال کرده باشید. در غیر این صورت سفارشات تکراری ممکن است با مشکل مواجه شوند.)";
        }
    ?>
    <div id="postex_submit_order_container">
        <button id="submit_order_postbar" class="button button-primary"><?php echo $submit_btn_text; ?></button>
        <div id="submit_note_msg"><?php echo $submit_note_msg ?></div>
    </div>
    

    <div id="postbarNewOrderMessage"></div>
    <img id="postbar_shipping_loading" src="<?php echo PostexShipping::plugin_url().'/assets/images/loadspinner.gif' ?>" />

    <?php
}