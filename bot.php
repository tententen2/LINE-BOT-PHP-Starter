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

			$text_split = explode('-', $text);

			$url = 'https://api.line.me/v2/bot/message/reply';

			if($text_split[0] == "ค้นหา"){
				$ch1 = curl_init();
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch1, CURLOPT_URL, 'https://th.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_split[1]);
				$result1 = curl_exec($ch1);
				curl_close($ch1);
				$obj = json_decode($result1, true);
				foreach($obj['query']['pages'] as $key => $val){
					$result_text = $val['extract'];
				}
				if(empty($result_text)){
					$ch1 = curl_init();
					curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch1, CURLOPT_URL, 'https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_split[1]);
					$result1 = curl_exec($ch1);
					curl_close($ch1);
					$obj = json_decode($result1, true);
					foreach($obj['query']['pages'] as $key => $val){ 
						$result_text = $val['extract']; 
					}
				}
				if(empty($result_text)){
					$result_text = 'ไม่พบข้อมูล';
				}


				$jsondata = [
					'type' => 'text',
					'text' => "ผลการค้นหา :"."\r\n".$result_text
				];

			}else if($text_split[0] == "ประมูล"){
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
    								if(strpos(strtolower($kk), strtolower($text_split[1])) !== false){
    									$ff = $value['time'];
    									$money = $value['priceforbit'];
    									$name = $kk;
    									$urlimg = $value['url'];
    								}
    							}
    						}
    					}
    				}
    				if(empty($name)){
    					$jsondata = [
							'type' => 'text',
							'text' => "ไม่มีการประมูล2 : ".$text_split[1]
						];
    				}else{
    		// 			$jsondata = [
						// 	"type" => "template",
						// 	"altText" => "this is a buttons template",
						// 	"template" => [
						// 	"type" => "buttons",
						// 	"thumbnailImageUrl" => $urlimg,
						// 	"title" => $name,
						// 	"text" => "เริ่มประมูลวันที่ ".substr($ff,0,10)."\r\n"."เวลา ".substr($ff,11,18)."\r\n".'บิตขั้นต่ำ '.$money.' บาท !!',
						// 	"actions" => [
						
						// 		]
						// 	]
						// ];

						$ff = ,
								[
								"thumbnailImageUrl" => $urlimg,
								"title" => $name,
								"text" => "เริ่มประมูลวันที่ ".substr($ff,0,10)."\r\n"."เวลา ".substr($ff,11,18)."\r\n".'บิตขั้นต่ำ '.$money.' บาท !!',
								"actions" => [
						
									]
								];

						$jsondata = [
							"type" => "template",
							"altText" => "this is a buttons template",
							"template" => [
							"type" => "carousel",
							"columns" => [
								[
								"thumbnailImageUrl" => $urlimg,
								"title" => $name,
								"text" => "เริ่มประมูลวันที่ ".substr($ff,0,10)."\r\n"."เวลา ".substr($ff,11,18)."\r\n".'บิตขั้นต่ำ '.$money.' บาท !!',
								"actions" => [
						
									]
								].$ff
							]
							]
						];
					}
			}else{
				$jsondata = [
					'type' => 'text',
					'text' => "กรุณาพิมพิ์ให้ถูกต้องตามเงื่อนไข :"."\r\n"."1. ถ้าต้องการค้นหาให้พิมพ์"."\r\n"." ค้นหา-ตามด้วยสิ่งที่ต้องการค้น"."\r\n"."2. ถ้าต้องการหาสินค้าประมูลให้พิมพ์"."\r\n"." ประมูล-ชื่อของประมูล"
				];
			}

			$data = [
				'replyToken' => $replyToken,
				'messages' => [$jsondata],
			];
			$post = json_encode($data);

			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
}
echo "OK";