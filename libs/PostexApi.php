<?php

class  PostexApi
{

    // callAPI
    public static function callAPI($method, $endpoint, $data, $headers)
    {
        $base_url = 'https://postex.ir/api/';
        $url = $base_url . $endpoint;
        if ($endpoint == 'newgetprice') {
            //$data = json_encode($data);
        }
        $args = array(
            'method' => $method,
            'body' => $data,
            'headers' => $headers,
            'timeout' => 300
        );
        $response = wp_remote_request($url, $args);

        if ($endpoint == 'newgetprice') {
            //  echo "<pre>";
            ////print_r($args);
            //print_r($response);
            //exit;
        }
        if (!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)) {
            return json_decode($response['body']);
        } else {
            return false;
        }
    }
    // End: callAPI

    // Login
    public static function login($username, $password)
    {
        $method = 'POST';
        $endpoint = 'login';
        $data = array('Username' => $username, 'Password' => $password);
        $headers = '';

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);
        // print_r($result_obj);
        //print_r($result_obj);exit;
        $response = new stdClass();
        if (!empty($result_obj->ErrorList)) {
            $response->success = 0;
            $response->errors = $result_obj->ErrorList;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Login

    // Get Token
    public static function getToken()
    {
        //$method = 'POST';
        // $endpoint = "IsTokenExpire";
        //$token = get_option('postbar_woo_shipping_token');
        // $data = array('Token' => $token);
        //$headers = '';

        //$result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        // if(!$result_obj->IsValid)
        // {
        $postbar_username = get_option('postbar_woo_shipping_opts')["postbar_username"];
        $postbar_password = get_option('postbar_woo_shipping_opts')["postbar_password"];
        $token = get_option('postbar_woo_shipping_token');
        // print_r($token);
        $postbar_user_data = self::login($postbar_username, $postbar_password);
        if ($postbar_user_data->success && $postbar_user_data->data->Token != $token) {
            update_option('postbar_woo_shipping_token', $postbar_user_data->data->Token);
            $token = get_option('postbar_woo_shipping_token');
        }
        //}

        return $token;
    }
    // End: Get Token

    // Get States
    public static function getState()
    {
        $method = "GET";
        $endpoint = 'state/getState';
        $data = '';
        $headers = array('token' => self::getToken());

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // Get States   

    // Get Towns By StateId
    public static function getTownsByStateId($stateId)
    {
        $method = 'GET';
        $endpoint = 'town/getTownsByStateId';
        $data = array('stateId' => $stateId);
        $headers = 'token:' . self::getToken();

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Towns By StateId

    // Get Services
    public static function getServices($data_array)
    {
        $base_url = 'https://postex.ir/api/newgetprice';
        $result_obj = wp_remote_post(
            $base_url,
            array(
                'method'      => 'POST',
                'timeout'     => 300,
                'headers'     => array('token' => self::getToken(), 'Content-Type'  => 'application/json'),
                'body'        => json_encode($data_array, JSON_UNESCAPED_UNICODE),
            )
        );

        if (is_wp_error($result_obj)) {
            $error_message = $result_obj->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            //echo 'Response:<pre>';
            /*   print_r( array(
                'method'      => 'POST',
                'timeout'     => 300,
                'headers'     => array('token'=>self::getToken(),'Content-Type'  => 'application/json'),
                'body'        => json_encode($data_array,JSON_UNESCAPED_UNICODE ),
            ) );
            echo '</pre>';*/
        }
        //exit;



        $response = new stdClass();
        if (!empty($result_obj->ErrorList)) {
            $response->success = 0;
            $response->errors = $result_obj->ErrorList;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Services

    // Get Insurance List
    public static function getInsuranceList($serviceId)
    {
        $method = 'GET';
        $endpoint = 'getInsuranceList';
        $data = array('serviceId' => $serviceId);
        $headers = 'token:' . self::getToken();

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Insurance List

    // Get CartonSize List
    public static function getCartonSizeList($serviceId)
    {
        $method = 'GET';
        $endpoint = 'getCartonSizeList';
        $data = array('serviceId' => $serviceId);
        $headers = 'token:' . self::getToken();

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get CartonSize List

    // Get CartonSize List
    public static function getPrice($data_array)
    {
        $method = 'POST';
        $endpoint = 'getPrice';
        $data = $data_array;
        $headers = 'token:' . self::getToken();

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get CartonSize List

    // New Order
    public static function newOrder($data_array)
    {
        $method = 'POST';
        $endpoint = 'checkout/panelNewOrder';
        $data = json_encode($data_array);
        //print_r($data);exit;
        $headers =  array('token' => self::getToken(), 'Content-Type'  => 'application/json');

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: New Order

    // Get Wallet Charge
    public static function getWalletChargeRate($mobileNo)
    {
        $method = 'GET';
        $endpoint = 'customer/getWalletBalance';
        $data = array("mobileNo" => $mobileNo);
        $headers = 'token:' . self::getToken();

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Wallet Charge

    // Order Tracking
    public static function orderTracking($orderId)
    {
        $method = 'GET';
        $endpoint = "order/TrackingNumber/$orderId";
        $data = '';
        $headers =  array('token' => self::getToken());


        $result_obj = self::callAPI($method, $endpoint, $data, $headers);
        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End : Order Tracking
    public static function getInvoice($orderId)
    {
        $method = 'GET';
        $endpoint = "order/getpdfinvoice/$orderId";
        $data = '';
        $headers =  array('token' => self::getToken());


        $result_obj = self::callAPI($method, $endpoint, $data, $headers);
        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // Get CartonSize List
    public static function getAllServiceList()
    {
        $method = 'GET';
        $endpoint = 'getServiceList';
        $data = array();
        $headers =  array('token' => self::getToken());

        $result_obj = self::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if (!$result_obj) {
            $response->success = 0;
        } else {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $result_obj;
    }
    public static function showService($serviceid)
    {
        $services = PostexApi::getAllServiceList();
        $allservice = array();
        foreach ($services as $key => $value) {
            $allservice[$value->ServiceId] = $value->ServiceName;
        }
        return $allservice[$serviceid];
    }
}
// End: Postbar_API Class
