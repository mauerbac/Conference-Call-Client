<?php

include 'Services/Twilio.php'; 
include ('constants.php');

$client = new Services_Twilio(ACCOUNT_SID, TAUTH_TOKEN);


//find "in-progress" calls
foreach ($client->account->conferences->getIterator(0, 50, array( 'Status' => 'in-progress')) as $conf) {
  $confsid=$conf->sid;
}

if($confsid==""){
	//no current Call

}else{ //current conference information
	echo "<h4a>Current Conference: </h4a><br/>";
	echo "<h3><u>Participants:</u></h3><ul>";


$conference = $client->account->conferences->get($confsid); //get confsid of in-progress call
$page = $conference->participants->getPage(0, 50); 
$participants = $page->participants;
foreach ($participants as $p) {    //print info for each participant 
 $callsid=$p->call_sid;
$call= $client->account->calls->get($callsid); //get callSid of each participant
$from=$call->from;  //get From number of each participant 

	if($from=="" || $from==null){
		$from="Unauthorized Browser Call";
	}else if($from=="+15555555555"){
		$from="Test: Cell";
	}else if($from=="+12345678901"){
		$from="Test1: Cell";
	}else if($from=="client:Test"){
		$from="Test: Browser";	
	}else if($from=="client:Example"){
		$from="Example: Browser";			
	}else{
		$from="unauthorized number";
	}
 		print "<li><h3a>" .$from."</h3a></li>";

}	
echo "</ul>";
	}
   
?>