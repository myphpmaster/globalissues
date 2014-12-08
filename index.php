<?php 
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

error_reporting(E_ALL); // E_ALL

include_once 'config.php';

include_once 'functions.php';
include_once 'header.php'; 

include_once 'nav.php'; 
include_once 'main_content.php'; 

foreach ($site_menus as $id=>$name){
	include_once 'content/' . $id . '.php'; 
}

include_once 'footer.php';

?>