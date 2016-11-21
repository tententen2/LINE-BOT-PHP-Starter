<?php
$access_token = 'rbyP45xdfrt81l84a4luzx9sZp0XUb0Ap6mKkaX7ljvAwVFNTR4X3Bzk5DlNsWATTcUD6XSLFI8wUTlozlhkAP9wxLemKSBavkmBr1kg21R0bHU2DZSXPJCynH69ScZZEJNY0g59fcCrva2C2DO8iAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			 $ch3 = curl_init();
    		curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch3, CURLOPT_RETURNTRANSFER,true);
    		curl_setopt($ch3, CURLOPT_URL,'https://e-auction-c1430.firebaseio.com/product.json');
    		$retValue = curl_exec($ch3);          
    		curl_close($ch3);
    		$obj1 = json_decode($retValue,true);
    		foreach ($obj1 as $key => $jsons) {
    			foreach($jsons as $key => $value) {
    				foreach($value as $key => $kk){
    						if($key == 'detail'){
    							if(strcasecmp($kk,$text) == 0){
    								$ff = $value['time'];
    								$money = $value['priceforbit'];
    								$name = $kk;
    							}
    						}
    				}
    			}

    		}

			$ch1 = curl_init();
			curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch1, CURLOPT_URL, 'https://th.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text);
			$result1 = curl_exec($ch1);
			curl_close($ch1);
			$obj = json_decode($result1, true);
			foreach($obj['query']['pages'] as $key => $val){
				$result_text = $val['extract'];
			}
			if(empty($result_text)){
				$result_text = 'ไม่พบข้อมูล';
			}


			$tmetme = json_encode($text);

			$messages = [
				'type' => 'text',
				'text' => $name."\r\n\r\n".'เริ่มประมูลวันที่ '.substr($ff,0,10)."\r\n".'เวลา '.substr($ff,11,18)."\r\n".'ราคาประมูลครั้งละ '.$money.' บาท !!'."\r\n\r\n".'ไปลงทะเบียนกันเลย ><'.$tmetme
				// 'text' => 'ผลการค้นหา :'.$result_text.'ความยาว '.$timee.'date '.$result_text11.'ประเภท1 '.$ff
			];

			$image = [
				'type' => 'image',
				'originalContentUrl' => 'https://www.eff.org/files/tor-https-1.png',
				'previewImageUrl' => 'https://www.eff.org/files/tor-https-1.png'

			];

			$jsondata = [
				'type' => 'template',
				'altText' => 'this is a buttons template',
				'template' => {
					'type' => 'buttons',
					'thumbnailImageUrl' => 'https://www.eff.org/files/tor-https-1.png',
					'title' => 'Menu',
					'text' => 'Please'
					'actions' => [
						{
							'type' => 'postback',
							'label1' => 'buy',
							'date' => 'action=buy&itemid=123'	
						},{
							'type' => 'postback',
							'label1' => 'buy1',
							'date' => 'action=buy&itemid=1231'
						},{	
							'type' => 'uri',
							'label1' => 'buy1s',
							'uri' =>  'http://example.com/page/123'
						}
					]
				}
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$jsondata],
			];
			
			// $data1 = [
			// 	'replyToken' => $replyToken,
			// 	'messages' => [$tem1plat0e],
			// ];
			$post = json_encode($data);
			echo $post;
			// $post1 = json_encode($data1);

			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			// echo $result . "\r\n";

			// $ch4 = curl_init($url);
			// curl_setopt($ch4, CURLOPT_CUSTOMREQUEST, "POST");
			// curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch4, CURLOPT_POSTFIELDS, $post1);
			// curl_setopt($ch4, CURLOPT_HTTPHEADER, $headers);
			// curl_setopt($ch4, CURLOPT_FOLLOWLOCATION, 1);
			// $result1 = curl_exec($ch4);
			// curl_close($ch4);


			// echo $result.$result1 . "\r\n";

			// $data = [
			// 	'replyToken' => $replyToken,
			// 	'messages' => [$image],
			// ];

			// $post = json_encode($data);
			// $post1 = json_encode($data1);

			// $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			// $ch = curl_init($url);
			// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			// $result = curl_exec($ch);
			// curl_close($ch);
		}
	}
}
echo "OK";