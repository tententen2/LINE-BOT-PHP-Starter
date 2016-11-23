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
					if(strlen($result_text) > 1950){
							$result_text = substr($result_text,0,1950);
					}
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
						if(strlen($result_text) > 1950){
						$result_text = substr($result_text,0,1950);
						} 
					}
					
				}
				$result_text = filter_var($result_text, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_STRIP_LOW);
				if(empty($result_text)){
					$result_text = 'ไม่พบข้อมูล';
				}
				$jsondata = [
					'type' => 'text',
					'text' => 'ผลการค้นหา1 :'."\r\n"."\"".$result_text."\""
				];

			}else if($text_split[0] == "ประมูล"){
				$count = 0;
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
    									$ff[$count] = $value['time'];
    									$money[$count] = $value['priceforbit'];
    									$name[$count] = $kk;
    									$urlimg[$count] = $value['url'];
    									$count += 1; 
    								}
    							}
    						}
    					}
    				}
    				if(empty($name[0])){
    					$jsondata = [
							'type' => 'text',
							'text' => "ไม่มีการประมูcล : ".$text_split[1]
						];
    				}else{
    					if(count($ff) == 1){
								$jsondata = [
								"type" => "template",
								"altText" => "this is a buttons template",
								"template" => [
								"type" => "carousel",
								"columns" => [
									[
									"thumbnailImageUrl" => $urlimg[0],
									"title" => $name[0],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[0],0,10)."\r\n"."เวลา ".substr($ff[0],11,18)."\r\n".'บิตขั้นต่ำ '.$money[0].' บาท !!',
									"actions" => [
						
										]
									]
								]
								]
							];
						}else if(count($ff) == 2){
							$jsondata = [
								"type" => "template",
								"altText" => "this is a buttons template",
								"template" => [
								"type" => "carousel",
								"columns" => [
									[
									"thumbnailImageUrl" => $urlimg[0],
									"title" => $name[0],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[0],0,10)."\r\n"."เวลา ".substr($ff[0],11,18)."\r\n".'บิตขั้นต่ำ '.$money[0].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[1],
									"title" => $name[1],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[1],0,10)."\r\n"."เวลา ".substr($ff[1],11,18)."\r\n".'บิตขั้นต่ำ '.$money[1].' บาท !!',
									"actions" => [
						
										]
									]
								]
								]
							];

						}else if(count($ff) == 3){
							$jsondata = [
								"type" => "template",
								"altText" => "this is a buttons template",
								"template" => [
								"type" => "carousel",
								"columns" => [
									[
									"thumbnailImageUrl" => $urlimg[0],
									"title" => $name[0],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[0],0,10)."\r\n"."เวลา ".substr($ff[0],11,18)."\r\n".'บิตขั้นต่ำ '.$money[0].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[1],
									"title" => $name[1],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[1],0,10)."\r\n"."เวลา ".substr($ff[1],11,18)."\r\n".'บิตขั้นต่ำ '.$money[1].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[2],
									"title" => $name[2],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[2],0,10)."\r\n"."เวลา ".substr($ff[2],11,18)."\r\n".'บิตขั้นต่ำ '.$money[2].' บาท !!',
									"actions" => [
						
										]
									]
								]
								]
							];

						}else if(count($ff) == 4 ){
							$jsondata = [
								"type" => "template",
								"altText" => "this is a buttons template",
								"template" => [
								"type" => "carousel",
								"columns" => [
									[
									"thumbnailImageUrl" => $urlimg[0],
									"title" => $name[0],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[0],0,10)."\r\n"."เวลา ".substr($ff[0],11,18)."\r\n".'บิตขั้นต่ำ '.$money[0].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[1],
									"title" => $name[1],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[1],0,10)."\r\n"."เวลา ".substr($ff[1],11,18)."\r\n".'บิตขั้นต่ำ '.$money[1].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[2],
									"title" => $name[2],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[2],0,10)."\r\n"."เวลา ".substr($ff[2],11,18)."\r\n".'บิตขั้นต่ำ '.$money[2].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[3],
									"title" => $name[3],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[3],0,10)."\r\n"."เวลา ".substr($ff[3],11,18)."\r\n".'บิตขั้นต่ำ '.$money[3].' บาท !!',
									"actions" => [
						
										]
									]
								]
								]
							];

						}else{
							$jsondata = [
								"type" => "template",
								"altText" => "this is a buttons template",
								"template" => [
								"type" => "carousel",
								"columns" => [
									[
									"thumbnailImageUrl" => $urlimg[$count-5],
									"title" => $name[$count-5],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[$count-5],0,10)."\r\n"."เวลา ".substr($ff[$count-5],11,18)."\r\n".'บิตขั้นต่ำ '.$money[$count-5].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[$count-4],
									"title" => $name[$count-4],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[$count-4],0,10)."\r\n"."เวลา ".substr($ff[$count-4],11,18)."\r\n".'บิตขั้นต่ำ '.$money[$count-4].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[$count-3],
									"title" => $name[$count-3],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[$count-3],0,10)."\r\n"."เวลา ".substr($ff[$count-3],11,18)."\r\n".'บิตขั้นต่ำ '.$money[$count-3].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[$count-2],
									"title" => $name[$count-2],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[$count-2],0,10)."\r\n"."เวลา ".substr($ff[$count-2],11,18)."\r\n".'บิตขั้นต่ำ '.$money[$count-2].' บาท !!',
									"actions" => [
						
										]
									],[
									"thumbnailImageUrl" => $urlimg[$count-1],
									"title" => $name[$count-1],
									"text" => "เริ่มประมูลวันที่ ".substr($ff[$count-1],0,10)."\r\n"."เวลา ".substr($ff[$count-1],11,18)."\r\n".'บิตขั้นต่ำ '.$money[$count-1].' บาท !!',
									"actions" => [
						
										]
									]
								]
								]
							];

						}
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