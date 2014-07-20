<?php
/*
      ___           ___           ___           ___           ___           ___                   
     /\  \         /\  \         /\  \         /\__\         /\__\         /\  \         _____    
    _\:\  \        \:\  \       |::\  \       /:/  /        /:/ _/_       /::\  \       /::\  \   
   /\ \:\  \        \:\  \      |:|:\  \     /:/  /        /:/ /\  \     /:/\:\  \     /:/\:\  \  
  _\:\ \:\  \   ___ /::\  \   __|:|\:\  \   /:/  /  ___   /:/ /::\  \   /:/ /::\  \   /:/  \:\__\ 
 /\ \:\ \:\__\ /\  /:/\:\__\ /::::|_\:\__\ /:/__/  /\__\ /:/_/:/\:\__\ /:/_/:/\:\__\ /:/__/ \:|__|
 \:\ \:\/:/  / \:\/:/  \/__/ \:\~~\  \/__/ \:\  \ /:/  / \:\/:/ /:/  / \:\/:/  \/__/ \:\  \ /:/  /
  \:\ \::/  /   \::/__/       \:\  \        \:\  /:/  /   \::/ /:/  /   \::/__/       \:\  /:/  / 
   \:\/:/  /     \:\  \        \:\  \        \:\/:/  /     \/_/:/  /     \:\  \        \:\/:/  /  
    \::/  /       \:\__\        \:\__\        \::/  /        /:/  /       \:\__\        \::/  /   
     \/__/         \/__/         \/__/         \/__/         \/__/         \/__/         \/__/    


WHMCSAD

WHMCS module for SADpanel

v1.0.1
*/

$whmcsad_version = "1.0.1";

/** changelog

v1.01 --
* added stream_get_sockets
* sends message doesn't receive
--

v1.0 --
* added basic setup
* added params
* made for local use
* initial release
--

**/

function connect_to_master($serverip,$servermessage) {
	$client = stream_socket_client( $serverip /*will change to 127.0.0.1 to be sure */, $errno, $errorMessage);

	if ($client === false) {
		$result = "couldn't open socket / connect.";
	}

	fwrite($client, $servermessage);
	$returninfo = stream_get_contents($client);
	fclose($client);
	if ($result !== "couldn't open socket / connect.") { retreived_info_function_to_create_link_with_and_other_stuff($returninfo); }
	return $result;
}

function whmcsad_ConfigOptions() {
	$configarray = array(
		"packagename" => array( "Type" => "text", "Size" => "14", "Description" => "Name of the package in SADpanel." ),
		"RAM" => array( "Type" => "text", "Size" => "5", "Description" => "Amount of RAM in MBs." ),
		"players" => array( "Type" => "text", "Size" => "4", "Description" => "Amount of Players, insert 0 for no limit / highest possible." ),
		);
	return $configarray;
}

function whmcsad_CreateAccount($params) {
	$serviceid = $params["serviceid"]; 
	$clientsdetails = $params["clientsdetails"]; 
	$customfields = $params["customfields"];
	$package = $params["configoption1"];
	$RAM = $params["configoption2"];
	$players = $params["configoption3"];
	$serverip = $params["serverip"];

	$servermessage = "FUNC_CREATEACCOUNT;
	user:".$clientsdetails['email'].";
	players:".$players.";
	RAM:".$RAM.";
	email:".$clientsdetails['email'].";
	package:".$package.";";
	
	connect_to_master($serverip,$servermessage);
	
	return $result;	
	}
	


