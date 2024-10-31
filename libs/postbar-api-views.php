<?php

// STATE :

/***** Get States HTML *****/
function postbarStatesHTML($selected_StateId = 1)
{
    $states = PostexApi::getState();
    $statesHTML = "";
    if ($states->success) {
        foreach ($states->data as $state) {
            $selected = $selected_StateId == $state->stateId ? "selected" : "";
            $statesHTML .= "<option value='" . $state->stateId . "' $selected>" . $state->stateName . "</option>";
        }
    }

    return $statesHTML;
}
/***** End: Get States HTML *****/

/***** Woo State Options arrray *****/
function postbarWooStateOptions($isDisabled)
{
    $states = PostexApi::getState();
    if ($states->success) {
        foreach ($states->data as $state) {
            if ($isDisabled) {
                if ($state->stateId == WC()->session->get('postbar_reciever_stateId', 0))
                    $state_options[$state->stateId] = $state->stateName;
            } else {
                $state_options[$state->stateId] = $state->stateName;
            }
        }
    } else {
        $state_options = array(
            ''    => 'عدم ارتباط با سامانه باربری'
        );
    }

    return $state_options;
}
/***** End: Woo State Options arrray *****/



// TOWN :

/***** Get Towns of State HTML *****/
function postbarStateTownsHTML($selected_townId = '', $stateId = '')
{
    if (!$stateId) {
        $stateId = 1;
    }
    $towns = PostexApi::getTownsByStateId($stateId);
    $townsHTML = "";
    if ($towns->success) {
        foreach ($towns->data as $town) {
            $selected = $selected_townId == $town->townId ? "selected" : "";
            $townsHTML .= "<option value='" . $town->townId . "' $selected>" . $town->townName . "</option>";
        }
    }

    return $townsHTML;
}
/***** End: Get Towns of State HTML *****/

/***** Ajax Get Towns Of State *****/
add_action('wp_ajax_ajaxPostbarStateTownsHTML', 'ajaxPostbarStateTownsHTML');
add_action('wp_ajax_nopriv_ajaxPostbarStateTownsHTML', 'ajaxPostbarStateTownsHTML');
function ajaxPostbarStateTownsHTML()
{
    check_ajax_referer('nonce-ajaxPostbarStateTownsHTML', 'security');
    $stateId = $_POST['stateId'] ? intval($_POST['stateId']) : '';
    $townsHTML = postbarStateTownsHTML('', $stateId);

    echo $townsHTML;

    die();
}
/***** End: Ajax Get Towns Of State *****/

/***** Woo Town Options arrray *****/
function postbarWooTownOptions($isDisabled, $stateId)
{
    $towns = PostexApi::getTownsByStateId($stateId);
    if ($towns->success) {
        foreach ($towns->data as $town) {
            if ($isDisabled) {
                if ($town->townId == WC()->session->get('postbar_reciever_townId', 0))
                    $town_options[$town->townId] = $town->townName;
            } else {
                $town_options[$town->townId] = $town->townName;
            }
        }
    } else {
        $town_options = array(
            ''    => 'عدم ارتباط با سامانه باربری'
        );
    }

    return $town_options;
}
/***** End: Woo Town Options arrray *****/



// SERVICES : 

