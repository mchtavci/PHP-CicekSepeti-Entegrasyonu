<?php
	$apiKey = "XXXXXXX";
	$suan = date('Y-m-d H:i:s');
	$endDate=date('Y-m-d\TH:i:s.000\Z');
	$startDate = date("Y-m-d\TH:i:s.000\Z",strtotime('-10 day',strtotime($suan)));  //3 saat geriye aldım
	$limit=100;
	$page=0;
	$key = "X-API-Key:".$apiKey."";
	$CicekSepeti = new CicekSepeti($key);
	
	do{
		$orders = $CicekSepeti->GetOrders(
		[
			"startDate" => $startDate,
			"endDate" => $endDate,
			"pageSize" => $limit,
			"page" => $page
		]
		);
		echo "<pre>";
		print_r($orders);
		echo "</pre>";
		$itemCount = count($orders->supplierOrderListWithBranch);
		$page++;
		
	}
	while($itemCount == $limit);
	
	
Class CicekSepeti {
    protected static $_apiKey, $_ch;
    
    public function __construct($apiKey) {
        self::$_apiKey = $apiKey;
    }
    
    public function setUrl($url) {
		self::$_ch = curl_init( $url );
		curl_setopt(self::$_ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt(self::$_ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt(self::$_ch, CURLOPT_HTTPHEADER, array(self::$_apiKey, "Content-Type:application/json", "Accept:application/json")) ;
    }
    
    public function GetOrders(array $searchData = Array()) {
		$searchDatas = json_encode($searchData);
		$this->setUrl('https://apis.ciceksepeti.com/api/v1/Order/GetOrders');
		curl_setopt(self::$_ch, CURLOPT_POSTFIELDS, $searchDatas );
		$result = json_decode( curl_exec(self::$_ch) );
		return $result;
	}
 
    public function __destruct() {
		curl_close(self::$_ch);
    }
}
?>