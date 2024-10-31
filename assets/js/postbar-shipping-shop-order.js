

jQuery(function($){
    $("#retrayservice").on("click" , function(){
        //getServices();
       alert('hi');
       });
    /***** new order form fields validation *****/
    function validateNewOrderFormFields(){
        var validationResult = {
            is_valid : true,
            msg : ""
        };

        $("#postex_order_details_container .required").each(function(){
            if( !$(this).val() )
            {
                var err_element = $(this).parent().find(".err-msg");
                if(!err_element.length)
                {
                    $(this).parent().append("<div class='err-msg'></div>");
                }
                $(this).parent().find(".err-msg").html("وارد کردن این فیلد الزامی است.");
                $(this).addClass("err");
                
                validationResult.is_valid = false;
                validationResult.msg = "مقادیر الزامی را وارد کنید.";
            }            
        });

        if( validationResult.is_valid )
        {
            $("#postex_order_details_container .validate").each(function(){
                var validate_validationResult = validator($(this).val() , $(this).data("validate"));
                if(!validate_validationResult.is_valid)
                {
                    var err_element = $(this).parent().find(".err-msg");
                    if(!err_element.length)
                    {
                        $(this).parent().append("<div class='err-msg'></div>");
                    }
                    $(this).parent().find(".err-msg").html(validate_validationResult.err_msg);
                    $(this).addClass("err");

                    validationResult.is_valid = false;
                    validationResult.msg = "لطفا خطاهای موجود را برطرف کنید.";
                }
            });
        }

        return validationResult;
    }
    /***** End: new order form fields validation *****/

    /***** blur inputs - validation *****/
    // validator 
    function validator(value, key)
    {
        var validationResult = {
            is_valid : "",
            err_msg : ""
        };

        switch (key) {
            case "email":
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                var is_valid = re.test(value);
                var msg = "مقدار وارد شده معتبر نیست.";
                break;

            case "mobile":
                var re_1 = /^\d{11}$/;
                var is_valid_1 = re_1.test(value);
                var msg_1 = "طول شماره موبایل باید 11 رقم باشد.";

                var re_2 = /(0|\+98)?([ ]|-|[()]){0,2}9[0|1|2|3|4|9]([ ]|-|[()]){0,3}(?:[0-9]([ ]|-|[()]){0,2}){8}/ig;
                var is_valid_2 = re_2.test(value);
                var msg_2 = "مقدار وارد شده معتبر نیست.";

                var is_valid = is_valid_1 && is_valid_2;
                var msg = !is_valid_1 ? msg_1 : msg_2;
                break;

            case "number":
                var is_valid = !isNaN(value);
                var msg = "مقدار وارد شده معتبر نیست.";
                break;
        }

        validationResult.is_valid = is_valid;
        validationResult.err_msg = is_valid ? "" : msg;

        return validationResult;
    }

    $("body").on("blur", "#postex_order_details_container .required, #postex_order_details_container .validate", function(){
        var err_element = $(this).parent().find(".err-msg");
        if($(this).val())
        {
            // not empty input
            var err_msg = "";
            if($(this).hasClass('validate'))
            {
                var validationResult = validator($(this).val() , $(this).data("validate"));
                if(!validationResult.is_valid)
                {
                    err_msg = validationResult.err_msg;
                }
            }

            if(err_msg)
            {
                if(!err_element.length)
                {
                    $(this).parent().append("<div class='err-msg'></div>");
                }
                $(this).parent().find(".err-msg").html(err_msg);
                $(this).addClass("err");
            }
            else
            {
                if(err_element.length)
                {
                    $(this).parent().find(".err-msg").remove();
                }                    
                $(this).removeClass("err");
            }
        }
        else
        {
            // empty input
            if($(this).hasClass('required'))
            {
                if(!err_element.length)
                {
                    $(this).parent().append("<div class='err-msg'></div>");
                }
                $(this).parent().find(".err-msg").html("وارد کردن این فیلد الزامی است.");
                $(this).addClass("err");
            }
        }
    });
    /***** End: blur inputs - validation *****/

    /***** Get Services *****/
    function getServices()
    {

        jQuery("#admin_services_content_container").html('دریافت اطلاعات ...');
        jQuery.ajax({
            type: "POST",
            url: jQuery("#admin_ajax_url").val(),
            data : {
                action : "ajaxAdminPostbarServicesHTML",
                security: jQuery("#nonce_ajaxAdminPostbarServicesHTML").val(),
                selected_ServiceId : jQuery("#primary_selected_ServiceId").val(),
                ListType : 0,
                senderStateId : jQuery("#Sender_StateId").val(),
                senderTownId : jQuery("#Sender_townId").val(),
                boxType : jQuery("#boxType").val(),
                insurance:jQuery("#InsuranceId option:selected").html(),
                cartonsize:jQuery("#CartonSizeId option:selected").html(),
                receiver_ForeginCountry : '',
                receiverStateId : jQuery("#Reciver_StateId").val(),
                receiverTownId : jQuery("#Reciver_townId").val() ,
                weightItem : jQuery("#Weight").val(),
                AproximateValue : jQuery("#ApproximateValue").val(),
                height : jQuery("#height").val(),
                width : jQuery("#width").val(),
                length : jQuery("#length").val(),
                Content : jQuery("#GoodsType").val(),
                dispatch_date : '',
                TruckType : '',
                VechileOptions : '',
                PackingLoad : '',
                PaymentType : jQuery("#paymentType").val(),
                IsCOD : jQuery("#IsCOD").val()
            },
            success: function (result) {
                jQuery("#admin_services_content_container").html(result);
            }
        });
    }
    jQuery(document).ready(function(){ 
  

        //getServices(); 
    });
    
    /***** Get Services *****/

    /***** Set InsuranceName *****/
    function setInsuranceName()
    {
        var InsuranceName = $("#InsuranceId").children(':selected').html();
        $("#InsuranceName").val( InsuranceName );
    }
    $("#InsuranceId").change(function(){ setInsuranceName(); });
    $(document).ready(function(){ setInsuranceName(); });
    /***** End: Set InsuranceName *****/

    /***** Set CartonSizeName *****/
    function setCartonSizeName()
    {
        var CartonSizeName = $("#CartonSizeId").children(':selected').html();
        $("#CartonSizeName").val( CartonSizeName );
    }
    $("#CartonSizeId").change(function(){ setCartonSizeName(); });
    $(document).ready(function(){ setCartonSizeName(); });
    /***** End: Set CartonSizeName *****/

    /***** Change State *****/
    $("#Sender_StateId").on('change', function(){
        $("#Sender_townId").html('<option>دریافت اطلاعات ...</option>');
        $.ajax({
            type: "POST",
            url: $("#admin_ajax_url").val(),
            data : {
                action : "ajaxPostbarStateTownsHTML",
                security: $("#nonce_ajaxPostbarStateTownsHTML").val(),
                stateId : $(this).val()
            },
            success: function (result) {
                $("#Sender_townId").html(result);
                getServices();
            }
        });
    });
    $("#Reciver_StateId").on('change', function(){
        $("#Reciver_townId").html('<option>دریافت اطلاعات ...</option>');
        $.ajax({
            type: "POST",
            url: $("#admin_ajax_url").val(),
            data : {
                action : "ajaxPostbarStateTownsHTML",
                security: $("#nonce_ajaxPostbarStateTownsHTML").val(),
                stateId : $(this).val()
            },
            success: function (result) {
                $("#Reciver_townId").html(result);
                getServices();
            }
        });
    });
    /***** End: Change State *****/
    
    /***** Change Town *****/
    $("#Sender_townId").on('change', function(){ getServices(); });
    $("#Reciver_townId").on('change', function(){ getServices(); });
    /***** End: Change Town *****/

    /***** Change Service *****/
    $("body").on('change', "input[name=ServiceId]", function(){
        //Insurance
        $("#InsuranceId").html('<option>دریافت اطلاعات ...</option>');
        $.ajax({
            type: "POST",
            url: $("#admin_ajax_url").val(),
            data : {
                action : "ajaxPostbarInsurancesHTML",
                security: $("#nonce_ajaxPostbarInsurancesHTML").val(),
                serviceId : $("input[name=ServiceId]:checked").val()
            },
            success: function (result) {
                $("#InsuranceId").html(result);
                setInsuranceName();
            }
        });

        //CartonSize
        $("#CartonSizeId").html('<option>دریافت اطلاعات ...</option>');
        $.ajax({
            type: "POST",
            url: $("#admin_ajax_url").val(),
            data : {
                action : "ajaxPostbarCartonSizeHTML",
                security: $("#nonce_ajaxPostbarCartonSizeHTML").val(),
                serviceId : $("input[name=ServiceId]:checked").val()
            },
            success: function (result) {
                $("#CartonSizeId").html(result);
                setCartonSizeName();
            }
        });
    });
    /***** End: Change Service *****/

    /***** Send Order To Postex *****/
    $("#submit_order_postbar").on("click" , function(e){
        e.preventDefault();
        if(confirm('آیا از  صحت داده ها و ثبت سفارش در سامانه باربری اطمینان دارید؟')) 
        {
            var checkFormValidation = validateNewOrderFormFields();
            if( checkFormValidation.is_valid )
            {            
                $("#postbar_shipping_loading").show();
                $.ajax({
                    type: "POST",
                    url: $("#admin_ajax_url").val(),
                    data : {

                        action : 'ajaxPostbarNewOrder',
                        security: $("#nonce_ajaxPostbarNewOrder").val(),
                        woo_post_id : $("#postex_post_id").val(),

                        //فروشنده
                        Sender_FristName : $("#Sender_FristName").val(),
                        Sender_LastName : $("#Sender_LastName").val(),
                        Sender_mobile : $("#Sender_mobile").val(),
                        Sender_StateId : $("#Sender_StateId").val(),
                        Sender_townId : $("#Sender_townId").val(),
                        Sender_City : $("#Sender_City").val(),
                        Sender_PostCode : $("#Sender_PostCode").val(),
                        Sender_Address : $("#Sender_Address").val(),
                        Sender_Email : $("#Sender_Email").val(),
                        SenderLat : $("#SenderLat").val(),
                        SenderLon : $("#SenderLon").val(),
                        InsuranceName : $("#InsuranceName").val(),
                        CartonSizeName : $("#CartonSizeName").val(),
                        boxType : $("#boxType").val(),
                        HasAccessToPrinter : $("#HasAccessToPrinter").val(),
                        AgentSaleAmount : $("#AgentSaleAmount").val(),
                        printLogo : $("#printLogo").val(),
                        notifBySms : $("#notifBySms").val(),
                        discountCouponCode : $("#discountCouponCode").val(),


                        //خریدار
                        ServiceId : $("input[name=ServiceId]:checked").val(),
                        Reciver_FristName : $("#Reciver_FristName").val(),
                        Reciver_LastName : $("#Reciver_LastName").val(),
                        Reciver_mobile : $("#Reciver_mobile").val(),
                        receiver_ForeginCountry : '',
                        receiver_ForeginCountryName : '',
                        Reciver_StateId : $("#Reciver_StateId").val(),
                        Reciver_townId : $("#Reciver_townId").val(),
                        Reciver_City : $("#Reciver_City").val(),
                        Reciver_PostCode : $("#Reciver_PostCode").val(),
                        Reciver_Address : $("#Reciver_Address").val(),
                        Reciver_Email : $("#Reciver_Email").val(),
                        PaymentType : $("#paymentType").val(),
                        IsCOD : $("#IsCOD").val(),


                        //کالا
                        GoodsType : $("#GoodsType").val(),
                        ApproximateValue : $("#ApproximateValue").val(),
                        CodGoodsPrice : $("#CodGoodsPrice").val(),
                        Weight : $("#Weight").val(),
                        length : $("#length").val(),
                        width : $("#width").val(),
                        height : $("#height").val(),
                        Count : $("#Count").val(),

                        //حمل و نقل سنگین    
                        UbbraTruckType : "", //req if heavy 1
                        VechileOptions : "", //req if heavy 1
                        UbbarPackingLoad : "", //req if heavy 1
                        dispatch_date : "", //req if heavy 1
                    },
                    success: function (response) {
                        var result = JSON.parse(response);
                        $("#postbar_shipping_loading").hide();
                        if(result.status == "success")
                        {
                            var successMessage = result.message + "<br />";
                            successMessage = successMessage + "سفارش شما در سامانه باربری پستِکس با شناسه " + result.orderId + " به ثبت رسید.";
                            var invoice_link = result.site_url + "/wp-content/plugins/postbar-shipping/libs/orederInvoice.php?orderid=" + result.orderId + "&token=" + result.token;
                            successMessage = successMessage + "<div class='postex-mt-15'><a class='postex-pdf-btn' href='"+invoice_link+"' target='_blank'>دریافت فاکتور</a></div>"; 
                            $("#postbarNewOrderMessage").html( successMessage );
                        }
                        else
                        {
                            $("#postbarNewOrderMessage").html(result.message);
                        }                        
                    }
                });
            }
            else
            {
                alert( checkFormValidation.msg );
            }
        } 
        else 
        {
            // Do nothing!
        } // End Confirmation
    });
    /***** End: Send Order To Postex *****/

    /***** Other Events to call Get Services *****/ 
    $("#boxType").on('change', function(){ getServices(); });
    $("#Weight, #length, #width, #height").on('keyup', function(){ getServices(); });
    /***** End : Other Events to call Get Services *****/ 

    /**** Change payment method *****/
    function handlePaymentMethod(){
        if($("#paymentType").val() == "cod")
            $("#CodGoodsPrice_row").show();
        else    
            $("#CodGoodsPrice_row").hide();
    }
    $(document).ready(function(){ handlePaymentMethod(); });
    $("#paymentType").on('change', function(){ 
        handlePaymentMethod();        
        getServices(); 
    });
    /**** End: Change payment method *****/

}); // End jQuery;