/***** Get Services HTML *****/
function postbarServicesHTML($receiverStateId, $receiverTownId, $listType, $selected_ServiceId, $is_COD)
{
    // Sender    
    $senderStateId = get_option('postbar_woo_shipping_opts')["Sender_StateId"] ? get_option('postbar_woo_shipping_opts')["Sender_StateId"] : 1;
    $senderTownId = get_option('postbar_woo_shipping_opts')["Sender_townId"] ? get_option('postbar_woo_shipping_opts')["Sender_townId"] : 779;
    $boxType = get_option('postbar_woo_shipping_opts')["boxType"] ? get_option('postbar_woo_shipping_opts')["boxType"] : "1";

    // wieght
    $weight_unit = get_option('woocommerce_weight_unit');
    $cart_weight = WC()->cart->cart_contents_weight;
    if ($weight_unit == 'kg') {
        $cart_weight = $cart_weight * 1000;
    }
    if ($cart_weight == 0) {
        $cart_weight = 100;
    }

    // AproximateValue
    $current_currency = get_woocommerce_currency();
    $AproximateValue = WC()->cart->subtotal;
    if ($current_currency == 'IRT') {
        $AproximateValue = $AproximateValue * 10;
    } elseif ($current_currency == 'IRHT') {
        $AproximateValue = $AproximateValue * 10000;
    }

    $data_array = array(
        //فروشنده
        'ListType' => $listType, //req 0=all, 1=Fastest, 2=cheapest
        'senderStateId' => $senderStateId, //req 1=تهران
        'senderCityId' => $senderTownId, //req 779=تجریش
        'boxType' => $boxType,  // 0 = pakat , 1 = baste
        "insuranceName" => '* انتخاب بیمه ضروری است *',
        "CartonSizeName" => "سایز 4(20*20*30)",
        //خریدار
        'receiver_ForeginCountry' => 0,  // اجباری در پست خارجی
        'receiverStateId' => $receiverStateId, //req expt external
        'receiverCityId' => $receiverTownId, //req expt external

        //کالا
        'weight' => ($cart_weight < 100) ? 100 : $cart_weight, //req گرم
        'goodsValue' => $AproximateValue, // ریال
        'height' => 10, // req if external
        'width' => 10, // req if external
        'length' => 10, // req if external
        'Content' => '',
        'needCartoon' => 0,

        //حمل و نقل سنگین
        'dispatch_date' => '', //req if heavy تاریخ و ساعت بارگیری
        'TruckType' => '', //req if heavy نوع خودرو
        'VechileOptions' => '', //req if heavy ویژگی خودرو
        'PackingLoad' => '', //req if heavy نوع بسته بندی بار    

        'serviceId' => 0,
        'IsCOD' => $is_COD
    );
    // print_r($data_array);exit;
    $Services = PostexApi::getServices($data_array);
    
    /*echo "**********";
    */
    // print_r($Services);exit;
    $sid = json_decode($Services->data['body']);
    $ServicesHTML = "";

    if ($Services->success && is_array($sid->ServicePrices)) {
        if (count($sid->ServicePrices) > 0) {
            // Set Default Service
            $has_matchable_service = false;
            foreach ($sid->ServicePrices as $Service) {
                if ($Service->ServiceId == $selected_ServiceId) {
                    $has_matchable_service = true;
                }
            }
            if (!$has_matchable_service) {
                $selected_ServiceId = $sid->ServicePrices[0]->ServiceId;
                if (!WC()->session->get('postbar_UserSelected_ServiceId', 0)) {
                    WC()->session->set("postbar_UserSelected_ServiceId", $sid->ServicePrices[0]->ServiceId);
                    WC()->session->set("postbar_UserSelected_ServicePrice", $sid->ServicePrices[0]->TotalPriceIncludeTax);
                    WC()->session->set("postbar_UserSelected_ServiceTitle", $sid->ServicePrices[0]->ServiceName);
                }
            }

            foreach ($sid->ServicePrices as $Service) {
                $selected = $selected_ServiceId == $Service->ServiceId ? "checked" : "";
                $ServicesHTML .= "
                    <div class='shipping-row'>
                        <div class='shipping-selection'>
                            <input type='radio' name='postbar_UserSelected_ServiceId' 
                                    value='$Service->ServiceId' 
                                    data-price='$Service->TotalPriceIncludeTax' 
                                    data-title='$Service->ServiceName' 
                                    $selected />
                        </div>
                        <div class='shipping-details'>
                            <div class='details-item'>
                                <span class='label'>نام سرویس : </span>
                                <span class='value'>$Service->ServiceName</span>
                            </div>
                            <div class='details-item'>
                                <span class='label'>مدت زمان ارسال (روز) : </span>
                                <span class='value'>$Service->SLA</span>
                            </div>
                            <div class='details-item'>
                                <span class='label'>قیمت (ریال) : </span>
                                <span class='value'>$Service->TotalPriceIncludeTax</span>
                            </div>
                        </div>
                    </div>
                ";
            }
        } else {
            $ServicesHTML = '<div>با توجه با مقادیر ورودی شما، سرویسی یافت نشد.</div>';
        }
    } else {
        $ServicesHTML = '<div>مشکل در برقراری ارتباط با سامانه باربری، لطفا بعدا مجدد تلاش کنید.</div>';
    }

    return $ServicesHTML;
}
/***** End: Get Services HTML *****/

