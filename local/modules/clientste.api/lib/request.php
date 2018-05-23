<?php


namespace Clientste\Api;


class Request extends Router
{
    public static function get()
    {
	$params = parent::getParameters();
	foreach ($params as $key => $value) { 
		$params[$key] = strip_tags($value);
	}
        $ar = [
            //'DATE' => date('Y-m-d H:i:s'),
	    'request_date' => date('Y-m-d H:i:s'),
            //'REQUEST_METHOD' => parent::getMethod(),
            'request_method' => parent::getMethod(),
            //'IP_ADDRESS' => parent::getRealIpAddr(),
            'ip_address' => parent::getRealIpAddr(),
            //'COUNTRY_CODE' => parent::getCountryCode(),
            //'CONTROLLER' => parent::getController(),
            'object' => parent::getController(),
            //'ACTION' => parent::getAction(),
            'action' => parent::getAction(),
            'parameters' => array_change_key_case($params,CASE_UPPER)
        ];
        //echo "Параметры:";print_r(parent::getParameters());echo "\n";
        if (parent::getApiVersion()) {
            //$ar['API_VERSION'] = parent::getApiVersion();
            $ar['api_version'] = parent::getApiVersion();
        }

        if ($_SERVER['HTTP_AUTHORIZATION_TOKEN']) {
            $ar['AUTHORIZATION_TOKEN'] = $_SERVER['HTTP_AUTHORIZATION_TOKEN'];
        }

        return $ar;
    }
}