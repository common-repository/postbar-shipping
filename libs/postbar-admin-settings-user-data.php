
<?php if( !$postbar_token ) : ?>
<!-- Not Logged In Message -->
<div class="pws-container pws-alarm">
    <div class="dashicons dashicons-info"></div>
    <div class="text-justify">
        ارتباط شما با سامانه باربری پستِکس برقرار نیست.
        جهت استفاده از خدمات سرویس نوین حمل و نقل پستِکس، ابتدا در
        <a href="https://postex.ir/" target="_blank">سامانه پستِکس</a>
        <a href="https://postex.ir/register" target="_blank"><b>ثبت نام</b></a>
        کنید و سپس با وارد کردن اطلاعات کاربری خود از برقراری ارتباط با این سامانه اطمینان حاصل کنید. 
    </div>               
</div>
<!-- End: Not Logged In Message -->
<?php endif; ?>

<!-- User Data -->
<div class="pws-row">
    <!-- Login -->
    <div class="col-6">
        <div class="pl-15p">
            <div class="pws-container-title">
                <div class="title-text">اتصال به سامانه باربری</div>
                <div class="dashicons dashicons-lock"></div>                         
            </div>
            <div class="pws-container h-200p">
                <div>
                    در صورتی که قبلا در
                    <a href="https://postex.ir/" target="_blank">پستِکس</a>
                    ثبت نام کرده اید، اطلاعات خود را وارد کنید.
                </div>
                <br />
                <table class="pws-form-table">
                    <tr>
                        <th>
                            <label for="pws_username">نام کاربری (موبایل)</label>
                        </th>
                        <td>
                            <input type="text" id="pws_username" name="postbar_woo_shipping_opts[postbar_username]" value="<?php echo $postbar_username; ?>" placeholder="نام کاربری" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="pws_password">رمز عبور</label>
                        </th>
                        <td>
                            <input type="password" id="pws_password" name="postbar_woo_shipping_opts[postbar_password]" value="<?php echo $postbar_password; ?>" placeholder="رمز عبور" />
                        </td>
                    </tr>
                </table>
                <?php 
                    submit_button( 'اتصال به سامانه پستِکس', '', '', false, array( 'id' => 'pws-login-btn' ) );
                ?>
            </div>
        </div>
    </div>
    <!-- End: Login -->
    <!-- user info -->
    <div class="col-4">     
        <div class="pws-container-title">
            <div class="title-text">اطلاعات کاربری</div>                            
        </div>
        <div class="pws-container h-200p">
            <table class="pws-user-info">
                <tr>
                    <th>نام</th>
                    <td><?php echo $postbar_user->FirstName ? $postbar_user->FirstName : "-"; ?></td>
                </tr>
                <tr>
                    <th>نام خانوادگی</th>
                    <td><?php echo $postbar_user->LastName ? $postbar_user->LastName : "-"; ?></td>
                </tr>
                <tr>
                    <th>ایمیل</th>
                    <td><?php echo $postbar_user->Email ? $postbar_user->Email : "-"; ?></td>
                </tr>
                <tr>
                    <th>نام کاربری</th>
                    <td><?php echo $postbar_user->Username ? $postbar_user->Username : "-"; ?></td>
                </tr>
                <tr>
                    <th>موجودی کیف پول شما</th>
                    <td>
                        <?php 
                        echo "-";
                            /*if($postbar_user->Username)
                            {
                                //$walletResult = Postbar_API::getWalletChargeRate($postbar_user->Username);
                                if($walletResult->success)
                                {
                                   // $WalletChargeRate = $walletResult->data->walletChargeRate . " ریال";
                                }
                                else
                                {
                                    $WalletChargeRate = "-";
                                }
                                //echo $WalletChargeRate; 
                            }
                            else
                            {
                                echo "-";
                            }*/
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <!-- End: user info -->                
</div>            
<!-- User Data -->