/***** Ajax Get Services HTML *****/
add_action('wp_ajax_ajaxPostbarServicesHTML', 'ajaxPostbarServicesHTML');
add_action('wp_ajax_nopriv_ajaxPostbarServicesHTML', 'ajaxPostbarServicesHTML');
function ajaxPostbarServicesHTML()
{
    check_ajax_referer('nonce-ajaxPostbarServicesHTML', 'security');

    $postbar_reciever_stateId = $_POST["postbar_reciever_stateId"] ? intval($_POST["postbar_reciever_stateId"]) : get_option('postbar_woo_shipping_opts')["reciver_default_stateId"];
    $postbar_reciever_townId = $_POST["postbar_reciever_townId"] ? intval($_POST["postbar_reciever_townId"]) : get_option('postbar_woo_shipping_opts')["reciver_default_townId"];
    $postbar_reciever_listType = get_option('postbar_woo_shipping_opts')["ListTypeSelectable"] ? intval($_POST["postbar_reciever_listType"]) : get_option('postbar_woo_shipping_opts')["ListType"];
    $postbar_UserSelected_ServiceId = $_POST["postbar_UserSelected_ServiceId"] ? intval($_POST["postbar_UserSelected_ServiceId"]) : '';
    if ($_POST["postbar_IsCOD"]) {
        $postbar_IsCOD =  wp_kses($_POST["postbar_IsCOD"], array());
    } else {
        $postbar_IsCOD =  enabled_payments()->singleEnabled === 'cod' ? 'true' : 'false';
    }

    $servicesHTML = postbarServicesHTML($postbar_reciever_stateId, $postbar_reciever_townId, $postbar_reciever_listType, $postbar_UserSelected_ServiceId, $postbar_IsCOD);

    echo $servicesHTML;

    die();
}
/***** End: Ajax Get Services HTML *****/

/***** Admin Services Html *****/
add_action('wp_ajax_ajaxAdminPostbarServicesHTML', 'ajaxAdminPostbarServicesHTML');
add_action('wp_ajax_nopriv_ajaxAdminPostbarServicesHTML', 'ajaxAdminPostbarServicesHTML');
function ajaxAdminPostbarServicesHTML()
{
    check_ajax_referer('nonce-ajaxAdminPostbarServicesHTML', 'security');

    $selected_ServiceId = intval($_POST["selected_ServiceId"]);
    $data_array = array(

        //فروشنده
        /*  'ListType' => intval($_POST["ListType"]),
        'senderStateId' => intval($_POST["senderStateId"]),
        'senderTownId' => intval($_POST["senderTownId"]),
        'boxType' => intval($_POST["boxType"]),
    
        //خریدار
        'receiver_ForeginCountry' => intval($_POST["receiver_ForeginCountry"]),
        'receiverStateId' => intval($_POST["receiverStateId"]),
        'receiverTownId' => intval($_POST["receiverTownId"]),
    
        //کالا
        'weightItem' => intval($_POST["weightItem"]),
        'AproximateValue' => intval($_POST["AproximateValue"]),
        'height' => $_POST["height"] ? intval($_POST["height"]) : 0,
        'width' => $_POST["width"] ? intval($_POST["width"]) : 0,
        'length' => $_POST["length"] ? intval($_POST["length"]) : 0,
        'Content' => wp_kses($_POST["Content"] , array()),*/
        "serviceId" => !empty($selected_ServiceId) ? $selected_ServiceId : 0,
        "insuranceName" => $_POST['insurance'],
        "CartonSizeName" => $_POST['cartonsize'],
        "senderCityId" => intval($_POST["senderTownId"]),
        "receiverCityId" => intval($_POST["receiverTownId"]),
        "goodsValue" => intval($_POST["AproximateValue"]),
        "printBill" => 0,
        "printLogo" => 0,
        "needCartoon" => 0,
        "isCod" => wp_kses($_POST["IsCOD"], array()),
        "sendSms" => true,
        "weight" => intval($_POST["weightItem"])
        //حمل و نقل سنگین
        // 'dispatch_date' => '',
        // 'TruckType' => '',
        // 'VechileOptions' => '',
        // 'PackingLoad' => '',

        // 'serviceId' => isset($selected_ServiceId)?$selected_ServiceId:'',
        // 'IsCOD' => wp_kses($_POST["IsCOD"] , array())
    );
    //print_r($data_array);exit;
    $Services = PostexApi::getServices($data_array);
    //print_r($Services);exit;
    $sid = json_decode($Services->data['body']);
    //print_r($sid);exit;
    $ServicesHTML = '';
    if ($Services->success && is_array($sid->ServicePrices)) {
        if (count($sid->ServicePrices) > 0) {
            foreach ($sid->ServicePrices as $Service) {
                $selected = $selected_ServiceId == $Service->ServiceId ? "checked" : "";
                $ServicesHTML .= "
                    <div class='shipping-row'>
                        <div class='shipping-selection'>
                            <input type='radio' name='ServiceId' 
                                    value='$Service->ServiceId' 
                                    $selected />
                        </div>
                        <div class='shipping-details'>
                            <div class='details-item'>
                                <span class='label'>نام سرویس : </span>
                                <span class='value'>$Service->ServiceName</span>
                            </div>
                            <div class='details-item'>
                                <span class='label'>مدت زمان ارسال (روز) : </span>
                                <span class='value'>$Service->SLA</span>
                            </div>
                            <div class='details-item'>
                                <span class='label'>قیمت (ریال) : </span>
                                <span class='value'>$Service->Price</span>
                            </div>
                        </div>
                    </div>
                ";
            }
        } else {
            $ServicesHTML = '<div>با توجه با مقادیر ورودی شما، سرویسی یافت نشد.</div>';
        }
    } else {
        $ServicesHTML = '<div>مشکل در برقراری ارتباط با سامانه باربری، لطفا بعدا مجدد تلاش کنید.</div><a id="retrayservice">تلاش مجدد</a>';
    }
    echo $ServicesHTML;

    die();
}
/***** End: Admin Services Html *****/

