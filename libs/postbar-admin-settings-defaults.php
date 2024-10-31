
<!-- Defaults Shipping Values -->
<?php 
$defaultboxType = get_option('postbar_woo_shipping_opts')["defaultboxType"];
$paymenttype = get_option('postbar_woo_shipping_opts')["paymenttype"];
$defaultbime = get_option('postbar_woo_shipping_opts')["defaultbime"];
$needbox = get_option('postbar_woo_shipping_opts')["needbox"];
$submitCOD = get_option('postbar_woo_shipping_opts')["submitCOD"];
$printlogo = get_option('postbar_woo_shipping_opts')["printlogo"];
            $printinvoice = get_option('postbar_woo_shipping_opts')["printinvoice"];
            $sendsms = get_option('postbar_woo_shipping_opts')["sendsms"];
            
    $boxsize = array(
        "19472"=> "کارتن نیاز ندارم.",

        "19473"=> "سایز A5(22*11)",

        "19474"=> "سایز A4(31*22)",

        "19475"=> "سایز A3(30*45)",

        "19476"=> "سایز 1(10*10*15)",

        "19477"=> "سایز 2(10*15*20)",

        "19478"=> "سایز 3(15*20*20)",

        "19479"=> "سایز 4(20*20*30)",

        "19480"=> "سایز 5(20*25*35)",

        "19481"=> "سایز 6(20*35*45)",

        "19482"=> "سایز 7(25*30*40)",

        "19483"=> "سایز 8(30*40*45)",
 
        "19484"=> "سایز 9(35*45*55)",

        "19485"=> "سایر(بزرگتر از سایز 9)"
);
$bimeselect =array(
"300000"=> "غرامت تا سقف 300 هزار تومان",
"400000"=> "غرامت تا سقف 400 هزار تومان",
"500000"=> "غرامت تا سقف 500 هزار تومان",
"600000"=> "غرامت تا سقف 600 هزار تومان",
"700000"=> "غرامت تا سقف 700 هزار تومان",
"800000"=> "غرامت تا سقف 800 هزار تومان",
"900000"=> "غرامت تا سقف 900 هزار تومان",
"1000000"=> "غرامت تا سقف 1 میلیون تومان",
"1500000"=> "غرامت تا سقف 1 میلیون و پانصد هزار تومان",
"2000000"=> "غرامت تا سقف 2 میلیون تومان",
"2500000"=> "غرامت تا سقف 2 میلیون و پانصد هزار تومان",
"3000000"=> "غرامت تا سقف 3 میلیون تومان",
"3500000"=> "غرامت تا سقف 3 میلیون و پانصد هزار تومان",
"4000000"=> "غرامت تا سقف 4 میلیون تومان",
"4500000"=> "غرامت تا سقف 4 میلیون و پانصد هزار تومان",
"5000000"=> "غرامت تا سقف 5 میلیون تومان",
"5500000"=> "غرامت تا سقف 5 میلیون و پانصد هزار تومان",
"6000000"=> "غرامت تا سقف 6 میلیون تومان");
    $is_user_saved = get_option('postbar_woo_shipping_opts')["is_user_saved"];
    
    $ListType = get_option('postbar_woo_shipping_opts')["ListType"];
    $ListTypeSelectable = get_option('postbar_woo_shipping_opts')["ListTypeSelectable"];
    if(!$is_user_saved)
    {
        $ListTypeSelectable = 1;
    }
    
    $conditionalShipping = 0;
    if(!$is_user_saved)
    {
        $conditionalShipping = 0;
    }elseif(isset(get_option('postbar_woo_shipping_opts')["conditionalShipping"])){
        $conditionalShipping = get_option('postbar_woo_shipping_opts')["conditionalShipping"];
        //$conditionalShipping = 0;
    }

    //$shippingConditions = get_option('postbar_woo_shipping_opts')["shippingConditions"];
    
    
    if(!$is_user_saved)
    {
        $boxType = "1";
    }else{
        $boxType = get_option('postbar_woo_shipping_opts')["boxType"];
    }

    
    if(!$is_user_saved )
    {
        $shippingPriceCalcType = "1";
    }else{
        $shippingPriceCalcType = get_option('postbar_woo_shipping_opts')["shippingPriceCalcType"];
    }

    $fixedShippingPriceValue = get_option('postbar_woo_shipping_opts')["fixedShippingPriceValue"];

    $woocommerce_currency_symbol = get_option('woocommerce_currency');
    $woocommerce_currency_title = "";
    switch ($woocommerce_currency_symbol) {
        case 'IRR':
            $woocommerce_currency_title = "ریال";
            break;
        case 'IRHR':
            $woocommerce_currency_title = "هزار ریال";
            break;
        case 'IRT':
            $woocommerce_currency_title = "تومان";
            break;
        case 'IRHT':
            $woocommerce_currency_title = "هزار تومان";
            break;
    }
