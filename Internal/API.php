<?php 

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));


switch ($r=array_shift($request)) {
	case 'card_down': handle_board($method);break;

	case 'hand' : handle_start($method,$request[0]);break;
	
	case 'status': handle_status($method,$request[0]);break;

	default: header("HTTP/1.1 404 Not Fount");break;
}


function handle_board($method){
	if ($method === 'GET') {
		$data = ['top_card' => '100'];
		header('Content-type: application/json');
		echo json_encode( $data );
	}else{
		header("HTTP/1.1 404 Wrong End");
	}
}
function handle_start($method,$user){
	print("post");
}
function handle_status($method,$user){
	print("test");
}

?>