/***** Set Default Service for first time *****/
function setDefaultServiceSession()
{
    $defaultboxType = get_option('postbar_woo_shipping_opts')["defaultboxType"];
    $defaultbime = get_option('postbar_woo_shipping_opts')["defaultbime"];
    $needbox = get_option('postbar_woo_shipping_opts')["needbox"];
    $printlogo = get_option('postbar_woo_shipping_opts')["printlogo"];
    $printinvoice = get_option('postbar_woo_shipping_opts')["printinvoice"];
    $sendsms = get_option('postbar_woo_shipping_opts')["sendsms"];
    $boxsize = array(
        "19472" => "کارتن نیاز ندارم.",

        "19473" => "سایز A5(22*11)",

        "19474" => "سایز A4(31*22)",

        "19475" => "سایز A3(30*45)",

        "19476" => "سایز 1(10*10*15)",

        "19477" => "سایز 2(10*15*20)",

        "19478" => "سایز 3(15*20*20)",

        "19479" => "سایز 4(20*20*30)",

        "19480" => "سایز 5(20*25*35)",

        "19481" => "سایز 6(20*35*45)",

        "19482" => "سایز 7(25*30*40)",

        "19483" => "سایز 8(30*40*45)",

        "19484" => "سایز 9(35*45*55)",

        "19485" => "سایر(بزرگتر از سایز 9)"
    );
    $bimeselect = array(
        "300000" => "غرامت تا سقف 300 هزار تومان",
        "400000" => "غرامت تا سقف 400 هزار تومان",
        "500000" => "غرامت تا سقف 500 هزار تومان",
        "600000" => "غرامت تا سقف 600 هزار تومان",
        "700000" => "غرامت تا سقف 700 هزار تومان",
        "800000" => "غرامت تا سقف 800 هزار تومان",
        "900000" => "غرامت تا سقف 900 هزار تومان",
        "1000000" => "غرامت تا سقف 1 میلیون تومان",
        "1500000" => "غرامت تا سقف 1 میلیون و پانصد هزار تومان",
        "2000000" => "غرامت تا سقف 2 میلیون تومان",
        "2500000" => "غرامت تا سقف 2 میلیون و پانصد هزار تومان",
        "3000000" => "غرامت تا سقف 3 میلیون تومان",
        "3500000" => "غرامت تا سقف 3 میلیون و پانصد هزار تومان",
        "4000000" => "غرامت تا سقف 4 میلیون تومان",
        "4500000" => "غرامت تا سقف 4 میلیون و پانصد هزار تومان",
        "5000000" => "غرامت تا سقف 5 میلیون تومان",
        "5500000" => "غرامت تا سقف 5 میلیون و پانصد هزار تومان",
        "6000000" => "غرامت تا سقف 6 میلیون تومان"
    );
    // Sender    
    $senderStateId = get_option('postbar_woo_shipping_opts')["Sender_StateId"] ? get_option('postbar_woo_shipping_opts')["Sender_StateId"] : 1;
    $senderTownId = get_option('postbar_woo_shipping_opts')["Sender_townId"] ? get_option('postbar_woo_shipping_opts')["Sender_townId"] : 779;
    $boxType = get_option('postbar_woo_shipping_opts')["boxType"] ? get_option('postbar_woo_shipping_opts')["boxType"] : "1";

    // wieght
    $weight_unit = get_option('woocommerce_weight_unit');
    $cart_weight = WC()->cart->cart_contents_weight;
    if ($weight_unit == 'kg') {
        $cart_weight = $cart_weight * 1000;
    }
    if ($cart_weight == 0) {
        $cart_weight = 100;
    }

    // AproximateValue
    $current_currency = get_woocommerce_currency();
    $AproximateValue = WC()->cart->subtotal;
    if ($current_currency == 'IRT') {
        $AproximateValue = $AproximateValue * 10;
    } elseif ($current_currency == 'IRHT') {
        $AproximateValue = $AproximateValue * 10000;
    }

    // Receiver
    $listType = get_option('postbar_woo_shipping_opts')["ListType"];
    $receiverStateId = get_option('postbar_woo_shipping_opts')["reciver_default_stateId"] ? get_option('postbar_woo_shipping_opts')["reciver_default_stateId"] : 1;
    $receiverTownId = get_option('postbar_woo_shipping_opts')["reciver_default_townId"] ? get_option('postbar_woo_shipping_opts')["reciver_default_townId"] : 779;
    $enabled_payments = enabled_payments();
    if ($enabled_payments->bothEnabled) {
        $is_COD = 'false';
    } else {
        $is_COD = $enabled_payments->singleEnabled == 'cod' ? 'true' : 'false';
    }

    $data_array = array(
        "insuranceName" => $bimeselect[$defaultbime],
        "CartonSizeName" => $boxsize[$boxsize],
        //فروشنده
        'ListType' => $ListType, //req 0=all, 1=Fastest, 2=cheapest
        'senderStateId' => $senderStateId, //req 1=تهران
        'senderTownId' => $senderTownId, //req 779=تجریش
        'boxType' => $boxType,  // 0 = pakat , 1 = baste
        //خریدار
        'receiver_ForeginCountry' => 0,  // اجباری در پست خارجی
        'receiverStateId' => $receiverStateId, //req expt external
        'receiverTownId' => $receiverTownId, //req expt external
        //کالا
        'weightItem' => $cart_weight, //req گرم
        'AproximateValue' => $AproximateValue, // ریال
        'height' => 10, // req if external
        'width' => 10, // req if external
        'length' => 10, // req if external
        'Content' => '',
        //حمل و نقل سنگین
        'dispatch_date' => '', //req if heavy تاریخ و ساعت بارگیری
        'TruckType' => '', //req if heavy نوع خودرو
        'VechileOptions' => '', //req if heavy ویژگی خودرو
        'PackingLoad' => '', //req if heavy نوع بسته بندی بار    
        "printBill" => ($printinvoice == 0) ? false : true,
        "printLogo" => ($printlogo == 0) ? false : true,
        "needCartoon" => ($needbox == 0) ? false : true,
        "sendSms" => ($nesendsmsedbox == 0) ? false : true,
        'serviceId' => 0,
        'IsCOD' => $is_COD
    );

    $Services = PostexApi::getServices($data_array);
    //print_r($Services);exit;
    if ($Services->success && is_array($Services->data->servicesInfo)) {
        WC()->session->set("postbar_UserSelected_ServiceId", $Services->data->servicesInfo[0]->ServiceId);
        WC()->session->set("postbar_UserSelected_ServicePrice", $Services->data->servicesInfo[0]->Price);
        WC()->session->set("postbar_UserSelected_ServiceTitle", $Services->data->servicesInfo[0]->ServiceName);
    }
}
/***** End: Set Default Service for first time *****/



