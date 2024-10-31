<?php

// Postbar Help
function postbar_woo_shipping_help(){
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'شما سطح دسترسی لازم برای مشاهده این قسمت را ندارید.' ) );
	}
    ?>

    <!-- wrap -->
	<div class="wrap">
        <!-- Header -->
        <div id="postbar-hlp-head">
            <div class="container">
                <div>
                    <ul id="postbar-hlp-top-nav">
                        <li> <a href="#settings">تنظیمات</a> </li>
                        <li> <a href="#about">درباره ما</a> </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h1>به سرویس جامع حمل و نقل پستِکس خوش آمدید</h1>
                        <div>
                            <h2>ویژگیهای افزونه حمل و نقل پستِکس</h2>
                            <ul class="features-list">
                                <li>سرویس های متنوع شرکت پست</li>
                                <li>همکاری با شرکتهای مختلف پستی مانند پست بار، چاپار، اسنپ باکس و ...</li>
                                <li>استعلام قیمت لحظه ای سرویسهای مختلف</li>
                                <li>امکان انتخاب سرویس پستی توسط خریدار</li>
                                <li>امکان رهگیری مرسولات توسط خریدار در پنل کاربری ووکامرس</li>
                                <li>دریافت فاکتور حمل و نفل</li>
                                <li>امکان تعریف نرخ ثابت برای خریدار</li>
                                <li>امکان تعریف هزینه حمل و نقل رایگان برای خریدار</li>
                                <li>امکان ایجاد شرط برای حمل و نقل رایگان</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/shipping-banner-1.png' ?>" alt="حمل و نقل پستِکس">
                        </div>
                    </div>                
                </div>            
            </div>            
        </div>
        <!-- End: Header -->
        <!-- video -->
        <div id="postex-hlp-video">
            <div class="container">
                <div class="postex_player_container">
                <div id="27034668517"><script type="text/JavaScript" src="https://www.aparat.com/embed/ame3J?data[rnddiv]=27034668517&data[responsive]=yes"></script></div>
                </div>
            </div>
        </div>
        <!-- End: video -->
        <!-- settings -->
        <div id="postbar-hpl-settings">
            <a name="settings"></a>
            <div class="container">
                <h2>تنظیمات افزونه حمل و نقل پستِکس</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-1-1.png' ?>" alt="منو">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-step-num">1</div>
                        <div class="setting-info">
                            پس از نصب افزونه پستِکس، قسمتی تحت عنوان همین نام در پنل وردپرس اضافه خواهد شد.
                            به قسمت تنظیمات وارد شوید.
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="setting-step-num">2</div>
                        <div class="setting-info">
                            جهت استفاده از افزونه حمل و نقل پستِکس، لازم است در سایت 
                            <a href="https://postex.ir/" target="_blank">پستِکس</a>
                            دارای حساب کاربری باشید. پس ابتدا در این سایت ثبت نام کرده و سپس جهت اتصال به سامانه باربری،
                            <b>نام کاربری و رمزعبور</b>
                            خود را وارد کنید و روی  
                            <b>اتصال به سامانه باربری</b>
                            کلیک کنید.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-2-1.png' ?>" alt="لاگین">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-3.png' ?>" alt="سایر تنظیمات">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-step-num">3</div>
                        <div class="setting-info">
                            پس از اتصال به سامانه باربری، سایر تنظیمات این صفحه را بر حسب نیاز خود تکمیل نمایید و تنظیمات را ذخیره کنید.
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="setting-step-num">4</div>
                        <div class="setting-info">
                            به قسمت
                            <b>پیکربندی ووکامرس</b>
                            رفته و در تب
                            <b>همگانی</b> ،
                            علاوه بر تنظیم کلیه موارد لازم است، 
                            <b>مکان پیش فرض مشتری</b>
                            را تعیین کنید.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-4-1.png' ?>" alt="فعال کردن محاسبه گر حمل و نقل در سبد خرید">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-5-1.png' ?>" alt="فعال کردن محاسبه گر حمل و نقل در سبد خرید">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-step-num">5</div>
                        <div class="setting-info">
                            در ادامه به قسمت
                            <b>پیکربندی ووکامرس</b>
                            رفته و در تب
                            <b>حمل و نقل</b> ،
                            وارد قسمت
                            <b>گزینه های حمل و نقل</b>
                            شوید. در این قسمت لازم است
                            <b>محاسبه‌گر هزینه ارسال در برگه سبدخرید</b>
                            فعال باشد. در صورتی که این گزینه فعال نیست، تیک فعال شدن آن را بزنید و ذخیره کنید.
                        </div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="setting-step-num">6</div>
                        <div class="setting-info">
                            سپس در همین تب حمل و نقل که هستید، وارد قسمت
                            <b>پستِکس</b>
                            شوید. در اینجا نیز لازم است روش حمل و نقل پستِکس را 
                            <b>فعال</b>
                            کنید و آن را ذخیره کنید. 
                            <br />                           
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-6-1.png' ?>" alt="فعال کردن پستِکس">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-img-7-1.png' ?>" alt="ارسال سفارش برای باربری">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-info">
                            تنظیمات به پایان رسید. 
                            حال براحتی میتوانید سفارشات کاربران خود،
                            که در قسمت سفارشات ووکامرس ثبت می شوند را برای باربری ارسال کنید.                      
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <!-- End: settings -->
        <!-- About -->
        <div id="postbar-hpl-about">
            <a name="about"></a>
            <div class="container">
                <h2>درباره ما</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="img-wrapper">
                            <img src="<?php echo PostexShipping::plugin_url().'/assets/images/help-about-1.png' ?>" alt="درباره ما">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="about-text">
                            <p>
                                افزونه حمل و نقل پستِکس، برای اولین بار در ایران جهت تسهیل و مدیریت باربری در فروشگاه های اینترنتی فارسی و استفاده از 
                                خدمات شرکت های پستی مختلف ارائه شده است.
                            </p>
                            <p>
                                به منظور هرچه بهتر شدن و ارائه خدمات بهتر، از شما فروشنده گرامی خواهشمندیم تا نظرات و 
                                پیشنهادات خود را با ما درمیان بگذارید تا با هربار بروزرسانی این افزونه،
                                قابلیت های آن توسعه یافته و هرچه بیشتر به هدف خود که همانا خدمت رسانی به فروشگاه های اینترنتی
                                فارسی می باشد نزدیک گردیم.
                            </p>
                            <p>
                                <b>راه های ارتباط با ما :</b>
                            </p>
                            <p>
                                وب سایت : 
                                <a href="https://postex.ir/" target="_blank">postex.ir</a>  
                            </p>
                            <p>
                                ایمیل : 
                                info@postex.ir  
                            </p>
                            <p>
                                تلفن :
                                <span>91300250-021</span>                                   
                            </p>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <!-- End: About -->
    </div>
    <!-- End: wrap -->
    
	<?php
}