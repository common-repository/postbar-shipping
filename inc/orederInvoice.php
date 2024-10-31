<?php

    $order_id = $_GET["orderid"];
    $token = $_GET["token"];
    $url = "https://postex.ir/api/order/getpdfinvoice/$order_id";    

    $headers = array('Content-type: application/json', 'token: '.$token);

    $curl = curl_init(DONWLOAD_REPORT);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $file = curl_exec($curl);

    if ($file === false) 
    {
        curl_close($curl);
        die('خطا در دریافت اطلاعات. لطفا بعدا مجددا تلاش کنید.');
    }

    curl_close($curl);

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=order_$order_id.pdf");
    echo $file;

?>