// INSURANCE : 

/***** Get Insurance HTML *****/
function postbarInsurancesHTML($selected_InsuranceId = '', $serviceId)
{
    $Insurances = PostexApi::getInsuranceList($serviceId);
    $InsurancesHTML = "";
    if ($Insurances->success) {
        foreach ($Insurances->data->data as $Insurance) {
            $selected = $selected_InsuranceId == $Insurance->Id ? "selected" : "";
            $InsurancesHTML .= "<option value='" . $Insurance->Id . "' $selected>" . $Insurance->Name . "</option>";
        }
    }

    return $InsurancesHTML;
}
/***** End: Get Insurance HTML *****/

/***** Ajax Get Insurance HTML *****/
add_action('wp_ajax_ajaxPostbarInsurancesHTML', 'ajaxPostbarInsurancesHTML');
add_action('wp_ajax_nopriv_ajaxPostbarInsurancesHTML', 'ajaxPostbarInsurancesHTML');
function ajaxPostbarInsurancesHTML()
{
    check_ajax_referer('nonce-ajaxPostbarInsurancesHTML', 'security');
    $serviceId = $_POST['serviceId'] ? intval($_POST['serviceId']) : 0;
    $InsurancesHTML = postbarInsurancesHTML('', $serviceId);

    echo $InsurancesHTML;

    die();
}
/***** End: Ajax Get Insurance HTML *****/

