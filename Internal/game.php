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
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$last = mysqli_query($mysqli,"SELECT last_played From gamestatus Where last_changed=(Select MAX(last_changed) From gamestatus)");
			while ($row = mysqli_fetch_assoc($last)) {
        		$pre_card = $row['last_played'];
    		}
    		//pernw teleytaia karta pou paixtike
    		$pre = mysqli_query($mysqli,"SELECT deck_position From deck Where card_id = '$pre_card'");
    		while ($r = mysqli_fetch_assoc($pre)) {
        		$pp = $r['deck_position'];
    		}
    		//vriskw tin thesi tis
    		$pp++;
    		$next = mysqli_query($mysqli,"SELECT card_id From deck Where deck_position = '$pp'");
    		while ($rr = mysqli_fetch_assoc($next)) {
        		$next_card = $rr['card_id'];
    		}
    		//pernw tin epomeni
			$draw_next = "UPDATE deck Set card_status='user1' Where card_id='$next_card'";
			if ($mysqli->query($draw_next)){
				echo "User1 drew card with id $next_card";
			}else{
				echo "Error";
			}
			//tin vazw sto xeri tou user1
    	}elseif ($username === $r['user2']) {

			$last = mysqli_query($mysqli,"SELECT last_played From gamestatus Where last_changed=(Select MAX(last_changed) From gamestatus)");
			while ($row = mysqli_fetch_assoc($last)) {
        		$pre_card = $row['last_played'];
    		}
    		$pre = mysqli_query($mysqli,"SELECT deck_position From deck Where card_id = '$pre_card'");
    		while ($r = mysqli_fetch_assoc($pre)) {
        		$pp = $r['deck_position'];
    		}
    		$pp++;
    		$next = mysqli_query($mysqli,"SELECT card_id From deck Where deck_position = '$pp'");
    		while ($rr = mysqli_fetch_assoc($next)) {
        		$next_card = $rr['card_id'];
    		}
    		
			$draw_next = "UPDATE deck Set card_status='user2' Where card_id='$next_card'";
			if ($mysqli->query($draw_next)){
				echo "User2 drew card with id $next_card";
			}else{
				echo "Error";
			}
    	}
	}
	$mysqli->close();
}

?>