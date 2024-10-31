<?php
define('WP_USE_THEMES', false);

/** Loads the WordPress Environment and Template */
require ('../../../../wp-load.php');
    $order_id = $_GET["orderid"];
   // $token = $_GET["token"];
    $url = "https://postex.ir/api/order/getpdfinvoice/$order_id";    
    $args = array(
        'headers' => array(
          'Token' => $_GET['token']
        ));
        $response = wp_remote_get( $url, $args );
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=order_$order_id.pdf");
    echo wp_remote_retrieve_body($response);


    

?>