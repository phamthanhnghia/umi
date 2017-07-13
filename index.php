<?php
	$verify_token = 'your_verify_token';
	if(isset($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe') {
		$challenge = $_REQUEST['hub_challenge'];
		$hub_verify_token = $_REQUEST['hub_verify_token'];
		if ($hub_verify_token === $verify_token) {
			header("HTTP/1.1 200 OK");
			echo $challenge;
		die;
		}
	}

	$input = json_decode(file_get_contents('php://input'), true);
	file_put_contents('data.txt', file_get_contents("php://input") . PHP_EOL, FILE_APPEND);
	// Page ID of Facebook page which is sending message
	$page_id = $input['entry'][0]['id'];
	// User Scope ID of sender.
	$sender_id = $input['entry'][0]['messaging'][0]['sender']['id'];
	// Get Message text if available
	$message = isset($input['entry'][0]['messaging'][0]['message']['text']) ? $input['entry'][0]['messaging'][0]['message']['text']: '' ;
	// Get Postback payload if available
	$postback = isset($input['entry'][0]['messaging'][0]['postback']['payload']) ? $input['entry'][0]['messaging'][0]['postback']['payload']: '' ;

	if($message) {

		//echo "1";
	  $reply = 'Message received: ' . $message;
	  file_put_contents('data.txt', $reply . PHP_EOL, FILE_APPEND);
	  $sender_id = '100006234335167';
	   $responseJSON = '{
	    "recipient":{
	      "id":"'. $sender_id .'"
	    },
	    "message": {
	            "text":"'. $reply .'"
	        }
	  }';
	  $access_token = 'EAACvj0WAemYBACMaTjg2EQRNtSHesW0IWcZAGozoH9m9IQBEWhiGMVjk2loaUdj3ZA94aLHoTVglbvf2LpOgY4y0ok4C9WoAKK2WkQzY5jwpNZAl97gtsLmpFZAMR33m9K8PBejScpZAslrT5wxlDEfmdQLGNYLxBX2U4TbZAlwQZDZD';

	  //Graph API URL
	  $url = 'https://graph.facebook.com/v2.7/me/messages?access_token='.$access_token;
	  // Using cURL to send a JSON POST data
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $responseJSON);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	  $result = curl_exec($ch);
	  curl_close($ch);
	}
?>