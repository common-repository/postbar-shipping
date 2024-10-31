<?php

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_shipping_calculator' ); ?>

<form id="postex-cart-shipping-calc-form" class="woocommerce-shipping-calculator" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
    <button id="postex_shipping_open_modal" class='postex-shipping-open-modal'>تغییر آدرس</button>
</form>

<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>
