<?php

require_once "Internal/game.php"

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);

switch ($r=array_shift($request)) {
	case 'test1':
		# code...
		break;
	
	default:
		# code...
		break;
}

?>