<?php
/**
 * @Author: du
 * @Date:   2019-02-09 22:41:11
 * @Last Modified by:   du
 * @Last Modified time: 2019-02-14 00:37:05
 */

$APPID = "";
$APPSECRET = "";
$CALLBACK = "";

if (isset($_GET["code"])) {
	$getTokenApiUrl = "https://oauth.yiban.cn/token/info?code=" . $_GET['code'] . "&client_id={$APPID}&client_secret={$APPSECRET}&redirect_uri={$CALLBACK}";
	$res = sendRequest($getTokenApiUrl);
	if (!$res) {
		throw new Exception('Get Token Error');
	}
	$userTokenInfo = json_decode($res);
	$access_token = $userTokenInfo["access_token"];
} else {
	$postStr = pack("H*", $_GET["verify_request"]);
	if (strlen($APPID) == '16') {
		$postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $APPSECRET, $postStr, MCRYPT_MODE_CBC, $APPID);
	} else {
		$postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $APPSECRET, $postStr, MCRYPT_MODE_CBC, $APPID);
	}
	$postInfo = rtrim($postInfo);
	$postArr = json_decode($postInfo, true);
	if (!$postArr['visit_oauth']) {
		header("Location: https://openapi.yiban.cn/oauth/authorize?client_id={$APPID}&redirect_uri={$CALLBACK}&display=web");
		die;
	}
	$access_token = $postArr['visit_oauth']['access_token'];
}

$userInfoJsonStr = sendRequest("https://openapi.yiban.cn/user/real_me?access_token={$access_token}");
$userInfo = json_decode($userInfoJsonStr);

if ($userInfo->status=="erro"){
    header("location:{$CALLBACK}");
    die("失败啦。给您跳转");
}
function sendRequest($uri) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Yi OAuth2 v0.1');
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_URL, $uri);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array());
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	$response = curl_exec($ch);
	return $response;
}

session_start();
$_SESSION['token']=$access_token;
$_SESSION["info"] = $userInfo->info;
if(isset($_SESSION['URL'])) {
    $url=$_SESSION['URL'];
    var_dump($url);
    unset($_SESSION['URL']);
    header("location:{$url}");
}else{
    header("location:index.php");
}

?>