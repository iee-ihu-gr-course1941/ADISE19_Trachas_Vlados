<?php 
require "game.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));


switch ($r=array_shift($request)) {
	case 'card_down': handle_board($method);break;

	case 'hand' : handle_hand($method,$request[0]);break;//to request einai to poios user einai
	
	case 'draw' : handle_draw($method,$request[0]);break;//to request einai to poios user einai

	case 'start_game' : handle_start($method);break;

	case 'play_card' : handle_play($method,$request[0],$request[1]);break;

	case 'deck_ended' : handle_deck_end($method);break;

	case 'login' : handle_login($method,$request[0]);break;

	case 'end_game' : handle_endgame($method);break;

	case 'opponent_hand' : handle_opponent_hand($method,$request[0]);break;

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
	if ($method === 'GET') {
		get_players_hand($username);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}

}
function handle_draw($method,$username){
	if ($method === 'GET') {
		draw_card($username);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}
function handle_start($method){
	if ($method === 'POST') {
		start_game();
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}

function handle_play($method,$username,$card){
	if ($method === 'POST'){
		play($username,$card);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}

function handle_deck_end($method){
	if ($method === 'POST') {
		deck_ended();
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}

function handle_login($method,$username){
	if ($method === 'GET') {
		login($username);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}

function handle_endgame($method){
	if ($method === 'GET') {
		end_game();
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}

function handle_opponent_hand($method,$username){
	if ($method === 'GET') {
		opponent_hand($username);
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}
?>

