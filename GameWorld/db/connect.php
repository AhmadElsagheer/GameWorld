<?php

$db = new mysqli('127.0.0.1', 'root', '', 'GameWorld');

// echo $db->connect_errno, '<br>';	//if 0 then no error
// echo $db->connect_error, '<br>';	//More friendly error statement
if($db->connect_errno)
	die('Sorry, we are having some problems.');	
/* die: print the statement and stop execution of 
	the coming statements. */