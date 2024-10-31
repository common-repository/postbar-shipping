
<!-- Defaults Receiver Data -->
<?php 
    $reciver_default_stateId = get_option('postbar_woo_shipping_opts')["reciver_default_stateId"] ? get_option('postbar_woo_shipping_opts')["reciver_default_stateId"] : 1;
    $reciver_default_stateTitle = get_option('postbar_woo_shipping_opts')["reciver_default_stateTitle"];
    $reciver_default_townId = get_option('postbar_woo_shipping_opts')["reciver_default_townId"];                
    $reciver_default_townTitle = get_option('postbar_woo_shipping_opts')["reciver_default_townTitle"];                
?>
<div>     
    <div class="pws-container-title">
        <div class="title-text">اطلاعات پیش فرض خریدار</div>
        <div class="dashicons dashicons-cart"></div>                            
    </div>
    <div class="pws-container">
        <p>این مقادیر به عنوان اطلاعات پیش فرض مشتری خواهند بود. مشتری شما هنگام خرید اطلاعات را برحسب نیاز خود تغییر میدهد.</p>
        <table class="pws-wide-form-table">
            <tr>
                <th>
                    <label for="reciver_default_stateId">استان پیش فرض مشتری</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[reciver_default_stateId]" id="reciver_default_stateId">
                        <?php echo postbarStatesHTML($reciver_default_stateId); ?>
                    </select>
                    <input type="hidden" name="postbar_woo_shipping_opts[reciver_default_stateTitle]" id="reciver_default_stateTitle" value="<?php echo $reciver_default_stateTitle; ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="reciver_default_townId">شهرستان پیش فرض مشتری</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[reciver_default_townId]" id="reciver_default_townId">
                        <?php echo postbarStateTownsHTML($reciver_default_townId , $reciver_default_stateId); ?>
                    </select>
                    <input type="hidden" name="postbar_woo_shipping_opts[reciver_default_townTitle]" id="reciver_default_townTitle" value="<?php echo $reciver_default_townTitle; ?>">
                </td>
            </tr>
        </table>
        <?php submit_button( 'ذخیره تغییرات', 'primary', '', false, '' ); ?>
    </div>
    <script>
        jQuery(function($){

            function setReciverStateTitle(){
                var reciverStateTitle = $("#reciver_default_stateId option:selected").text();
                $("#reciver_default_stateTitle").val(reciverStateTitle);
            }

            function setReciverTownTitle(){
                var reciverTownTitle = $("#reciver_default_townId option:selected").text();
                $("#reciver_default_townTitle").val(reciverTownTitle);
            }

            /***** Reciver Change State *****/
            $("#reciver_default_stateId").on('change', function () {
                setReciverStateTitle();
                $("#reciver_default_townId").html('<option>دریافت اطلاعات ...</option>');
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data : {
                        action : "ajaxPostbarStateTownsHTML",
                        security: '<?php echo wp_create_nonce( "nonce-ajaxPostbarStateTownsHTML" ); ?>',
                        stateId : $(this).val()
                    },
                    success: function (result) {
                        $("#reciver_default_townId").html(result);
                        setReciverTownTitle();
                    }
                });
            });
            /***** End: Reciver Change State *****/

            /***** Reciver Change Town *****/
            $("#reciver_default_townId").on('change', function () {
                setReciverTownTitle();                            
            });
            /***** End: Reciver Change Town *****/

            $(document).ready(function(){
                setReciverStateTitle();
                setReciverTownTitle();
            });

            
        }); // End jQuery;
    </script>
</div>
<!-- End: Defaults Receiver Data -->