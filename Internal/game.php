<?php 

function get_card_down(){
	require_once "dbconnect2.php";
	$sql = "SELECT last_played From gamestatus Where s_id='0'";
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
	require_once 'deck.php';

	$sql = "SELECT last_played From gamestatus Where s_id='0'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$c_id = $row['last_played'];

	$cards_id = array();
	$down = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='down'");
	while ($d = mysqli_fetch_assoc($down)){
		if ($d['card_id']!= $c_id) {
			$cards_id[]= $d['card_id'];
		}
	}

	$h1 = array();
   	$hand1 = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user1'");
	while($row = mysqli_fetch_assoc($hand1)) {
        $h1[] = $row['card_id'];
    }

    $h2 = array();
    $hand2 = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user2'");
	while($row = mysqli_fetch_assoc($hand2)) {
        $h2[] = $row['card_id'];
    }

   	$deleteDeck = "TRUNCATE deck";
	$mysqli->query($deleteDeck);

	$first = "INSERT INTO deck Values('$c_id','down','0')";
    $mysqli->query($first);
	
    $count1 = count($h1);
    $count2 = count($h2);
    $a = $count1+$count2;
    $b = count($cards_id);

    $c=0;
    for ($i=1; $i < $count1+1 ; $i++) { 
    	$d1 = "INSERT INTO deck Values('$h1[$c]','user1','$i')";
   		$mysqli->query($d1);
   		$c++;
    }

   	$c=0;
    for ($i=$count1+1; $i < $a+1 ; $i++) { 
    	$d2 = "INSERT INTO deck Values('$h2[$c]','user2','$i')";
   		$mysqli->query($d2);
   		$c++;
    }
    $c=0;
    shuffle($cards_id);
    for ($i=$a+1; $i < 108 ; $i++) { 
    	$dd = "INSERT INTO deck Values('$cards_id[$c]','deck','$i')";
   		$mysqli->query($dd);
   		$c++;
    }
    
    
    $mysqli->close();
	
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
			
			$status_update = "UPDATE gamestatus SET current_player='1', last_played='$card' WHERE s_id='0'";
			$mysqli->query($status_update);
			
		}elseif ($username === $r['user2']) {
			
			$play = "UPDATE deck Set card_status='down' WHERE card_id=$card";
			$mysqli->query($play);
			
			$user1 = $r['user1'];
			$user2 = $r['user2'];
			
			$status_update = "UPDATE gamestatus SET current_player='2', last_played='$card' WHERE s_id='0'";
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
			echo json_encode("user1");
		}elseif (empty($r['user2'])){
			$inu2 = "UPDATE gamestatus SET user2='$username' WHERE s_id='0'";
			$mysqli->query($inu2);
			echo json_encode("user2");
		}else{
			header("HTTP/1.1 400 No More Users Allowed");
			echo json_encode("Error 400 No More Users Allowed");
		}
	}
	$mysqli->close();
}

function end_game(){
	require_once "dbconnect2.php";

	$deleteDeck = "TRUNCATE deck";
	$mysqli->query($deleteDeck);

	$resetStatus = "UPDATE gamestatus SET current_player='0', last_played='0', user1='', user2='' WHERE s_id='0'";
	$mysqli->query($resetStatus);

	$mysqli->close();
}

function opponent_hand($username){
	require_once "dbconnect2.php";

	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM gamestatus");
	$cards_id = array();
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user2'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}elseif ($username === $r['user2']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM deck WHERE card_status='user1'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}
	}
	$counter = count($cards_id);
	echo json_encode($counter);

	$mysqli->close();
}
?>