<?php

class Postbar_API 
{

    // callAPI
    public function callAPI($method, $endpoint, $data, $headers)
    {
        $base_url = 'https://postex.ir/api/';
        $url = $base_url.$endpoint;
        $args = array(
            'method' => $method,
            'body' => $data,
            'headers' => $headers,
            'timeout' => 300
        );
        $response = wp_remote_request( $url, $args );

        if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)) 
        {
            return json_decode($response['body']);
        }
        else 
        {
            return false;
        }
    }
    // End: callAPI
    
    // Login
    public function login($username, $password)
    {
        $method = 'POST';
        $endpoint = 'login';
        $data = array('username' => $username, 'password' => $password);
        $headers = '';

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);
        
        $response = new stdClass();
        if(!empty($result_obj->ErrorList))
        {   
            $response->success = 0;
            $response->errors = $result_obj->ErrorList;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Login

    // Get Token
    public function getToken()
    {
        $method = 'POST';
        $endpoint = "IsTokenExpire";
        $token = get_option('postbar_woo_shipping_token');
        $data = array('Token' => $token);
        $headers = '';

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        if(!$result_obj->IsValid)
        {
            $postbar_username = get_option('postbar_woo_shipping_opts')["postbar_username"];
            $postbar_password = get_option('postbar_woo_shipping_opts')["postbar_password"];

            $postbar_user_data = Postbar_API::login($postbar_username, $postbar_password);
            if($postbar_user_data->success && $postbar_user_data->data->Token)
            {
                update_option( 'postbar_woo_shipping_token', $postbar_user_data->data->Token );
                $token = get_option('postbar_woo_shipping_token');
            }
        }

        return $token;
    }
    // End: Get Token
    
    // Get States
    public function getState()
    {
        $method = "GET";
        $endpoint = 'state/getState';
        $data = '';
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }
        
        return $response;
    }
    // Get States   
    
    // Get Towns By StateId
    public function getTownsByStateId($stateId)
    {
        $method = 'GET';
        $endpoint = 'town/getTownsByStateId';
        $data = array('stateId' => $stateId);
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Towns By StateId

    // Get Services
    public function getServices($data_array)
    {
        $method = 'POST';
        $endpoint = 'getServices';
        $data = $data_array;
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!empty($result_obj->ErrorList))
        {   
            $response->success = 0;
            $response->errors = $result_obj->ErrorList;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Services

    // Get Insurance List
    public function getInsuranceList($serviceId)
    {
        $method = 'GET';
        $endpoint = 'getInsuranceList';
        $data = array('serviceId' => $serviceId);
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Insurance List

    // Get CartonSize List
    public function getCartonSizeList($serviceId)
    {
        $method = 'GET';
        $endpoint = 'getCartonSizeList';
        $data = array('serviceId' => $serviceId);
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get CartonSize List
    
    // Get CartonSize List
    public function getPrice($data_array)
    {
        $method = 'POST';
        $endpoint = 'getPrice';
        $data = $data_array;
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get CartonSize List

    // New Order
    public function newOrder($data_array)
    {
        $method = 'POST';
        $endpoint = 'checkout/newOrder';
        $data = $data_array;
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: New Order

    // Get Wallet Charge
    public function getWalletChargeRate($mobileNo)
    {
        $method = 'GET';
        $endpoint = 'customer/getWalletChargeRate';
        $data = array( "mobileNo" => $mobileNo);
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End: Get Wallet Charge

    // Order Tracking
    public function orderTracking($orderId)
    {
        $method = 'GET';
        $endpoint = "order/TrackingNumber/$orderId";
        $data = '';
        $headers = 'token:'.Postbar_API::getToken();

        $result_obj = Postbar_API::callAPI($method, $endpoint, $data, $headers);

        $response = new stdClass();
        if(!$result_obj) 
        {   
            $response->success = 0;
        }
        else
        {
            $response->success = 1;
            $response->data = $result_obj;
        }

        return $response;
    }
    // End : Order Tracking
    
}
// End: Postbar_API Class