?>
<div>     
    <div class="pws-container-title">
        <div class="title-text">تنظیمات پیش فرض باربری</div>
                                   
    </div>
    <div class="pws-container">
        <p>تنظیمات زیر در هنگام خرید مشتریان، به عنوان آیتم های پیش فرض، جهت استعلام قیمت استفاده میشوند. شما میتوانید در زمان ثبت سفارش برای باربری، بسته به نیاز هر سفارش، این تنظیمات را تغییر دهید.</p>
        <input type="hidden" name="postbar_woo_shipping_opts[is_user_saved]" value="1" />
        <table class="pws-wide-form-table c3">
            <tr>
                <th class="align-baseline">
                    <label for="shippingPriceCalcType">نوع محاسبه هزینه حمل و نقل</label>                                
                </th>
                <td>
                    <div class="shipping-price-calc-type-row">
                        <input type="radio" name="postbar_woo_shipping_opts[shippingPriceCalcType]" value="1" <?php echo $shippingPriceCalcType=="1" ? "checked" : ""; ?> id="postbar_woo_shipping_opts_shippingPriceCalcType_1">
                        <label for="postbar_woo_shipping_opts_shippingPriceCalcType_1">محاسبه خودکار براساس شهر مقصد (انتخاب سرویس پستی توسط مشتری)</label>
                    </div>
                    <div class="shipping-price-calc-type-row">
                        <input type="radio" name="postbar_woo_shipping_opts[shippingPriceCalcType]" value="2" <?php echo $shippingPriceCalcType=="2" ? "checked" : ""; ?> id="postbar_woo_shipping_opts_shippingPriceCalcType_2">
                        <label for="postbar_woo_shipping_opts_shippingPriceCalcType_2">حمل و نقل رایگان</label>
                    </div>
                    <div class="shipping-price-calc-type-row">
                        <input type="radio" name="postbar_woo_shipping_opts[shippingPriceCalcType]" value="3" <?php echo $shippingPriceCalcType=="3" ? "checked" : ""; ?> id="postbar_woo_shipping_opts_shippingPriceCalcType_3">
                        <label for="postbar_woo_shipping_opts_shippingPriceCalcType_3">نرخ ثابت</label>
                    </div>
                </td>
            </tr> 
            <tr id="tr_conditionalShipping">
                <th class="align-baseline">
                    <input type="checkbox" name="postbar_woo_shipping_opts[conditionalShipping]" id="conditionalShipping" value="1" <?php echo $conditionalShipping ? "checked" : ""; ?>>
                    <label for="conditionalShipping">فعال سازی حمل و نقل رایگان مشروط</label>                                
                </th>
                <td id="shipping_conditions_container" class="<?php echo $conditionalShipping ? "visible" : ""; ?>">
                    <?php 
                        $nextConditionId = 0;
                        if( isset($shippingConditions)&&is_array($shippingConditions) && count($shippingConditions) > 0 ) : 
                        foreach($shippingConditions as $key => $shippingCondition) : 
                    ?>
                    <div class="specified-condition-row">
                        <a href="#">حذف</a>
                        <span><?php echo json_decode($shippingCondition)->shippingTitle; ?></span>   
                        <input type="hidden" name="postbar_woo_shipping_opts[shippingConditions][<?php echo $key; ?>]" 
                                value='<?php echo $shippingCondition; ?>' />                                 
                    </div>
                    <?php $nextConditionId=$key*1+1; endforeach; endif; ?>                                
                    <button id="btn_CSHM_opener" class="button button-info">افزودن شرط</button>
                    <input type="hidden" id="nextConditionId" value="<?php echo $nextConditionId; ?>" />
                </td>
            </tr>
            <tr id="tr_fixedShippingPriceValue">
                <th>
                    <label for="fixedShippingPriceValue">
                        نرخ ثابت حمل و نقل (ریال)
                        <br />
                        <span class="lable-guide">
                        برای زمانی که مشتری هنوز سرویسی را انتخاب نکرده و یا نوع محاسبه، نرخ ثابت می‌باشد.
                        </span>
                    </label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[fixedShippingPriceValue]" id="fixedShippingPriceValue" value="<?php echo $fixedShippingPriceValue ? $fixedShippingPriceValue : 200000; ?>" placeholder="نرخ ثابت حمل و نقل" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ListType">نمایش سرویس های پستی به مشتری</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[ListType]" id="ListType">
                        <option value="0" <?php echo $ListType=="0" ? "selected" : ""; ?> >همه سرویس ها</option>
                        <option value="1" <?php echo $ListType=="1" ? "selected" : ""; ?> >سریع ترین</option>
                        <option value="2" <?php echo $ListType=="2" ? "selected" : ""; ?> >ارزان ترین</option>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="postbar_woo_shipping_opts[ListTypeSelectable]" id="ListTypeSelectable" value="1" <?php echo $ListTypeSelectable ? "checked" : ""; ?> />
                    <label for="ListTypeSelectable">امکان تغییر توسط مشتری</label>
                </td>
            </tr>   
            <tr>
                <th>
                    <label for="boxType">نوع بسته بندی کالا</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[boxType]" id="boxType">
                        <option value="1" <?php echo $boxType=="1" ? "selected" : ""; ?> >بسته</option>
                        <option value="0" <?php echo $boxType=="0" ? "selected" : ""; ?> >پاکت</option>
                    </select>
                </td>
            </tr>   
            <!--<tr>
                <th>
                    <label for="boxType">ثبت سفارات به صورت پرداخت در محل؟</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[submitCOD]" id="boxType">
                        <option value="1" <?php echo $submitCOD=="1" ? "selected" : ""; ?> >بله</option>
                        <option value="0" <?php echo $submitCOD=="0" ? "selected" : ""; ?> >خیر</option>
                    </select>
                </td>
            </tr> -->
            <tr>
                <th>
                    <label for="boxType">کارتن نیاز دارید؟</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[needbox]" id="boxType">
                        <option value="1" <?php echo $needbox=="1" ? "selected" : ""; ?> >بله</option>
                        <option value="0" <?php echo $needbox=="0" ? "selected" : ""; ?> >خیر</option>
                    </select>
                </td>
            </tr>   
            <tr>
                <th>
                    <label for="paymentType">نحوه پرداخت پیش فرض</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[paymenttype]" id="">
                    <option value="cod" <?php echo $paymenttype=="cod" ? "selected" : ""; ?> >پس کرایه</option>
                    <option value="prepaid" <?php echo $paymenttype=="prepaid" ? "selected" : ""; ?> >پیش کرایه</option>
                    <option value="safe" <?php echo $paymenttype=="safe" ? "selected" : ""; ?> >پرداخت امن</option>
                    <option value="free" <?php echo $paymenttype=="free" ? "selected" : ""; ?> >پرداخت رایگان</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <th>
                    <label for="boxType"> چاپ لوگو؟</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[printlogo]" id="boxType">
                        <option value="1" <?php echo ($printlogo=="1") ? "selected" : ""; ?> >بله</option>
                        <option value="0" <?php echo ($printlogo=="0") ? "selected" : ""; ?> >خیر</option>
                    </select>
                </td>
            </tr>   
            <tr>
                <th>
                    <label for="boxType">امکان چاپ صورتحساب؟</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[printinvoice]" id="boxType">
                        <option value="1" <?php echo ($printinvoice=="1") ? "selected" : ""; ?> >بله</option>
                        <option value="0" <?php echo ($printinvoice=="0") ? "selected" : ""; ?> >خیر</option>
                    </select>
                </td>
            </tr>   
            <tr>
                <th>
                    <label for="boxType">ارسال اس ام اس؟</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[sendsms]" id="boxType">
                        <option value="1" <?php echo ($sendsms=="1") ? "selected" : ""; ?> >بله</option>
                        <option value="0" <?php echo ($sendsms=="0") ? "selected" : ""; ?> >خیر</option>
                    </select>
                </td>
            </tr>   
            <tr>         

                <th>
                    <label for="boxType">اندازه کارتن پیشفرض</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[defaultboxType]" id="boxType">
                        
                        <?php foreach($boxsize as $key => $val){ ?>
                            <option value="<?php echo $key; ?>" <?php echo $defaultboxType==$key ? "selected" : ""; ?> ><?php echo $val; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>    
            <tr>
                <th>
                    <label for="boxType">بیمه پیش فرض</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[defaultbime]" id="boxType">
                        
                        <?php foreach($bimeselect as $key => $val){ ?>
                            <option value="<?php echo $key; ?>" <?php echo $defaultbime==$key ? "selected" : ""; ?> ><?php echo $val; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>                    
        </table>
        <?php submit_button( 'ذخیره تغییرات', 'primary', '', false, '' ); ?>
        <div id="conditional_shipping_modal" class="unvisible">
            <div id="CSHM_content">
                <div id='CSHM_header'>
                    <div id='CSHM_close_modal_icon'></div>
                    <h3>افزودن شرط برای حمل و نقل رایگان</h3>
                </div>
                <div id='CSHM_body'>
                    <div class="CSHM-form-row">
                        <label class="CSHM-label">نوع شرط :</label>
                        <select class="CSHM-input" id="CSHM_condition_type">
                            <option value="">انتخاب کنید</option>
                            <option value="receiverLocation" data-target="CSHM_RL_fields">حمل و نقل رایگان برای منطقه‌ای خاص</option>
                            <option value="cartTotalPrice" data-target="CSHM_CTP_fields">حمل و نقل رایگان براساس مجموع سبد خرید</option>
                        </select>
                    </div>
                    <div id="CSHM_RL_fields" class="CSHM-condition-fields">
                        <div class="CSHM-form-row">
                            <label class="CSHM-label">استان مشتری :</label>
                            <select class="CSHM-input" id="CSHM_RL_stateId">
                                <?php echo postbarStatesHTML(); ?>
                            </select>
                        </div>
                        <div class="CSHM-form-row">
                            <label class="CSHM-label">شهرستان مشتری :</label>
                            <select class="CSHM-input" id="CSHM_RL_townId">
                                <?php echo postbarStateTownsHTML(); ?>
                            </select>
                        </div>
                    </div>
                    <div id="CSHM_CTP_fields" class="CSHM-condition-fields">
                        <div class="CSHM-form-row">
                            <label class="CSHM-label">
                                حداقل قیمت سبد خرید  :
                                (<?php echo $woocommerce_currency_title; ?>)
                            </label>
                            <input type="text" class="CSHM-input" id="CSHM_CTP_minPrice" />
                        </div>
                    </div>
                    <div id="condition_method_title_container">
                        <label class="CSHM-label">عنوان روش حمل و نقل : </label>
                        <input type="text" class="CSHM-input" id="condition_method_title" />
                    </div>
                </div>
                <div id='CSHM_footer'>
                    <button id="btn_CSHM_cancel">انصراف</button>
                    <button id="btn_CSHM_save">تایید</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(function($){

            function mngFixedPriceVisibility(){
                var selected_shippingPriceCalcType = $("input:radio[name='postbar_woo_shipping_opts[shippingPriceCalcType]']:checked").val();
                
                if(selected_shippingPriceCalcType == "1")
                {
                    $("#tr_conditionalShipping").show();
                }
                else
                {
                    $("#tr_conditionalShipping").hide();
                }

                if(selected_shippingPriceCalcType == "2")
                {
                    $("#tr_fixedShippingPriceValue").hide();
                }
                else
                {
                    $("#tr_fixedShippingPriceValue").show();
                }
            }

            /***** Change shippingPriceCalcType *****/
            $("input:radio[name='postbar_woo_shipping_opts[shippingPriceCalcType]']").change(function(){
                mngFixedPriceVisibility();
            });
            /***** End: Change shippingPriceCalcType *****/

            $(document).ready(function(){
                mngFixedPriceVisibility();
            });

            /***** shipping conditions container visibility *****/
            $("#conditionalShipping").change(function(){
                if( $(this).is(':checked') )
                    $("#shipping_conditions_container").addClass("visible");
                else
                    $("#shipping_conditions_container").removeClass("visible");
            });
            /***** End: shipping conditions container visibility *****/

            /***** Conditional Shipping Modal *****/
            //open
            $("#btn_CSHM_opener").click(function(e){
                e.preventDefault();
                $("#conditional_shipping_modal").removeClass("unvisible");
            });
            //close
            $("#btn_CSHM_cancel, #CSHM_close_modal_icon").click(function(e){
                e.preventDefault();
                $("#conditional_shipping_modal").addClass("unvisible");
            });
            // change condition type
            $("#CSHM_condition_type").change(function(){
                $(".CSHM-condition-fields").removeClass("show");
                var target_id = $("#CSHM_condition_type option:selected").data("target");
                $("#"+target_id).addClass("show");
                if( $(this).val() )
                    $("#condition_method_title_container").addClass("CSHM-form-row");
                else
                    $("#condition_method_title_container").removeClass("CSHM-form-row");
            });
            // change state
            $("#CSHM_RL_stateId").on('change', function () {
                $("#CSHM_RL_townId").html('<option>دریافت اطلاعات ...</option>');
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data : {
                        action : "ajaxPostbarStateTownsHTML",
                        security: '<?php echo wp_create_nonce( "nonce-ajaxPostbarStateTownsHTML" ); ?>',
                        stateId : $(this).val()
                    },
                    success: function (result) {
                        $("#CSHM_RL_townId").html(result);
                    }
                });
            });
            // confirmation
            $("#btn_CSHM_save").click(function(e){
                e.preventDefault();
                $("#conditional_shipping_modal").addClass("unvisible");

                if( $("#CSHM_condition_type").val() )
                {
                    var condition_method_title = $("#condition_method_title").val();
                    var condition_obj = {};
                    if( $("#CSHM_condition_type").val() == "receiverLocation" )
                    {
                        var condition_obj = {
                            conditionType : "receiverLocation",
                            stateId : $("#CSHM_RL_stateId").val(),
                            townId : $("#CSHM_RL_townId").val(),
                            shippingTitle : condition_method_title
                        };
                    }
                    else if( $("#CSHM_condition_type").val() == "cartTotalPrice" )
                    {
                        var condition_obj = {
                            conditionType : "cartTotalPrice",
                            minPrice : $("#CSHM_CTP_minPrice").val(),
                            shippingTitle : condition_method_title
                        };
                    }
                    var condition_json = JSON.stringify(condition_obj);
                    var nextConditionId = $("#nextConditionId").val();

                    var new_condition_row = "\
                        <div class='specified-condition-row'>\
                            <a href='#'>حذف</a>\
                            <span>"+condition_method_title+"</span>\
                            <input type='hidden' name='postbar_woo_shipping_opts[shippingConditions]["+nextConditionId+"]' value='"+condition_json+"' />\
                        </div>";

                    $("#btn_CSHM_opener").before(new_condition_row);
                    $("#nextConditionId").val( nextConditionId*1 + 1  );
                }
            });
            /***** End: Conditional Shipping Modal *****/

            /***** Remove a condition row *****/
            $("body").on("click", ".specified-condition-row a", function(e){
                e.preventDefault();
                $(this).parent().remove();
            });
            /***** End: Remove a condition row *****/

        }); // End jQuery;
    </script>
</div>
<!-- End: Defaults Shipping Values -->