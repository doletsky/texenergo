<?php


namespace Clientste\Api;


class Router extends Init
{
    private static $apiPath;
    private static $apiVersion;
    private static $controller;
    private static $action;
    private static $params;
    private $parameter;	


    // MAIN METHODS
    public function __construct()
    {
	$file_options = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/rest_api_options_clients.php';
	if (file_exists($file_options)) {
		include($file_options);
		if (isset($REST_API_OPTIONS)) {
        		$this->parameter = $REST_API_OPTIONS;
			//echo "Конструктор:Router";print_r($this->parameter);
		}
	  }
	}

    public function start()
    {
	//echo "Router:";print_r($aParam);
	//$this->parameter = $aParam;
	//print_r($this->parameter);
        $path_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        // Api path
        self::$apiPath = strtolower(current($path_parts));
        array_shift($path_parts);

        // Get version
        if ($this->checkUseVersion()) {

            if (current($path_parts)) {
                self::$apiVersion = strtolower(current($path_parts));
                array_shift($path_parts);
            }
        }

        // Get controller
        if (current($path_parts)) {

            self::$controller = strtolower(current($path_parts));
            array_shift($path_parts);
        }

        // Get action
        if (current($path_parts)) {

            self::$action = strtolower(current($path_parts));
            array_shift($path_parts);
        }

        // Get params
        switch (parent::getMethod()) {

            case 'GET':
                if (count($path_parts) > 0) {
                    // if original get-request
                    if (strstr($_SERVER['REQUEST_URI'], '?', false) !== false) {

                        $path_parts = $_SERVER['QUERY_STRING'];
                        $path_parts = explode('&', $path_parts);

                        $tmp = [];

                        foreach ($path_parts as $item) {

                            $item = explode('=', $item);
                            $tmp[urldecode($item[0])] = urldecode($item[1]);
                        }

                        $path_parts = $tmp;
                    }
                }
                break;

            case 'POST':
                if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
                    $path_parts = json_decode(file_get_contents('php://input'), true);
                } else {
                    $path_parts = $_POST;
                }
                break;

            case 'PUT':
                if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
                    $path_parts = json_decode(file_get_contents('php://input'), true);
                }
                break;

            case 'DELETE':
                if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
                    $path_parts = json_decode(file_get_contents('php://input'), true);
                }
                break;

            case 'OPTIONS':
                if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
                    $path_parts = json_decode(file_get_contents('php://input'), true);
                }
                break;
        }

        self::$params = $path_parts;

        // Run controller
        if ($this->getController() && $this->getAction()) {

            $controller = new Controller();
            $controller->run();

        } else {

            Response::BadRequest();
        }

        die();
    }


    // ADDITIONAL METHODS


    // PARAMETERS

    private function checkUseVersion()
    {
	//echo 'проаверка версий:'.$this->parameter['USE_VERSIONS'];
	//die();
        //return (isset(parent::getParameter()['USE_VERSIONS'])) ? true : false;
	return (isset($this->parameter['USE_VERSIONS'])) ? true : false;
    }
    public function getApiPath()
    {
        return self::$apiPath;
    }
    public function getApiVersion()
    {
        return self::$apiVersion;
    }
    public function getController()
    {
        return self::$controller;
    }
    public function getAction()
    {
        return self::$action;
    }
    public function getParam()
    {
        return self::$parameter;
    }
    public function getParameter()
    {
	//echo "$sParameter:".$sParameter;
	//echo "Параметр-:"; print_r($this->parameter);
	//die();
        return $this->parameter;
    }

    public function getParameters()
    {
	
	//echo "Паратеры:".self::$params;
        return self::$params;
    }
}