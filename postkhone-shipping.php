<?php

/**
 * Plugin Name: Postex Shipping
 * Plugin URI: https://postex.ir/
 * Description: ارسال و رهگیری مرسولات پستی در ووکامرس به همراه محاسبه خودکار هزینه حمل و نقل براساس آدرس مبدا، مقصد، وزن و ابعاد مرسوله، با استفاده از سرویسهای پستی شرکتهای مختلف مانند پستبار، اوبار، اسنپ باکس و چاپار و غیره
 * Version: 3.3
 * Author: Postex Team
 * Author URI: https://postex.ir/
 * Requires at least: 4.4
 * Tested up to: 5.8.3
 * php version 7.3
 * Text Domain: postbar
 */

defined('ABSPATH') or die('No script kiddies please!');

/***** Check if WooCommerce is active *****/
if (!function_exists('get_plugins')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
if (is_plugin_active('woocommerce/woocommerce.php')) {

    // Class PostbarShipping
    class PostexShipping
    {

        protected $plugin_name;
        protected $plugin_slug;
        protected $plugin_url;
        public $plugin_version;

        // Constructor
        function __construct()
        {
            $this->set_plugin_info();

            $this->include_files();

            add_action('activated_plugin', [$this, 'postex_shipping_activation']);
            register_deactivation_hook(
                __FILE__,
                [$this, 'postex_shipping_deactivation']
            );
            add_action(
                'woocommerce_shipping_init',
                [$this, 'woocommerce_postbar_init']
            );
        }
        // End: Constructor

        // plugin activation
        function postex_shipping_activation($plugin)
        {
            if ($plugin == plugin_basename(__FILE__)) {
                exit(wp_redirect(admin_url('admin.php?page=woo-postbar-settings')));
            }
        }
        // End: plugin activation

        // plugin deactivation
        function postex_shipping_deactivation()
        {
            require_once('libs/postbar-shipping-method-class.php');
            $WC_Shipping_Postbar = new WC_Shipping_Postbar;
            $process_admin_options = array(
                $WC_Shipping_Postbar,
                'process_admin_options'
            );
            $process_admin_options[0]->enabled = 'no';
            $process_admin_options[0]->settings['enabled'] = 'no';
            do_action(
                'woocommerce_update_options_shipping_postbar_shipping',
                $process_admin_options
            );
        }
        // End: plugin deactivation

        // plugin Info
        function set_plugin_info()
        {
            $this->plugin_slug = basename(__FILE__, '.php');
            $this->plugin_url = plugins_url(null, __FILE__);
            if (!function_exists('get_plugins')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }
            $plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
            $plugin_file = basename((__FILE__));
            $this->plugin_version = $plugin_folder[$plugin_file]['Version'];
            $this->plugin_name = $plugin_folder[$plugin_file]['Name'];
        }
        // End: plugin Info

        public static function plugin_path()
        {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

        public static function plugin_url()
        {
            return untrailingslashit(plugin_dir_url(__FILE__));
        }

        function include_files()
        {
            include_once($this->plugin_path() . '/libs/functions.php');
        }

        function woocommerce_postbar_init()
        {
            include_once($this->plugin_path() . '/libs/postbar-shipping-method.php');
        }
    }
    // End: Class PostbarShipping

    new PostexShipping;
}
/***** End: Check if WooCommerce is active *****/
