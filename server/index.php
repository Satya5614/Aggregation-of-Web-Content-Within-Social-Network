<?php
	
	include('dbConnect.php');
  	require_once('lib/facebook/facebook.php');
  	$config = array(
    	'appId' => '465523613530810',
    	'secret' => 'f4a5f0f3b6fd87ad38ba7cb9ea4a95ba',
  	);
  	include("lib/phptextcat/phptextcat.class.php");

  	$analyser = new PhpTextCat('business_finance', 'entertainment', 'knowledge', 'lifestyle', 'news', 'sports');
  	
	if ($_GET['query'] == 'getUid') {
		$facebook = new Facebook($config);
  		$user_id = $facebook->getUser();
		//$uid = rand(1000,9999);
		if($user_id){
			$uid = $user_id;
			$query = "INSERT into userDetails(userId) VALUES ('$uid') ";
			mysql_query($query);
			echo $uid;
		}
		else{
			$loginUrl = $facebook->getLoginUrl($params);
			//header('Location: '.$loginUrl);
			echo 0;
		}
	}

	if ($_GET['queryType'] == 'created') {
		$userId = $_GET['userId'];
		$sessionId = $_GET['sessionId'];
		$tabId = $_GET['tabId'];
		$tabUrl = $_GET['tabUrl'];
		$tabTitle = $_GET['tabTitle'];
		$visitCount = $_GET['visitCount'];
		$typedCount = $_GET['typedCount'];
		$transitionType = $_GET['transitionType'];
		$category = $analyser->guessLanguage($tabTitle);
		$query = "INSERT into browserSessionInfo(userId, browserSessionId, tabId, tabUrl, tabTitle,category, visitCount, typedCount, transitionType) VALUES ('$userId', '$sessionId', '$tabId', '$tabUrl', '$tabTitle',  '$category', '$visitCount', '$typedCount', '$transitionType') ";
		mysql_query($query) or die(error());
	}

	if ($_GET['queryType'] == 'updated') {
		$userId = $_GET['userId'];
		$sessionId = $_GET['sessionId'];
		$tabId = $_GET['tabId'];
		$tabUrl = $_GET['tabUrl'];
		$tabTitle = $_GET['tabTitle'];
		$visitCount = $_GET['visitCount'];
		$typedCount = $_GET['typedCount'];
		$transitionType = $_GET['transitionType'];
		$query = "SELECT * FROM browserSessionInfo WHERE (userId='$userId' AND browserSessionId='$sessionId' AND tabId='$tabId') ORDER BY urlOpeningTime DESC LIMIT 1";
		$result = mysql_query($query);
		$value = mysql_fetch_assoc($result);
		$id = $value['id'];
		date_default_timezone_set('Asia/Calcutta');
		$date = date_create();
		$date = date_format($date, 'Y-m-d H:i:s');
		$query = "UPDATE browserSessionInfo SET urlClosingTime ='$date' WHERE id ='$id'";
		mysql_query($query);
		$category = $analyser->guessLanguage($tabTitle);
		$query = "INSERT into browserSessionInfo(userId, browserSessionId, tabId, tabUrl, tabTitle, category, visitCount, typedCount, transitionType) VALUES ('$userId', '$sessionId', '$tabId', '$tabUrl', '$tabTitle', '$category', '$visitCount', '$typedCount', '$transitionType') ";
		mysql_query($query);
	}

	if ($_GET['queryType'] == 'removed') {
		$userId = $_GET['userId'];
		$sessionId = $_GET['sessionId'];
		$tabId = $_GET['tabId'];
		$tabUrl = $_GET['tabUrl'];

		$query = "SELECT * FROM browserSessionInfo WHERE (userId='$userId' AND browserSessionId='$sessionId' AND tabId='$tabId') ORDER BY urlOpeningTime DESC LIMIT 1";
		$result = mysql_query($query);
		$value = mysql_fetch_assoc($result);
		$id = $value['id'];
		date_default_timezone_set('Asia/Calcutta');
		$date = date_create();
		$date = date_format($date, 'Y-m-d H:i:s');
		$query = "UPDATE browserSessionInfo SET urlClosingTime ='$date' WHERE id ='$id'";
		mysql_query($query);
	}
?>