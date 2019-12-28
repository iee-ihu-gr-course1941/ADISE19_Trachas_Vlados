<?php 

function get_card_down(){
	require_once "dbconnect2.php";
	$sql = "SELECT last_played From gamestatus Where last_changed=(Select MAX(last_changed) From gamestatus)";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
		
	$data = ['top_card' => $row['last_played']];
	header('Content-type: application/json');
	echo json_encode( $data );
	$mysqli->close();
}

function get_players_hand($username){
	require_once "dbconnect2.php";
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	$cards = array();
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user1'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards[] = $row['card_id'];
    		}
		}elseif ($username === $r['user2']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user2'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards[] = $row['card_id'];
    		}
		}
	}
	echo json_encode($cards);
	$mysqli->close();	
}

function draw_card($username){
	require_once "dbconnect2.php";

}

?>