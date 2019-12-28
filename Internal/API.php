<?php 
require "game.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));


switch ($r=array_shift($request)) {
	case 'card_down': handle_board($method);break;

	case 'hand' : handle_hand($method,$request[0]);break;//to request einai to poios user einai
	
	case 'draw': handle_draw($method,$request[0]);break;//to request einai to poios user einai

	default: header("HTTP/1.1 404 Not Fount");break;
}


function handle_board($method){
	if ($method === 'GET') {
		get_card_down();
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}
function handle_hand($method,$username){
	if ($method === 'POST') {
		get_players_hand($username);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}

}
function handle_status($method,$username){
	if ($method === 'POST') {
		draw_card($username);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}

?>