/***** Woo Insurance Options arrray *****/
function postbarWooInsuranceOptions($serviceId)
{
    $Insurances = PostexApi::getInsuranceList($serviceId);
    if ($Insurances->success) {
        foreach ($Insurances->data->data as $Insurance) {
            $Insurance_options[$Insurance->Id] = $Insurance->Name;
        }
    } else {
        $Insurance_options = array(
            ''    => 'عدم ارتباط با سامانه باربری'
        );
    }

    return $Insurance_options;
}
/***** End: Woo Insurance Options arrray *****/



// CARTONSIZE : 

/***** Get CartonSize HTML *****/
function postbarCartonSizeHTML($selected_CartonSizeId = '', $serviceId)
{
    $CartonSizes = PostexApi::getCartonSizeList($serviceId);
    $CartonSizeHTML = "";
    if ($CartonSizes->success) {
        foreach ($CartonSizes->data->data as $CartonSize) {
            $selected = $selected_CartonSizeId == $CartonSize->Id ? "selected" : "";
            $CartonSizeHTML .= "<option value='" . $CartonSize->Id . "' $selected>" . $CartonSize->Name . "</option>";
        }
    }

    return $CartonSizeHTML;
}
/***** End: Get CartonSize HTML *****/

/***** Ajax Get CartonSize HTML *****/
add_action('wp_ajax_ajaxPostbarCartonSizeHTML', 'ajaxPostbarCartonSizeHTML');
add_action('wp_ajax_nopriv_ajaxPostbarCartonSizeHTML', 'ajaxPostbarCartonSizeHTML');
function ajaxPostbarCartonSizeHTML()
{
    check_ajax_referer('nonce-ajaxPostbarCartonSizeHTML', 'security');
    $serviceId = $_POST['serviceId'] ? intval($_POST['serviceId']) : 0;
    $CartonSizeHTML = postbarCartonSizeHTML('', $serviceId);

    echo $CartonSizeHTML;

    die();
}
/***** End: Ajax Get CartonSize HTML *****/



// NEW ORDER : 

