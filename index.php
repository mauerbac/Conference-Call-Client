<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * 
limitations under the License. 

Conference Call Landing
Written by Matt Auerbach (mauerbach@twilio.com)

*/
include ('constants.php');
require_once 'src/apiClient.php';
require_once 'src/contrib/apiOauth2Service.php';

session_start();
$client = new apiClient();
$client->setApplicationName(APPLICATION_NAME); 
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setDeveloperKey(DEVELOPER_KEY);
$oauth2 = new apiOauth2Service($client);



if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: conference.php');
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}   
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $user = $oauth2->userinfo->get();

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL); //retrive users email

  $_SESSION['token'] = $client->getAccessToken();

  } else {
   $authUrl = $client->createAuthUrl();
  }


?>

<!doctype html>
<html>
<head><meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style1.css" />
<title>Conference Call Client </title>

</head>
<body style=" background-image: url(img/twiliocon.png);"> //background image
  <img style="position:absolute; top: 16px; left:19px; width:233px; height:68px" src="http://static0.twilio.com/packages/twilio-conference/img/twilio-logo-2012-header.png"> //logo
<div id="content">
  <center><h1>Evangelists Conference Call Portal</h1></center>
  <center><h3>*Twilio Email Required</h3></center>

<?php
  if(isset($authUrl)) {
    print "<center><a id='call-button' class='button' href='$authUrl'>Authenticate</a></center>";
  } else {
   print "<center><a id='call-button' class='button' href='?logout'>Logout</a></center>";
  }
?>
</div>
<? 
$errorcode=$_GET['error'];
  if($errorcode==1){
  echo "<div id='error'><h3><b>Error</b>: You need a Twilio email</h3></div>";    
  }else if($errorcode==2){
   echo "<div id='error'><h3><b>Error</b>: You must login for access </h3></div>";
  }
?>
</body>
</html>
