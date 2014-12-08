<?php

$action = isset($_POST['action']) ? $_POST['action'] : false;

// Include setting value and functions
include_once 'config.php';
include_once 'functions.php';

if(!$action) die;

switch($action){
	case 'weather':
	case 'facilities':
	case 'social':
		if(isset($_POST['lat']) && isset($_POST['lon']))
			include_once 'content/' . $action . '.php';
	
	break;
	case 'economic':
		if(isset($_POST['country']))
			include_once 'content/economic.php';
	
	break;	
}
?>