/***** Set Order In Postbar *****/
add_action('wp_ajax_ajaxPostbarNewOrder', 'ajaxPostbarNewOrder');
add_action('wp_ajax_nopriv_ajaxPostbarNewOrder', 'ajaxPostbarNewOrder');
function ajaxPostbarNewOrder()
{
    if (current_user_can('editor') || current_user_can('administrator')) {
        check_ajax_referer('nonce-ajaxPostbarNewOrder', 'security');


        //فروشنده
        $Sender_FristName = wp_kses($_POST["Sender_FristName"], array());
        $Sender_LastName = wp_kses($_POST["Sender_LastName"], array());
        $Sender_mobile = wp_kses($_POST["Sender_mobile"], array());
        $Sender_StateId = $_POST["Sender_StateId"] ? intval($_POST["Sender_StateId"]) : 0;
        $Sender_townId = $_POST["Sender_townId"] ? intval($_POST["Sender_townId"]) : 0;
        $Sender_City = wp_kses($_POST["Sender_City"], array());
        $Sender_PostCode = wp_kses($_POST["Sender_PostCode"], array());
        $Sender_Address = wp_kses($_POST["Sender_Address"], array());
        $Sender_Email = sanitize_email($_POST["Sender_Email"]);
        $SenderLat = wp_kses($_POST["SenderLat"], array());
        $SenderLon = wp_kses($_POST["SenderLon"], array());
        $InsuranceName = wp_kses($_POST["InsuranceName"], array());
        $CartonSizeName = wp_kses($_POST["CartonSizeName"], array());
        $boxType = intval($_POST["boxType"]);
        $HasAccessToPrinter = rest_sanitize_boolean($_POST["HasAccessToPrinter"]);
        $AgentSaleAmount = intval($_POST["AgentSaleAmount"]);
        $printLogo = rest_sanitize_boolean($_POST["printLogo"]);
        $notifBySms = rest_sanitize_boolean($_POST["notifBySms"]);
        $discountCouponCode = wp_kses($_POST["discountCouponCode"], array());

        //خریدار
        $ServiceId = $_POST["ServiceId"] ? intval($_POST["ServiceId"]) : 0;
        $Reciver_FristName = wp_kses($_POST["Reciver_FristName"], array());
        $Reciver_LastName = wp_kses($_POST["Reciver_LastName"], array());
        $Reciver_mobile = wp_kses($_POST["Reciver_mobile"], array());
        $receiver_ForeginCountry = intval($_POST["receiver_ForeginCountry"]);
        $receiver_ForeginCountryName = wp_kses($_POST["receiver_ForeginCountryName"], array());
        $Reciver_StateId = $_POST["Reciver_StateId"] ? intval($_POST["Reciver_StateId"]) : 0;
        $Reciver_townId = $_POST["Reciver_townId"] ? intval($_POST["Reciver_townId"]) : 0;
        $Reciver_City = wp_kses($_POST["Reciver_City"], array());
        $Reciver_PostCode = wp_kses($_POST["Reciver_PostCode"], array());
        $Reciver_Address = wp_kses($_POST["Reciver_Address"], array());
        $Reciver_Email = sanitize_email($_POST["Reciver_Email"]);
        $IsCODSubmit = wp_kses($_POST["IsCOD"], array());
        $PaymentType = wp_kses($_POST["PaymentType"], array());
        $IsCOD = false;
        if ($PaymentType == 'free') {
            $IsCOD = false;
            $IsFreePost = true;
            $IsSafeBuy = false;
        } else if ($PaymentType == 'cod') {
            $IsCOD = true;
            $IsFreePost = false;
            $IsSafeBuy = false;
        } else if ($PaymentType == 'safe') {
            $IsCOD = false;
            $IsFreePost = false;
            $IsSafeBuy = true;
        } else if ($PaymentType == 'prepaid') {
            $IsCOD = false;
            $IsFreePost = false;
            $IsSafeBuy = false;
        }
        //کالا
        $GoodsType = wp_kses($_POST["GoodsType"], array());
        $ApproximateValue = $_POST["ApproximateValue"] ? intval($_POST["ApproximateValue"]) : 0;
        $CodGoodsPrice = $_POST["CodGoodsPrice"] ? intval($_POST["CodGoodsPrice"]) : 0;
        $Weight = $_POST["Weight"] ? intval($_POST["Weight"]) : 100;
        $length = $_POST["length"] ? intval($_POST["length"]) : 0;
        $width = $_POST["width"] ? intval($_POST["width"]) : 0;
        $height = $_POST["height"] ? intval($_POST["height"]) : 0;
        $Count = $_POST["Count"] ? intval($_POST["Count"]) : 1;

        //حمل و نقل سنگین 
        $UbbraTruckType = wp_kses($_POST["UbbraTruckType"], array());
        $VechileOptions = wp_kses($_POST["VechileOptions"], array());
        $UbbarPackingLoad = wp_kses($_POST["UbbarPackingLoad"], array());
        $dispatch_date = wp_kses($_POST["dispatch_date"], array());

        //plugin Data
        $PostbarShipping = new PostexShipping;
        $plugin_version = $PostbarShipping->plugin_version;
        $woo_post_id = $_POST["woo_post_id"] ? intval($_POST["woo_post_id"]) : 1;


        /* $data_array = array(
            //فروشنده
            "Sender_FristName" => $Sender_FristName,
            "Sender_LastName" => $Sender_LastName,
            "Sender_mobile" => $Sender_mobile,
            "Sender_StateId" => $Sender_StateId,
            "Sender_townId" => $Sender_townId,
            "Sender_City" => $Sender_City,
            "Sender_PostCode" => $Sender_PostCode,
            "Sender_Address" => $Sender_Address,
            "Sender_Email" => $Sender_Email,
            "SenderLat" => $SenderLat,
            "SenderLon" => $SenderLon,
            "InsuranceName" => $InsuranceName,
            "CartonSizeName" => $CartonSizeName,
            "boxType" => $boxType,
            "HasAccessToPrinter" => $HasAccessToPrinter,
            "AgentSaleAmount" => $AgentSaleAmount,
            "printLogo" => $printLogo,
            "notifBySms" => $notifBySms,
            'discountCouponCode' => $discountCouponCode,

            //خریدار
            "ServiceId" => $ServiceId,
            "Reciver_FristName" => $Reciver_FristName,
            "Reciver_LastName" => $Reciver_LastName,
            "Reciver_Mobile" => $Reciver_mobile,
            "receiver_ForeginCountry" => $receiver_ForeginCountry,
            "receiver_ForeginCountryName" => $receiver_ForeginCountryName,
            "Reciver_StateId" => $Reciver_StateId,
            "Reciver_townId" => $Reciver_townId,
            "Reciver_City" => $Reciver_City,
            "Reciver_PostCode" => $Reciver_PostCode,
            "Reciver_Address" => $Reciver_Address,
            "Reciver_Email" => $Reciver_Email,
            "ReciverLat" => "",
            "ReciverLon" => "",
            "IsCOD" => $IsCOD,

            //کالا
            "GoodsType" => $GoodsType,
            "ApproximateValue" => $ApproximateValue,
            "CodGoodsPrice" => $CodGoodsPrice,
            "Weight" => $Weight,
            "length" => $length,
            "width" => $width,
            "height" => $height,
            "Count" => $Count,

            //حمل و نقل سنگین    
            "UbbraTruckType" => $UbbraTruckType,
            "VechileOptions" => $VechileOptions,
            "UbbarPackingLoad" => $UbbarPackingLoad,
            "dispatch_date" => $dispatch_date,

            // اطلاعات پلاگین
            "domainName" => $_SERVER['SERVER_NAME'],
            "pluginType" => "woocommerce",
            "pluginVersion" => $plugin_version,
            "refrenceNo" => $woo_post_id
        );*/
        $data_array = array(
            "ServiceID" => $ServiceId, // شناسه سرویس پستی
            "GoodsType" => $GoodsType,
            "ApproximateValue" => $ApproximateValue, // ارزش ریالی مرسوله
            "CodGoodsPrice" => 0, // مبلغی که باید از مشتری در محل دریافت شود
            "Weight" => $Weight, // وزن به گرم
            "InsuranceName" => $InsuranceName,
            "NeedCarton" => false, // آیا نیاز به بسته بندی دارید
            "CartonSizeName" => $CartonSizeName,
            "Sender_FristName" => $Sender_FristName,
            "Sender_LastName" =>  $Sender_LastName,
            "Sender_mobile" =>  $Sender_mobile,
            "Sender_StateId" => $Sender_StateId,
            "Sender_townId" => $Sender_townId,
            "Sender_PostCode" => $Sender_PostCode,
            "Sender_Address" => $Sender_Address,
            "Reciver_FristName" => $Reciver_FristName,
            "Reciver_LastName" =>  $Reciver_LastName,
            "Reciver_Mobile" =>  $Reciver_mobile,
            "Reciver_StateId" => $Reciver_StateId,
            "Reciver_TownId" =>  $Reciver_townId,
            "Reciver_PostCode" => $Reciver_PostCode,
            "Reciver_Address" => $Reciver_Address,
            "IsCOD" =>  $IsCOD,
            "HasAccessToPrinter" => $HasAccessToPrinter,
            "BoxType" => $boxType,
            "Count" => $Count,
            "SenderLat" => $SenderLat,
            "SenderLon" => $SenderLon,
            "RefrenceNo" =>  $woo_post_id,
            "OrderSource" => 18, // نوع ورودی
            "NotifBySms" =>  $notifBySms, // اطلاع رسانی دپیامکی
            "IsFreePost" => $IsFreePost,
            "IsSafeBuy" => $IsSafeBuy, //,آیا سفارش پرداخت امن می باشد
            "receiver_ForeginCountry" => 0
        );
        $result = PostexApi::newOrder($data_array);

        if ($result->success) {
            if ($result->data->orderId) {
                if (get_post_meta($woo_post_id, 'postbar_orderId', true)) {
                    update_post_meta($woo_post_id, 'postbar_orderId', $result->data->orderId);
                } else {
                    add_post_meta($woo_post_id, 'postbar_orderId', $result->data->orderId, true);
                }

                echo json_encode(array(
                    'status' => 'success',
                    'message' => $result->data->message,
                    'orderId' => $result->data->orderId,
                    'site_url' => site_url(),
                    'token' => PostexApi::getToken(),
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => $result->data->message
                ));
            }
        } else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'خطا در ارسال داده ها مجددا تلاش کنید.'
            ));
        }
    } // End check if user has approprate role

    die();
}
/***** End: Set Order In Postbar *****/
