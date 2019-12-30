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
	require 'deck.php';
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			
			
    	}elseif ($username === $r['user2']) {

			
    	}
	}
	$mysqli->close();
}

function deck_ended(){
	require_once "dbconnect2.php";
	$sql = mysqli_query($mysqli,"SELECT deck_counter FROM gamestatus Where last_changed=(Select MAX(last_changed) From gamestatus)");
	while ($row = mysqli_fetch_assoc($sql)) {
		$dc = $row['deck_counter'];
	}
	$last = mysqli_query($mysqli,"SELECT last_played From gamestatus Where last_changed=(Select MAX(last_changed) From gamestatus)");
	while ($l = mysqli_fetch_assoc($last)) {
        $last_played = $l['last_played'];
    }
    echo json_encode($last_played);
    $down =  mysqli_query($mysqli,"SELECT card_id From deck Where card_status='down'");
    while ($d = mysqli_fetch_assoc($down)) {
    	$down_cards = $d['card_id'];
    }
}

function start_game(){
	require_once "dbconnect2.php";
	require_once 'deck.php';
	$numbers=range(0,107);
    shuffle($numbers);

	$delete = "TRUNCATE deck";
	$mysqli->query($delete);

    for ($i=1; $i < 107  ; $i++) { 
    	$position = $numbers[$i];
    	$deck = "INSERT INTO deck Values('$i','deck','$position')";
    	$mysqli->query($deck);
    }

    $first = "UPDATE deck Set card_status='down' Where deck_position='0'";
	$mysqli->query($first);

    for ($i=1; $i <8 ; $i++) { 
    	
    	$h1 = "UPDATE deck Set card_status='user1' Where deck_position='$i'";
    	$mysqli->query($h1);
    }

    for ($i=8; $i <15 ; $i++) { 
    	
    	$h2 = "UPDATE deck Set card_status='user2' Where deck_position='$i'";
    	$mysqli->query($h2);
    }
}
?>