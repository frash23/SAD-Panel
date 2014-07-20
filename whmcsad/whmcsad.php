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

v1.1
*/

$whmcsad_version = "1.1";

/** changelog

v1.1 --
* repaired the socket function
* added (weak) password generator
--

v1.0.1 --
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

# DEFAULT CONFIG
# MODIFY TO NEEDS
$serverport = "21300";
# END DEFAULT CONFIG
# DO NOT MODIFY ANYTHING BELOW

function generatePassword($length = 12) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

function connect_to_master($serverip,$servermessage) {
	$serverip = "127.0.0.1";
	$serverport = "21300";
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	$result = socket_connect($socket, $serverip, $serverport);
	socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>5, "usec"=>0));
	socket_write($socket, "Test!", strlen("Test!"));
	$out = socket_read($socket, 2048);
	socket_close($socket);
	return $out;
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
	$password = generatePassword();

	$servermessage = "FUNC_CREATEACCOUNT;
	user:".$clientsdetails['email'].";
	players:".$players.";
	RAM:".$RAM.";
	email:".$clientsdetails['email'].";
	package:".$package.";";
	
	connect_to_master($serverip,$servermessage);
	if ($returnmessage === "success") { $do = "nothing yet"; }
	return $result;	
	}
	


