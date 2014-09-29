<?php 
require_once('../../../wp-load.php');
	
	$teste = file_get_contents('http://jelled.com/ajax/instagram?do=username&username='.get_option ('_theme_instagram_user'));

	//$teste = '//jelled.com/ajax/instagram?do=username&username='.get_option ('_theme_instagram_user');
	$obj = json_decode($teste);
	
		echo($obj->data[0]->id);
	
	exit;






?>