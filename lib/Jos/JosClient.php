<?php

namespace Jos;

/*
defined('JOS_ENV') || define('JOS_ENV', 'sandbox');

defined('JOS_APP_KEY') || define('JOS_APP_KEY', '');
defined('JOS_SECRET_KEY') || define('JOS_SECRET_KEY', '');
defined('JOS_REDIRECT_URI') || define('JOS_REDIRECT_URI', '');
defined('JOS_ACCESS_TOKEN') || define('JOS_ACCESS_TOKEN', null);
 */

class JosClient {

  const CSRF_TOKEN = 1;
  const CSRF_AUTHORIZE = 2;

  public static $conf;
  protected static $_instance;
  protected $_appkey;
  protected $_secretKey;

  protected $_authorizeUrl = 'http://auth.360buy.com/oauth/authorize';
  protected $_tokenUrl = 'http://auth.360buy.com/oauth/token';
  protected $_gatewayUrl = "http://gw.api.360buy.com/routerjson";

  protected $_apiVersion = '2.0';
  protected $checkRequest = false;

  public static function getInstance() {
    if (! isset(self::$_instance)) {
      self::_init();
      self::$_instance = new JosClient();
      self::$_instance->reload();
    }
    return self::$_instance;
  }

  private static function _init() {
    self::$conf = \Yaf\Registry::get("config")->jingdong;
  }

  public function getConf() {
    $config = self::$conf;
    return $config->toArray();
  }

  public function reload() {
    $this->_appkey = self::$conf->appkey;
    $this->_secretKey= self::$conf->secretkey;
    $this->_redirectUri = self::$conf->redirect_uri;
    $this->_access_token = self::$conf->access_token;
    if (\Yaf\Application::app()->environ() != 'product') {
      $this->_sandbox();
    }
  }

  private function _sandbox() {
    $this->_authorizeUrl = 'http://auth.sandbox.360buy.com/oauth/authorize';
    $this->_tokenUrl = 'http://auth.sandbox.360buy.com/oauth/token';
    $this->_gatewayUrl = 'http://gw.api.sandbox.360buy.com/routerjson';
  }

  public function getAuthUrl() {
    $param = array(
      'response_type' => 'code',
      'client_id' => $this->_appkey,
      'redirect_uri' => $this->_redirectUri,
      'state' => $this->mkCsrf(self::CSRF_AUTHORIZE),
      'scope' => 'read',
    );
    return $this->_authorizeUrl . '?' . http_build_query($param);
  }

  public function refreshAccessToken() {
    $param = array(
      'grant_type' => 'refresh_token',
      'client_id' => $this->_appkey,
      'client_secret' => $this->_secretKey,
      'refresh_token' => $this->_access_token,
      'redirect_uri' => $this->_redirectUri,
      'scope' => 'read',
      'state' => $this->mkCsrf(self::CSRF_TOKEN)
    );
    $json = $this->curl($this->_tokenUrl, $param);
    $json = iconv('gbk', 'utf-8', $json);
    $json = json_decode($json);
    if (isset($json->code) && isset($json->error_description)) {
      throw new \Jos\Exception\JosApiException($json->error_description, intval($json->code));
    }
    self::$conf->access_token = $json->access_token;
    return $json;
  }

  public function fetchAccessToken($code) {   
    $param = array(
      'grant_type' => 'authorization_code',
      'client_id' => $this->_appkey,
      'client_secret' => $this->_secretKey,
      'code' => $code,
      'redirect_uri' => $this->_redirectUri,
      'scope' => 'read',
      'state' => $this->mkCsrf(self::CSRF_TOKEN)
    );  
    $json = $this->curl($this->_tokenUrl, $param);
    $json = iconv('gbk', 'utf-8', $json);
    $json = json_decode($json);
    if (isset($json->code) && isset($json->error_description)) {
      throw new \Jos\Exception\JosApiException($json->error_description, intval($json->code));
    }
    self::$conf->access_token = $json->access_token;
    return $json;
  }


  public function getVersion() {
    return '20131216';
  }

