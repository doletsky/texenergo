<?php


namespace Clientste\Api;


use Bitrix\Main\Application;
use \Bitrix\Main\Authentication;

class Init
{
    const GEOIP = 'Geoip';

    private $parameter;
    //public $parameter;	
    private $pathApi;
    public static $realIp;


    public function __construct()
    {
	$file_options = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/rest_api_options_clients.php';
	if (file_exists($file_options)) {
		include_once($file_options);
		if (isset($REST_API_OPTIONS)) {
        		$this->parameter = $REST_API_OPTIONS;
			//echo "Конструктор:Init";print_r($this->parameter);
			//die();
		}
	}
    }


    // MAIN METHODS


    public function start()
    {
	//echo "Параметры";print_r($this->getParameter());
        // If the module is activated
        if ($this->checkPathApi() && $this->checkUseApi()) {

            // Check filters
            if ($this->checkFilters()) {

                // Set headers
                $this->setHeaders();

                // Run router/dispatch
                $router = new Router();
                $router->start();

            } else {

                // Cross domain
                if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS' && $_SERVER['HTTP_ORIGIN']) {
                    $this->setHeadersPreQuery();

                } else {

                    // Close access
                    Response::DenyAccess();
                }
            }

            die();
        }
    }


    // ADDITIONAL METHODS


    // PARAMETERS

    public function getParameter()
    {
	//echo "getParameter:";print_r($this->parameter);
        return $this->parameter;
    }
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    private function checkUseApi()
    {
        //return ($this->getParameter()->getValue('USE_RESTFUL_API') == 'Y') ? true : false;
	return true;
    }

    // API PATH

    private function getPathApi()
    {
        if (!$this->pathApi) {
            //$this->pathApi = $this->getParameter()->getValue('PATH_RESTFUL_API');
	    if (isset($this->getParameter()['PATH_RESTFUL_API'])) 	
	    	$this->pathApi = $this->getParameter()['PATH_RESTFUL_API'];
	    else
		$this->pathApi = "/rest-tx-api/";
        }

	//echo $this->pathApi; exit("Прервали");
        return $this->pathApi;
    }
    private function checkPathApi()
    {
        $result = false;

        $current_module = explode('/', trim($_SERVER['REQUEST_URI'], '/'))[0];
        $api_module = explode('/', trim($this->getPathApi(), '/'))[0];
	//echo '$current_module:'.$current_module.' $api_module:'.$api_module;
	//exit(" :Прервали");

        if ($current_module == $api_module) {
            $result = true;
        }

        return $result;
    }

    // FILTERS

    private function checkFilters()
    {
        $result = (!$this->checkToken() || !$this->checkFilterCountry() || !$this->checkFilterAddress() || !$this->checkAccessHttps()) ? false : true;

	//echo "checkFilters:".$result."\n";
        return $result;
    }
    private function checkFilterCountry()
    {
        $access = true;

        //if ($this->getParameter()->getValue('USE_LIST_COUNTRY_FILTER') == 'Y') {
        if (isset($this->getParameter()['WHITE_LIST_COUNTRY'])) {
            $ar = $this->getParameter()['WHITE_LIST_COUNTRY'];
            $ar = explode(';', $ar);
            $ar = array_diff($ar, ['']);

            foreach ($ar as &$item) {
                $item = trim($item);
                $item = strtoupper($item);
            }

            if (!in_array($this->getCountryCode(), $ar)) {
                $access = false;
            }
        }

	//echo "checkFilterCountry:".$access." ";
        return $access;
    }
    private function checkFilterAddress()
    {
        $access = true;

        //if ($this->getParameter()->getValue('USE_BLACK_LIST_ADDRESS_FILTER') == 'Y') {
	if (isset($this->getParameter()['BLACK_LIST_ADDRESS'])) {
            // Black list
            $arBlack = $this->getParameter()['BLACK_LIST_ADDRESS'];
            $arBlack = explode(';', $arBlack);
            $arBlack = array_diff($arBlack, ['']);

            foreach ($arBlack as &$item) {
                $item = trim($item);
            }

            if (in_array($this->getRealIpAddr(), $arBlack)) {

                $access = false;
            }
        }

        //if ($this->getParameter()->getValue('USE_WHITE_LIST_ADDRESS_FILTER') == 'Y') {
	if (isset($this->getParameter()['WHITE_LIST_ADDRESS'])) {
            // White list
            $arWhite = $this->getParameter()['WHITE_LIST_ADDRESS'];
            $arWhite = explode(';', $arWhite);
            $arWhite = array_diff($arWhite, ['']);

            foreach ($arWhite as &$item) {
                $item = trim($item);
            }

            $access = (in_array($this->getRealIpAddr(), $arWhite)) ? true : false;
        }

	//echo "checkFilterAddress:".$access." ";
        return $access;
    }
    private function checkAccessHttps()
    {
        $access = true;

        //if ($this->getParameter()->getValue('ONLY_HTTPS_EXCHANGE') == 'Y' && $_SERVER['SERVER_PORT'] != 443) {
	if (isset($this->getParameter()['ONLY_HTTPS_EXCHANGE']) && $_SERVER['SERVER_PORT'] != 443) {
            $access = false;
        }

	//echo "checkAccessHttps:".$access." ";
        return $access;
    }
    private function checkToken()
    {
        $access = true;
	//echo "checkToken:";

        //if ($this->getParameter()->getValue('USE_AUTH_TOKEN') == 'Y') {
	if (isset($this->getParameter()['USE_AUTH_TOKEN'])) {

            $access = false;
	    //echo "Работаем с ключом:".$_SERVER['HTTP_AUTHORIZATION_TOKEN'];
	    //die();		
            if ($_SERVER['HTTP_AUTHORIZATION_TOKEN']) {

                $token = trim($_SERVER['HTTP_AUTHORIZATION_TOKEN']);
                //echo "Токен ".$token;
                //$keyword = str_replace(' ', '', $this->getParameter()['TOKEN_KEYWORD']).':';
		$arParseToken = explode(':', $token);
		$loginUser = trim($arParseToken[0]);
		$keyUser   = trim($arParseToken[1]);
                //$checkKeyword = substr($token, 0, strlen($keyword));

                //if ($checkKeyword != $keyword) {
                //    return false;
                //}

                //$token = trim(substr($token, strlen($keyword)));
		//echo "<br>login:".$loginUser." key:".$keyUser;
		$order = array('sort' => 'asc');
		$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
		$arfilter = array (
			//"LOGIN" => "s.a.sergienko@texenergo.ru",
			//"ACTIVE" => "Y"			
		);
		$rsUsers = null;
		$arfilter = Array('LOGIN_EQUAL' => $loginUser,"ACTIVE" => "Y");
		$arParameters = array(
			"SELECT"=> array("UF_API_KEY"),
			"FIELDS"=>array("ID","LOGIN")				
		);

		$rsUsers = \CUser::GetList(($by = "id"), ($order = "desc"), $arfilter, $arParameters);
        	if ($rsUsers && $arUser = $rsUsers->Fetch()) {
            		//$arParams['LOGIN'] = $arUser['LOGIN'];
			//pr($arUser);
			global $loginUserID;
			$loginUserID = $arUser["ID"];
			if($loginUser != $arUser["LOGIN"]) {
				//echo "Не верный логин";
				$access = false;			
				
			} else  $access = true; 
			if($keyUser != $arUser["UF_API_KEY"]) {
				//echo "Не верный ключ для пользователя";
				$access = false;
				
			} else  $access = true; 
        	}
		//else {
		//	echo "НЕТ прав для указанного пользователя";
		//}
            }
        }

	//echo $access." "; 
        return $access;
    }

    public function getCountryCode()
    {
        // Default country
        $defaultCountryCode = 'RU';

        // Check library availability geoip
        if (self::checkLibraryAvailability(self::GEOIP)) {

            $result = ($countryCode = strtoupper(geoip_country_code_by_name(self::$realIp))) ? $countryCode : $defaultCountryCode;

        } else {

            $result = 'Error: the '.self::GEOIP.' library was not found';
        }

        return $result;
    }
    public function getRealIpAddr()
    {
        if (!self::$realIp) {

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

                self::$realIp = $_SERVER['HTTP_CLIENT_IP'];

            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

                self::$realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];

            } else {

                self::$realIp = $_SERVER['REMOTE_ADDR'];
            }
        }

        return self::$realIp;
    }

    // HEADERS

    private function setHeaders()
    {
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization-Token');

        // Cross domain
        header('Access-Control-Allow-Origin: '.$_SERVER['SERVER_NAME']);

        if (isset($this->getParameter()['USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER'])) {

            $ar = $this->getParameter()['WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN'];

            if (strpos($ar, '*') !== false) {

                header('Access-Control-Allow-Origin: *');

            } else {

                $ar = explode(';', $ar);
                $ar = array_diff($ar, ['']);

                foreach ($ar as &$item) {

                    $item = trim($item);

                    if ($item == $_SERVER['HTTP_ORIGIN']) {

                        header('Access-Control-Allow-Origin: '.$item);
                        break;
                    }
                }
            }
        }
    }
    private function setHeadersPreQuery()
    {
        header('HTTP/1.0 200');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization-Token');
        header('Access-Control-Max-Age: 604800'); // 7 days

        if (isset($this->getParameter()['USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER'])) {

            $ar = $this->getParameter()['WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN'];

            if (strpos($ar, '*') !== false) {

                header('Access-Control-Allow-Origin: *');

            } else {

                $ar = explode(';', $ar);
                $ar = array_diff($ar, ['']);

                foreach ($ar as &$item) {

                    $item = trim($item);

                    if ($item == $_SERVER['HTTP_ORIGIN']) {

                        header('Access-Control-Allow-Origin: '.$item);
                        break;
                    }
                }
            }
        }
    }

    // EXTENSIONS
    public static function checkLibraryAvailability($libraryCode)
    {
        $result = false;

        // Installed extensions
        $extensions = get_loaded_extensions();

        if ($exist = array_search(strtolower($libraryCode), $extensions)) {
            $result = true;
        }

        return $result;
    }

    // DB
    private function DB()
    {
        return Application::getConnection();
    }
}