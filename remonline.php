<?php
error_reporting(E_ALL);
ini_set("display_error", true);
ini_set("error_reporting", E_ALL);
class remonline{
  protected $token_info = array();
  protected $api_url = "https://api.remonline.ru/";
  private   $api_key = "";                          // ВСТАВИТЬ API - КЛЮЧ
  private   $cURL = null;
  public function __construct($api_key){
    if(!isset($api_key)) exit();
    $this->api_key = $api_key;
    $this->cURL = curl_init();
    curl_setopt_array($this->cURL, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_REFERER        => "",
    ));
    if(file_exists(__CLASS__ . ".json")) $this->token_info = json_decode(file_get_contents(__CLASS__ . ".json"), true);
    $this->__checkToken();
  }
  public function __destruct(){
    if($this->cURL)
      curl_close($this->cURL);
    if(!isset($this->token_info["renew"]) || $this->token_info["renew"] === true)
      file_put_contents(__CLASS__ . ".json", json_encode($this->token_info));
  }
  private function __checkToken(){
    if(!isset($this->token_info['token']) || time() - $this->token_info["time"] >= 10 * 60) /* token timeout is 10 min */
      $this->__getToken($this->api_key);
  }
  private function __getToken($api_key){
    curl_setopt($this->cURL, CURLOPT_POST, 1);
    curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "token/new");
    curl_setopt($this->cURL, CURLOPT_POSTFIELDS, array("api_key" => $api_key));
    $result = json_decode(curl_exec($this->cURL));
    $this->token_info = array('token' => $result->token, "time" => time(), "renew" => false);
    //var_dump($result);
  }
  public function getBranches(){                       // ПОЛУЧЕНИЕ СПИСКА МАСТЕРСКИХ КОМПАНИИ
    $this->__checkToken();
    curl_setopt($this->cURL, CURLOPT_POST, 0);
    curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "branches/?" . http_build_query(array("token" => $this->token_info['token'])));
    return curl_exec($this->cURL);
  }
}
$remonline = new remonline("$api_key");
echo '<pre>'. $remonline->getBranches().'</pre>', PHP_EOL;
?>
