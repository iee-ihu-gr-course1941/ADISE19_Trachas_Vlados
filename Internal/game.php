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
	require_once 'deck.php';
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	$cards_id = array();
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user1'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}elseif ($username === $r['user2']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user2'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}
	}
	$counter = count($cards_id);
	$cards = array();
	for ($i=0; $i < $counter; $i++) { 
		$card_id = $cards_id[$i];
		$cards[$i] = $deck[$card_id];
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
			$last_played = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='deck' AND deck_position=(Select MIN(deck_position) FROM deck WHERE card_status='deck')");
			while($l= mysqli_fetch_assoc($last_played)){
				$ls = $l['card_id'];
			}			

			$h1 = "UPDATE deck Set card_status='user1' Where card_id='$ls'";
    		$mysqli->query($h1);

			$card_info = $deck[$ls];
			echo json_encode($card_info);


    	}elseif ($username === $r['user2']) {
    		$last_played = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='deck' AND deck_position=(Select MIN(deck_position) FROM deck WHERE card_status='deck')");
			while($l= mysqli_fetch_assoc($last_played)){
				$ls = $l['card_id'];
			}			
			
			$h2 = "UPDATE deck Set card_status='user2' Where card_id='$ls'";
    		$mysqli->query($h2);
			
			$card_info = $deck[$ls];
			echo json_encode($card_info);
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

    for ($i=1; $i < 108 ; $i++) { 
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
    
    $down =  mysqli_query($mysqli,"SELECT card_id From deck Where card_status='down'");
    while ($d = mysqli_fetch_assoc($down)) {
    	$down_card = $d['card_id'];
    }

    $status = "UPDATE gamestatus SET last_played = '$down_card' WHERE s_id='0'";
	$mysqli->query($status);
	$mysqli->close();
}

function play($username,$card){
	require_once "dbconnect2.php";
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			
			$play = "UPDATE deck Set card_status='down' WHERE card_id=$card";
			$mysqli->query($play);
			
			$user1 = $r['user1'];
			$user2 = $r['user2'];
			
			$status_update = "INSERT INTO gamestatus VALUES('0','1','$card',null,'$user1','$user2')";
			$mysqli->query($status_update);
			
		}elseif ($username === $r['user2']) {
			
			$play = "UPDATE deck Set card_status='down' WHERE card_id=$card";
			$mysqli->query($play);
			
			$user1 = $r['user1'];
			$user2 = $r['user2'];
			
			$status_update = "INSERT INTO gamestatus VALUES('0','2','$card',null,'$user1','$user2')";
			$mysqli->query($status_update);
			
		}
	}
	$mysqli->close();
}

function login($username){
	require_once "dbconnect2.php";
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ( empty($r['user1']) ){
			$inu1 = "UPDATE gamestatus SET user1='$username' WHERE s_id ='0'";
			$mysqli->query($inu1);
			echo "$username is user1";
		}elseif (empty($r['user2'])){
			$inu2 = "UPDATE gamestatus SET user2='$username' WHERE s_id='0'";
			$mysqli->query($inu2);
			echo "$username is user2";
		}else{
			echo "User slots are full";
		}
	}
	$mysqli->close();
}



?>