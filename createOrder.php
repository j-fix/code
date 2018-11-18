<?php
class Remonline
{
    protected $api_url = 'https://api.remonline.ru/';
    protected $tokeninfo = [];
    
    private $api_key = '';
    private $cURL = null;
    
    public function __construct($api_key) {
        if(!isset($api_key)) exit();
        $this->api_key = $api_key;
        $this->cURL = curl_init();
        curl_setopt_array($this->cURL, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_REFERER => 'http://tbot.my/'
        ));
        
        if(file_exists(__CLASS__.'.json')){
            $this->tokeninfo = json_decode(file_get_contents(__CLASS__.'.json'),true);
        }
        
        $this->__checkToken();
    }
    
    public function __destruct() {
        if($this->cURL){
            curl_close($this->cURL);
        }
        if(!isset($this->tokeninfo['renew']) || $this->tokeninfo['renew'] === TRUE){
            file_put_contents(__CLASS__.',json', json_encode($this->tokeninfo));
        }
    }
    
    public function __checkToken()
    {
        if(!isset($this->tokeninfo['token']) || time() - $this->tokeninfo['time'] >= 10*60 ){
            $this->__getToken($this->api_key);
        }
    }
    
    public function __getToken($api_key){
        curl_setopt($this->cURL, CURLOPT_POST, 1);
        curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "token/new");
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, array("api_key" => $api_key));
        $result = json_decode(curl_exec($this->cURL));
        $this->tokeninfo = array('token' => $result->token, "time" => time(), "renew" => false);
    }
    
    public function getBranches()
    {
        $this->__checkToken();
        curl_setopt($this->cURL, CURLOPT_POST, 0);
        curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "branches/?" . http_build_query(array("token" => $this->tokeninfo['token'])));
        return curl_exec($this->cURL);
        
    }
    
    public function getOrdertypes()
    {
        $this->__checkToken();
        curl_setopt($this->cURL, CURLOPT_POST, 0);
        curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "order/types/?" . http_build_query(array("token" => $this->tokeninfo['token'])));
        return curl_exec($this->cURL);
    }
    
    public function createOrder($data)
    {
        $this->__checkToken();
        $data['token'] = $this->tokeninfo['token'];
        curl_setopt($this->cURL, CURLOPT_POST, 1);
        curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "order/");
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $data);
        return curl_exec($this->cURL);
    }
    
    public function createClient($data)
    {
      //  error_reporting(0);
        $this->__checkToken();
        $data['token'] = $this->tokeninfo['token'];
        curl_setopt($this->cURL, CURLOPT_POST, 1);
        curl_setopt($this->cURL, CURLOPT_URL, $this->api_url . "clients/");
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $data);
        return curl_exec($this->cURL);
    }
    
    public function customfields()
    {
        
    }
    public function token()
    {
        return $this->tokeninfo;
    }
}
$name = 'Name';
$phone = array('phone[]'=>777777777); //phone: array[string]
$email = 'test@mail.com';
$brand = 'Apple';
$model = 'iphone 6';
$serial = '1234567890';
$malfunction = 'Всё не работает - ничего не помагает';
$remonline = new remonline("$api-key");
//var_dump($remonline);
print '<pre>';
echo $remonline->getBranches();
print '<br>';
echo $remonline->getOrdertypes();
//die();

$ph = array('phone[]'=> '77777777');

$userdata = array(
    'email' =>$email,
  //'phone' =>$phone,
    'name' => $name,
);
print_r($userdata);
$dat = json_decode($remonline->createClient($userdata));
print 'userdata:'.'<br>';
//print_r($dat);
echo '<br/><br/>';
$dataord = array(
    'branch_id'=>29131,
    'order_type'=>48939,      // 48939 По гарантии, 48938 Платный ;
    'brand' => $brand,             
    'model'=>$model,
    'serial'=>$serial,
    'malfunction'=>$malfunction,
  //  'client_id'=>$dat->data->id
);
print_r($dataord);

var_dump($dataord);
echo '<br/>';
echo $remonline->createOrder($dataord);
print '</pre>';

?>
