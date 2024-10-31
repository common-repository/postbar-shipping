
function postbarErrNotify(errors)
{
    if(errors)
    {
        var container_element = document.getElementById("postbar_errs_ntfy_container");
        if(!container_element)
        {
            container_element = document.createElement("div");
            container_element.setAttribute("id", "postbar_errs_ntfy_container");
            document.body.appendChild(container_element);
        }
        for(var i=0; i<errors.length; i++)
        {
            var child_node = document.createElement("div");
            child_node.setAttribute("class", "postbar-err-holder");
            var closer = "<div class='postbar-err-holder-closer' onclick='postbarCloseErr(this)'>x</div>";
            var errContent = "<div class='postbar-err-content'>اخطار پستِکس : "+errors[i]+"</div>";
            child_node.innerHTML = closer + errContent ;
            container_element.appendChild(child_node);
        }
    }
}

function postbarCloseErr(e)
{
    e.parentNode.parentNode.removeChild(e.parentNode);
}


jQuery(function($){

    /***** Get Shipping Services By Ajax *****/
    function getShippingServices()
    {
        if( $("#shipping_rows_container").length )
        {
            $("#shipping_rows_container").html('درحال دریافت اطلاعات ...');
            $.ajax({
                type: "POST",
                url: $("#postbarShippingModalAjaxUrl").val(),
                data : {
                    action : "ajaxPostbarServicesHTML",
                    security: $("#nonce_ajaxPostbarServicesHTML").val(),
                    postbar_reciever_stateId : $("#postbar_reciever_stateId").val(),
                    postbar_reciever_townId : $("#postbar_reciever_townId").val(),
                    postbar_reciever_listType : $("#postbar_reciever_listType").length ? $("#postbar_reciever_listType").val() : 0,
                    postbar_UserSelected_ServiceId : $("input[name=postbar_UserSelected_ServiceId]").length ? $("input[name=postbar_UserSelected_ServiceId]:checked").val() : 0,
                    postbar_IsCOD : $("#postbar_IsCOD").length ? $("#postbar_IsCOD").val() : 0,
                },
                success: function (result) {
                    $("#shipping_rows_container").html(result);
                }
            });
        }
    }
    /***** End: Get Shipping Services By Ajax *****/

    /***** check conditional shipping *****/
    function checkConditionalShipping()
    {
        //hide elements
        $("#postbar_reciever_listType_container").hide();
        $("#postbar_IsCOD_container").hide();
        $("#postex_shipping_services_container").hide();

        //show message
        $("#postbar_reciever_listType_container").before("<div id='postex_checkConditionalShipping_msg'>در حال بررسی سرویسهای حمل و نقل ...</div>");

        //check is there a condition by ajax
        $.ajax({
            type: "POST",
            url: $("#postbarShippingModalAjaxUrl").val(),
            data : {
                action : "ajaxCheckConditionalShipping",
                security: $("#nonce_ajaxCheckConditionalShipping").val(),
                postbar_reciever_stateId : $("#postbar_reciever_stateId").val(),
                postbar_reciever_townId : $("#postbar_reciever_townId").val(),
            },
            success: function (result) {
                $("#postex_checkConditionalShipping_msg").remove();
                if(result=="false")
                {
                    $("#postbar_reciever_listType_container").show();
                    $("#postbar_IsCOD_container").show();
                    $("#postex_shipping_services_container").show();
                }
            }
        });
    }
    /***** End: check conditional shipping *****/

    // Shipping Modal Selectors
    if( $("#postex-shipping-modal-selector").length )
    {
        /***** open modal *****/
        $('body').on('click' , '#postex_shipping_open_modal' , function(e){
            e.preventDefault();
            $('#postexShippingModalSelector_container').show();
            checkConditionalShipping();
        });
        $('.postex-checkout-change-statecity #postex_shipping_open_modal').click(function(e){
            e.preventDefault();
            $('#postexShippingModalSelector_container').show();
            checkConditionalShipping();
        });
        /***** End: open modal *****/

        /***** close modal or cancel *****/
        $('#postex-close-modal-icon, #postex_modal_btn_cancel').click(function(e){
            e.preventDefault();
            $('#postexShippingModalSelector_container').hide();
        });
        /***** End: close modal or cancel *****/

        /***** Change State *****/
        $("body").on('change', "#postbar_reciever_stateId", function () {
            $("#postbar_reciever_townId").html('<option>دریافت اطلاعات ...</option>');
            $.ajax({
                type: "POST",
                url: $("#postbarShippingModalAjaxUrl").val(),
                data : {
                    action : "ajaxPostbarStateTownsHTML",
                    security: $("#nonce_ajaxPostbarStateTownsHTML").val(),
                    stateId : $(this).val()
                },
                success: function (result) {
                    $("#postbar_reciever_townId").html(result);
                    checkConditionalShipping();
                    getShippingServices();
                }
            });
        });
        /***** End: Change State *****/

        /***** Change Town *****/
        $("body").on("change" , "#postbar_reciever_townId", function(){ checkConditionalShipping(); getShippingServices(); });
        /***** End: Change Town *****/

        /***** Change List Type *****/
        $("body").on("change" , "#postbar_reciever_listType", function(){ getShippingServices(); });
        /***** End: Change List Type *****/

        /***** Change payment method *****/
        $("body").on("change" , "#postbar_IsCOD", function(){ getShippingServices(); });
        /***** End: Change payment method *****/

        /***** Click on shipping rows *****/
        $("body").on("click", "#shipping_rows_container .shipping-row", function(){
            $(this).find("input[type=radio]").prop("checked", true);
        });
        /***** End: Click on shipping rows *****/

        /***** modal confirm changes *****/
        function postex_modal_confirm_changes(){
            //loading on
            $("#postex_modal_btn_save").addClass('loading');

            //set sission
            var PUSID_element = $("input[name=postbar_UserSelected_ServiceId]").length;
            $.ajax({
                type: "POST",
                url: $("#postbarShippingModalAjaxUrl").val(),
                data : {
                    action : "ajaxPostbarSetSessions",
                    security: $("#nonce_ajaxPostbarSetSessions").val(),
                    postbar_reciever_stateId : $("#postbar_reciever_stateId").val(),
                    postbar_reciever_stateTitle : $("#postbar_reciever_stateId option:selected").text(),
                    postbar_reciever_townId : $("#postbar_reciever_townId").val(),
                    postbar_reciever_townTitle : $("#postbar_reciever_townId option:selected").text(),
                    postbar_reciever_listType : $("#postbar_reciever_listType").length ? $("#postbar_reciever_listType").val() : 0,
                    postbar_UserSelected_ServiceId : PUSID_element ? $("input[name=postbar_UserSelected_ServiceId]:checked").val() : 0,
                    postbar_UserSelected_ServicePrice : PUSID_element ? $("input[name=postbar_UserSelected_ServiceId]:checked").data('price') : 0,
                    postbar_UserSelected_ServiceTitle : PUSID_element ? $("input[name=postbar_UserSelected_ServiceId]:checked").data('title') : 0,
                    postbar_IsCOD : $("#postbar_IsCOD").length ? $("#postbar_IsCOD").val() : 0,
                },
                success: function (result) {
                    //loading off
                    $("#postex_modal_btn_save").removeClass('loading');

                    //close modal
                    $('#postexShippingModalSelector_container').hide();

                    //calculate shipping cost
                    if( $('#postex-cart-shipping-calc-form').length )
                    {
                        $("#postex-cart-shipping-calc-form").submit();
                    }
                    if( $('form.checkout').length )
                    {
                        $('body').trigger('update_checkout');
                        //hidden inputs
                        $("input[name=billing_postbar_reciever_stateId]").val( $("#postbar_reciever_stateId").val() );
                        $("input[name=billing_postbar_reciever_townId]").val( $("#postbar_reciever_townId").val() );
                        $("input[name=billing_postbar_UserSelected_ServiceId]").val( PUSID_element ? $("input[name=postbar_UserSelected_ServiceId]:checked").val() : 0 );
                        $("#billing_state").val( $("#postbar_reciever_stateId option:selected").text() );
                        $("#billing_city").val( $("#postbar_reciever_townId option:selected").text() );
                        //disabled selects
                        $("#billing_postbar_reciever_stateId option:selected").val( $("#postbar_reciever_stateId").val() );
                        $("#billing_postbar_reciever_stateId option:selected").text( $("#postbar_reciever_stateId option:selected").text() );
                        $("#billing_postbar_reciever_townId option:selected").val( $("#postbar_reciever_townId").val() );
                        $("#billing_postbar_reciever_townId option:selected").text( $("#postbar_reciever_townId option:selected").text() );
                    }
                }
            });
        }
        $("#postex_modal_btn_save").click(function(e){
            e.preventDefault();
            postex_modal_confirm_changes();
        });
        /***** End: modal confirm changes *****/

        /***** primary data of state and town in checkout *****/
        if( $('form.checkout').length )
        {
            $(document).ready(function(){
                $("#billing_state").val( $("#postbar_reciever_stateId option:selected").text() );
                $("#billing_city").val( $("#postbar_reciever_townId option:selected").text() );
            });
        }
        /***** End: primary data of state and town in checkout *****/

        /***** cart update by quantity *****/
        var should_get_postex_services = false;
        $('.woocommerce').on('change', 'input.qty', function(){

            should_get_postex_services = true;

            if ( cart_update_timeout !== undefined ) {
                clearTimeout( cart_update_timeout );
            }

            var cart_update_timeout = setTimeout(function() {
                $(".woocommerce [name='update_cart']").trigger("click");
            }, 1500 );

        });

        $( document.body ).on( 'updated_cart_totals', function(){
            if(should_get_postex_services){
                should_get_postex_services = false;
                checkConditionalShipping();
                getShippingServices();
                postex_modal_confirm_changes();
            }
        });
        /***** End: cart update by quantity *****/

    }


});
