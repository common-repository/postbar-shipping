
<!-- Sender Settings -->
<?php 
    $Sender_FristName = get_option('postbar_woo_shipping_opts')["Sender_FristName"];
    $Sender_LastName = get_option('postbar_woo_shipping_opts')["Sender_LastName"];
    $Sender_mobile = get_option('postbar_woo_shipping_opts')["Sender_mobile"];
    $Sender_StateId = get_option('postbar_woo_shipping_opts')["Sender_StateId"];
    $Sender_townId = get_option('postbar_woo_shipping_opts')["Sender_townId"];
    $Sender_City = get_option('postbar_woo_shipping_opts')["Sender_City"];
    $Sender_PostCode = get_option('postbar_woo_shipping_opts')["Sender_PostCode"];
    $Sender_Address = get_option('postbar_woo_shipping_opts')["Sender_Address"];
    $Sender_Email = get_option('postbar_woo_shipping_opts')["Sender_Email"];
    $SenderLat = get_option('postbar_woo_shipping_opts')["SenderLat"];
    $SenderLon = get_option('postbar_woo_shipping_opts')["SenderLon"];
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
<div>     
    <div class="pws-container-title">
        <div class="title-text">اطلاعات فرستنده کالا</div>
        <div class="dashicons dashicons-id-alt"></div>                            
    </div>
    <div class="pws-container">
        <div class="text-justify">
            اطلاعات زیر به عنوان مقادیر پیش فرض فرستنده کالا استفاده خواهد شد. شما میتوانید برای هر سفارش
            اطلاعات متفاوتی را برای فرستنده کالا ذکر کنید. اما جهت سهولت در ثبت سفارشات خود،
            اطلاعات پیش فرض فرستنده کالا (آدرس فروشگاه یا انبار خود) را ثبت کنید.
        </div>
        <table class="pws-wide-form-table">
            <tr>
                <th>
                    <label for="Sender_FristName">نام فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_FristName]" id="Sender_FristName" value="<?php echo $Sender_FristName ? $Sender_FristName : ""; ?>" placeholder="نام فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_LastName">نام خانوادگی فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_LastName]" id="Sender_LastName" value="<?php echo $Sender_LastName ? $Sender_LastName : ""; ?>" placeholder="نام خانوادگی فرستنده"  />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_mobile">موبایل فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_mobile]" id="Sender_mobile" value="<?php echo $Sender_mobile ? $Sender_mobile : ""; ?>" placeholder="موبایل فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_Email">ایمیل فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_Email]" id="Sender_Email" value="<?php echo $Sender_Email ? $Sender_Email : ""; ?>" placeholder="ایمیل فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_StateId">استان فرستنده</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[Sender_StateId]" id="Sender_StateId">
                        <?php echo postbarStatesHTML($Sender_StateId); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_townId">شهرستان فرستنده</label>                                
                </th>
                <td>
                    <select name="postbar_woo_shipping_opts[Sender_townId]" id="Sender_townId">
                        <?php echo postbarStateTownsHTML($Sender_townId , $Sender_StateId); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_City">نام شهر/بخش/روستا فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_City]" id="Sender_City" value="<?php echo $Sender_City ? $Sender_City : ""; ?>" placeholder="نام شهر/بخش/روستا فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_PostCode">کدپستی فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_PostCode]" id="Sender_PostCode" value="<?php echo $Sender_PostCode ? $Sender_PostCode : ""; ?>" placeholder="کدپستی فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="Sender_Address">آدرس پستی فرستنده</label>                                
                </th>
                <td>
                    <input type="text" name="postbar_woo_shipping_opts[Sender_Address]" id="Sender_Address" value="<?php echo $Sender_Address ? $Sender_Address : ""; ?>" placeholder="آدرس پستی فرستنده" />
                </td>
            </tr>
            <tr>
                <th>
                    <label>
                        موقعیت فرستنده روی نقشه
                        <br />
                        <span class="lable-guide">روی محل مورد نظر خود کلیک کنید.</span>
                    </label>                                
                </th>
                <td>
                    <div id="postexMapContainer"></div>
                    <input type="hidden" name="postbar_woo_shipping_opts[SenderLat]" id="SenderLat" value="<?php echo $SenderLat ? $SenderLat : 35.78114; ?>" />
                    <input type="hidden" name="postbar_woo_shipping_opts[SenderLon]" id="SenderLon" value="<?php echo $SenderLon ? $SenderLon : 51.417238; ?>" />
                </td>
            </tr>            
        </table>
        <?php submit_button( 'ذخیره تغییرات', 'primary', '', false, '' ); ?>
    </div>
</div>
<script>
    jQuery(function($){

        /***** Change State *****/
        $("#Sender_StateId").on('change', function () {
            $("#Sender_townId").html('<option>دریافت اطلاعات ...</option>');
            $.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data : {
                    action : "ajaxPostbarStateTownsHTML",
                    security: '<?php echo wp_create_nonce( "nonce-ajaxPostbarStateTownsHTML" ); ?>',
                    stateId : $(this).val()
                },
                success: function (result) {
                    $("#Sender_townId").html(result);
                }
            });
        });
        /***** End: Change State *****/

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
<!-- End: Sender Settings -->