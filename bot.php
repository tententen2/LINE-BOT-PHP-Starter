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
    		foreach ($array as $key => $jsons) {
    			foreach($jsons as $key => $value) {
    				if($key == 'time'){
    					echo $value;
    					$timee = $value;
    				}

    				if($key == 'detail'){
    						if($value == 'Dora')
 							$result_text11 = $value;
 							$tine = $timee;
					}
    			}

    		}

    		// foreach ($obj1['Accessory']['KW0kAtaRoKuucaQEtsm'] as $key => $value) {
    		// 	$result_text11 = $value['date'];
    		// }




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



			$messages = [
				'type' => 'text',
				'text' => 'ผลการค้นหา :'.$result_text.'ความยาว2 '.$obj.'date '.$result_text11
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
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

			echo $result . "\r\n";
		}
	}
}
echo "OK";