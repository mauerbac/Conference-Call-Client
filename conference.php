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
limitations under the License. */


/*
Conference Call Client
Written by Matt Auerbach (mauerbach@twilio.com)

*/

include ('constants.php');
include 'Services/Twilio.php'; 
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


if (isset($_REQUEST['logout'])) {
    unset($_SESSION['token']);
    $client->revokeToken();
}

if (isset($_GET['code']) || isset($_SESSION['token']) ) {
    if(isset($_SESSION['token'])){
      $client->setAccessToken($_SESSION['token']);
     
      }else{
         $_SESSION['auth_token']= $client->authenticate();
  }
    if ($client->getAccessToken()) {
           $user = $oauth2->userinfo->get();
           $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL)
            $_SESSION['token'] = $client->getAccessToken();
      }
      $tw = strpos($email, "@twilio.com"); //check for twilio domain 

    if($tw !==false){ 
      $clientName='Null';


        //matches email to a Client Name
    if($email=="" || $email==null){
        $clientName="CantRetriveEmail";
      }else if($email=="test@twilio.com"){
        $clientName="Test";
      }else if($email=="Example@twilio.com"){
        $clientName="Example";
      }else{
        $clientName="unauthorized_number";
      }

$token = new Services_Twilio_Capability(ACCOUNT_SID, TAUTH_TOKEN);
$token->allowClientIncoming($clientName);
$token->allowClientOutgoing(APP_ID); 
$token= $token->generateToken();
  
  
?>  

<html>
  <head>
    <title>
 Developer Evangelist Conference
    </title>

<link rel="stylesheet" type="text/css" href="style.css" />
    <!-- @start snippet -->


    <script type="text/javascript" src="http://static.twilio.com/libs/twiliojs/1.0/twilio.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script type="text/javascript">
    
  function setupCall() {
        $('#call-button').unbind();
        $('#call-button').click(function(e) {
            e.preventDefault();
            $('#call-options').fadeIn('slow');
            Twilio.Device.connect();
            $(this).text("Connecting")
            }); 
        $('#call-button').text("Start Call");
    }

    function setupHangup() {
        $('#call-button').unbind("click");
        $('#call-button').click(function(e) {
            e.preventDefault();
            Twilio.Device.disconnectAll();
        });
        $('#call-button').text("Hangup");
    }
    
    Twilio.Device.setup("<?php echo $token ?>");

    Twilio.Device.ready(function (device) {
        setupCall();

    });

    Twilio.Device.error(function (error) {
      $("#call-button").text("Error");
      $("#call-button").removeAttr('href');
      $("#call-status").text(error.message).fadeIn('slow');
    });

    Twilio.Device.connect(function (conn) {
        $("#call-button").text("Connected");
        $("#option1").unbind();
        $("#option2").unbind();
        $("#option3").unbind();
        $("#option4").unbind();
        $("#option1").click(function(e) {
            sendTone(e, conn, '1');
        });
        $("#option2").click(function(e) {
            sendTone(e, conn, '2');
        });
        $("#option3").click(function(e) {
            sendTone(e, conn, '3');
        });
        $("#option4").click(function(e) {
            sendTone(e, conn, '4');
        });
        setupHangup();
    });

    Twilio.Device.disconnect(function (conn) {
      $("#call-status").text("Call ended.").fadeOut('slow');
      $('#call-options').fadeOut('slow');
      setupCall();
    });


 Twilio.Device.presence(function (pres) {
        console.log(pres);
        if (pres.available) {
          // create an item for the client that became available
          $("<li>", {id: pres.from, text: pres.from}).click(function () {
            $("#number").val(pres.from);
            call();
          }).prependTo("#people");
        }
        else {
          // find the item by client name and remove it
          $("#" + pres.from).remove();
        }
      });

    function AutoRefresh(){
        var xmlHttp;
        try{
          xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
        }
        catch (e){
          try{
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
          }
          catch (e){
            try{
              xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e){
              alert("No AJAX");
              return false;
            }
          }
        }

        xmlHttp.onreadystatechange=function(){
          if(xmlHttp.readyState==4){
            document.getElementById('AutoUpdte').innerHTML=xmlHttp.responseText;
            setTimeout('AutoRefresh()',3000); // JavaScript function calls AutoRefresh() every 3 seconds
          }
        }
        xmlHttp.open("GET","pullinfo.php",true);
        xmlHttp.send(null);
      }

      AutoRefresh();

    </script>


    <!-- @end snippet -->
  </head>
<body>
  <img style="position:absolute; top: 16px; left:19px; width:233px; height:68px" src="http://static0.twilio.com/packages/twilio-conference/img/twilio-logo-2012-header.png">//logo
  

    <div id="content">
    <h2> <center> Developer Evangelist Conference Call Client  </center></h2>
        <h4> <center> Call in at (914) 819-5726 </center> </h4>
        <center> <a id="call-button" class="button" href="#">Join Conference</a> </center> 
       </div>
       <div style="position:absolute;left:50%;top:500px; width:900px; margin-left:-450px;"id="AutoUpdte">Error Loading Info
       </div>

  <a id='done' class='button' href='?logout'>Logout</a> //logout button

  </body>
</html>

<?

}else{ //Not twilio email
  unset($_SESSION['token']);
  $client->revokeToken(); 
  echo "<meta http-equiv='refresh' content='0;url=index.php?error=1'>";
}
}
else{ //not logged in 
  echo "<meta http-equiv='refresh' content='0;url=index.php?error=2'>";
}
?>