  public function execute(JosRequest $request, $session = null) {
    $result = new \stdClass();
    if ($this->checkRequest) {
      try {
        $request->check();
      } catch (Exception $e) {
        $result->code = $e->getCode();
        $result->zh_desc = "api请求参数验证失败";
        $result->en_desc = $e->getMessage();
        return $result;
      }
    }
    // 组装系统参数
    $sysParams['app_key'] = $this->_appkey;
    $sysParams['v'] = $this->_apiVersion;
    $sysParams['method'] = $request->getApiMethod();
    $session = $this->_access_token;
    $sysParams['timestamp'] = date('Y-m-d H:i:s');

    // 获取业务参数
    $apiParams['360buy_param_json'] = $request->getAppJsonParams();
    // 签名
    $sysParams['sign'] = $this->generateSign(array_merge($sysParams, $apiParams));

    $requestUrl = $this->_gatewayUrl . '?' . http_build_query($sysParams);

    // 发送http请求
    try {
      //echo($requestUrl . "</br></br>");
      $resp = $this->curl($requestUrl, $apiParams);
    } catch (Exception $e) {
      $result->code = $e->getCode();
      $request->zh_desc = "curl发送http请求失败";
      $result->en_desc = $e->getMessage();
      return $result;
    }
    // 解析返回结果
    $respWellFormed = false;
    //echo ($resp); echo "</br></br>";
    $respObject = self::jsonDecode($resp);
    //var_dump(self::jsonDecode('{"error_response": {"code":"19","zh_desc":"无效access_token","en_desc":"Invalid access_token"}}'));

    if (null !== $respObject) {
      $respWellFormed = true;
      foreach ($respObject as $propKey => $propValue) {
        $respObject = $propValue;
      }
    }
    if (false === $respWellFormed) {
      $result->code = 1;
      $result->zh_desc = "api返回数据错误或程序无法解析返回参数";
      $result->en_desc = "HTTP_RESPONSE_NOT_WELL_FORMED";
      return $result;
    }
    return $respObject;
  }

  public function refreshToken() {
  }

  private static function jsonDecode($str) {
    //echo ($str); echo "</br></br>";
    return json_decode($str, true, 512);
    //return PhplutilsJSON::decode($str, true);
    /*
    if (defined(JSON_BIGINT_AS_STRING)) {
      return json_decode($str, false, 512, JSON_BIGINT_AS_STRING);
    } else {
      return PhplutilsJSON::decode($str);
    }
     */
  }

  /**
   * 签名
   *
   * @param $params 业务参数            
   * @return void
   */
  private function generateSign($params) {
    if ($params != null) { // 所有请求参数按照字母先后顺序排序
      ksort($params);

      // 定义字符串开始 结尾所包括的字符串
      $stringToBeSigned = $this->_secretKey;
      // 把所有参数名和参数值串在一起
      foreach ($params as $k => $v) {
        $stringToBeSigned .= "$k$v";
      }
      unset($k, $v);

      // 把venderKey加在字符串的两端
      $stringToBeSigned .= $this->_secretKey;
    } else {
      // 定义字符串开始 结尾所包括的字符串
      $stringToBeSigned = $this->_secretKey;
      // 把venderKey加在字符串的两端
      $stringToBeSigned .= $this->_secretKey;
    }
    // 使用MD5进行加密，再转化成大写

    return strtoupper(md5($stringToBeSigned));
  }

  public function curl($url, $postFields = null) {
    $ch = \curl_init();
    \curl_setopt($ch, CURLOPT_URL, $url);
    \curl_setopt($ch, CURLOPT_FAILONERROR, false);
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // https 请求
    if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
      \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    if (is_array($postFields) && 0 < count($postFields)) {
      \curl_setopt($ch, CURLOPT_POST, true);
      $postMultipart = false;
      foreach ($postFields as $k => $v) {
        if ('@' == substr($v, 0, 1)) {
          $postMultipart = true;
          break;
        }
      }
      unset($k, $v);
      if ($postMultipart) {
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
      } else {
        \curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
      }
    }
    $reponse = \curl_exec($ch);

    if (\curl_errno($ch)) {
      throw new \Jos\Exception\JosSdkException(\curl_error($ch), 0);
    } else {
      $httpStatusCode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if (200 !== $httpStatusCode) {
        throw new \Jos\Exception\JosSdkException($reponse, $httpStatusCode);
      }
    }
    \curl_close($ch);
    return $reponse;
  }

  private function mkCsrf ($key)
  {
    // TODO
    // $v = uniqid('', true);
    // $_SESSION['jos_' . $key] = $v;
    return '';
  }

}
