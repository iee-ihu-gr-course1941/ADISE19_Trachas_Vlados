<?php 

function get_card_down(){
	require_once "dbconnect2.php";
	require_once 'deck.php';
	$sql = "SELECT last_played From GameStatus Where s_id='0'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$id = $row['last_played'];
	$data[0] = $deck[$id];
	//dinei kateuthian to xrwma pou einai katw se periptosi pou paixtike mauri karta
	$sql = "SELECT current_color From GameStatus Where s_id='0'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$data[1] = $row['current_color'];

	echo json_encode( $data );
	$mysqli->close();
}

function get_players_hand($username){
	require_once "dbconnect2.php";
	require_once 'deck.php';
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM GameStatus");
	$cards_id = array();
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='user1'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}elseif ($username === $r['user2']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='user2'");
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
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM GameStatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$next_draw = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='deck' AND deck_position=(Select MIN(deck_position) FROM Deck WHERE card_status='deck')");
			while($l= mysqli_fetch_assoc($next_draw)){
				$ls = $l['card_id'];
			}			

			$h1 = "UPDATE Deck Set card_status='user1' Where card_id='$ls'";
    		$mysqli->query($h1);

			$card_info = $deck[$ls];
			echo json_encode($card_info);


    	}elseif ($username === $r['user2']) {
    		$next_draw = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='deck' AND deck_position=(Select MIN(deck_position) FROM Deck WHERE card_status='deck')");
			while($l= mysqli_fetch_assoc($next_draw)){
				$ls = $l['card_id'];
			}			
			
			$h2 = "UPDATE Deck Set card_status='user2' Where card_id='$ls'";
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

	$sql = "SELECT last_played From GameStatus Where s_id='0'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$c_id = $row['last_played'];

	$cards_id = array();
	$down = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='down'");
	while ($d = mysqli_fetch_assoc($down)){
		if ($d['card_id']!= $c_id) {
			$cards_id[]= $d['card_id'];
		}
	}

	$h1 = array();
   	$hand1 = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='user1'");
	while($row = mysqli_fetch_assoc($hand1)) {
        $h1[] = $row['card_id'];
    }

    $h2 = array();
    $hand2 = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='user2'");
	while($row = mysqli_fetch_assoc($hand2)) {
        $h2[] = $row['card_id'];
    }

   	$deleteDeck = "TRUNCATE Deck";
	$mysqli->query($deleteDeck);

	$first = "INSERT INTO Deck Values('$c_id','down','0')";
    $mysqli->query($first);
	
    $count1 = count($h1);
    $count2 = count($h2);
    $a = $count1+$count2;
    $b = count($cards_id);

    $c=0;
    for ($i=1; $i < $count1+1 ; $i++) { 
    	$d1 = "INSERT INTO Deck Values('$h1[$c]','user1','$i')";
   		$mysqli->query($d1);
   		$c++;
    }

   	$c=0;
    for ($i=$count1+1; $i < $a+1 ; $i++) { 
    	$d2 = "INSERT INTO Deck Values('$h2[$c]','user2','$i')";
   		$mysqli->query($d2);
   		$c++;
    }
    $c=0;
    shuffle($cards_id);
    for ($i=$a+1; $i < 108 ; $i++) { 
    	$dd = "INSERT INTO Deck Values('$cards_id[$c]','deck','$i')";
   		$mysqli->query($dd);
   		$c++;
    }
    
    
    $mysqli->close();
	
}

function start_game(){
	require_once "dbconnect2.php";
	require_once 'deck.php';
	require_once 'card.php';
	$numbers=range(0,107);
    shuffle($numbers);

	$delete = "TRUNCATE Deck";
	$mysqli->query($delete);

    for ($i=1; $i < 108 ; $i++) { 
    	$position = $numbers[$i];
    	$deck_s = "INSERT INTO Deck Values('$i','deck','$position')";
    	$mysqli->query($deck_s);
    }
    
    $first = "UPDATE Deck Set card_status='down' Where deck_position='0'";
	$mysqli->query($first);

    for ($i=1; $i <8 ; $i++) { 
    	
    	$h1 = "UPDATE Deck Set card_status='user1' Where deck_position='$i'";
    	$mysqli->query($h1);
    }
    
    for ($i=8; $i <15 ; $i++) { 
    	$h2 = "UPDATE Deck Set card_status='user2' Where deck_position='$i'";
    	$mysqli->query($h2);
    }
    
    $down =  mysqli_query($mysqli,"SELECT card_id From Deck Where card_status='down'");
    while ($d = mysqli_fetch_assoc($down)) {
    	$down_card = $d['card_id'];
    }
    $color = $deck[$down_card]->get_color();
    $status = "UPDATE GameStatus SET last_played = '$down_card', current_color='$color', current_player='2' WHERE s_id='0'";
	$mysqli->query($status);

	$mysqli->close();
}

function play($username,$card_p){
	require_once "dbconnect2.php";
	require_once 'deck.php';
	require_once 'card.php';
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM GameStatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			
			$play = "UPDATE Deck Set card_status='down' WHERE card_id=$card_p";
			$mysqli->query($play);
			
			$user1 = $r['user1'];
			$user2 = $r['user2'];
			$color = $deck[$card_p]->get_color();
			$status_update = "UPDATE GameStatus SET played_by='1', last_played='$card_p' WHERE s_id='0'";
			$mysqli->query($status_update);
			
		}elseif ($username === $r['user2']) {
			
			$play = "UPDATE Deck Set card_status='down' WHERE card_id=$card_p";
			$mysqli->query($play);
			
			$user1 = $r['user1'];
			$user2 = $r['user2'];
			$color = $deck[$card_p]->get_color();
			$status_update = "UPDATE GameStatus SET played_by='2', last_played='$card_p' WHERE s_id='0'";
			$mysqli->query($status_update);
			
		}
	}
	$mysqli->close();
}

function register($username){
	require_once "dbconnect2.php";
	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM GameStatus");
	while ($r = mysqli_fetch_assoc($users)) {
		if ( empty($r['user1']) ){
			$inu1 = "UPDATE GameStatus SET user1='$username' WHERE s_id ='0'";
			$mysqli->query($inu1);
			echo json_encode("user1");
		}elseif (empty($r['user2'])){
			$inu2 = "UPDATE GameStatus SET user2='$username' WHERE s_id='0'";
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

	$deleteDeck = "TRUNCATE Deck";
	$mysqli->query($deleteDeck);

	$resetStatus = "UPDATE GameStatus SET current_player='0', last_played='0', played_by='0',current_color='',user1='', user2='' WHERE s_id='0'";
	$mysqli->query($resetStatus);

	$mysqli->close();
}

function opponent_hand($username){
	require_once "dbconnect2.php";

	$users = mysqli_query($mysqli,"SELECT DISTINCT user1,user2 FROM GameStatus");
	$cards_id = array();
	while ($r = mysqli_fetch_assoc($users)) {
		if ($username === $r['user1']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='user2'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}elseif ($username === $r['user2']) {
			$sql = mysqli_query($mysqli,"SELECT card_id FROM Deck WHERE card_status='user1'");
			while($row = mysqli_fetch_assoc($sql)) {
        		$cards_id[] = $row['card_id'];
    		}
		}
	}
	$counter = count($cards_id);
	echo json_encode($counter);

	$mysqli->close();
}

function get_turn(){
	require_once "dbconnect2.php";
	$sql = "SELECT current_player From GameStatus Where s_id='0'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$data = $row['current_player'];
	echo json_encode( $data );
	$mysqli->close();
}

function set_turn($card,$down_card,$card_color){
	require_once "dbconnect2.php";
	require_once "deck.php";
	require_once "card.php";
	require_once "board.php";

	if ($card == "pass") {
		$sql = "SELECT current_player From GameStatus Where s_id='0'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$data = $row['current_player'];
		
		if($data == "2"){
			$turn = "UPDATE GameStatus SET current_player='1' WHERE s_id='0'";
			$mysqli->query($turn);
		}elseif($data == "1"){
			$turn = "UPDATE GameStatus SET current_player='2' WHERE s_id='0'";
			$mysqli->query($turn);
		}
	}else{
		$sql = "SELECT current_color From GameStatus Where s_id='0'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$col = $row['current_color'];
		$can_play = playable_card($card,$down_card,$col);
		if($can_play)
		{
			$next_color="";
			if($card_color=="no_change")
			{
				$next_color=$deck[$card]->get_color();
			}else
			{
				$next_color=$card_color;
			}
			$number = $deck[$card]->get_number();
			if ($number!='B' AND $number!='R') 
			{
				$sql = "SELECT current_player From GameStatus Where s_id='0'";
				$result = $mysqli->query($sql);
				$row = $result->fetch_assoc();
				$data = $row['current_player'];
				if($number === "+2" OR $number === "+4") 
				{
					opponent_draw_cards($data,$number,$mysqli);
				}
				if($data == "2"){
					$turn = "UPDATE GameStatus SET current_player='1', current_color='$next_color'  WHERE s_id='0'";
					$mysqli->query($turn);
				}elseif($data == "1"){
					$turn = "UPDATE GameStatus SET current_player='2', current_color='$next_color' WHERE s_id='0'";
					$mysqli->query($turn);
				}
			}
			echo json_encode(true);
		}else{
			echo json_encode(false);
		}
		
	}
	
	$mysqli->close();
}

function opponent_draw_cards($this_player,$number_of_cards,$mysql)
{
	require_once "deck.php";
	require_once "dbconnect2.php";
	$username="";
	if($this_player=="2")
	{
		$sql = "SELECT user1 From GameStatus Where s_id='0'";
		$result = $mysql->query($sql);
		$row = $result->fetch_assoc();
		$username = $row['user1'];
	}elseif($this_player=="1")
	{
		$sql = "SELECT user2 From GameStatus Where s_id='0'";
		$result = $mysql->query($sql);
		$row = $result->fetch_assoc();
		$username = $row['user2'];
	}

	$num=0;
	if($number_of_cards==="+2")
	{
		$num=2;
	}elseif ($number_of_cards==="+4") {
		$num=4;
	}
	for($i=0; $i<$num; $i++)
	{
		$users = mysqli_query($mysql,"SELECT DISTINCT user1,user2 FROM GameStatus");
		while ($r = mysqli_fetch_assoc($users)) {
			if ($username === $r['user1']) {
				$next_draw = mysqli_query($mysql,"SELECT card_id FROM Deck WHERE card_status='deck' AND deck_position=(Select MIN(deck_position) FROM Deck WHERE card_status='deck')");
				while($l= mysqli_fetch_assoc($next_draw)){
					$ls = $l['card_id'];
				}			

				$h1 = "UPDATE Deck Set card_status='user1' Where card_id='$ls'";
	    		$mysql->query($h1);


	    	}elseif ($username === $r['user2']) {
	    		$next_draw = mysqli_query($mysql,"SELECT card_id FROM Deck WHERE card_status='deck' AND deck_position=(Select MIN(deck_position) FROM Deck WHERE card_status='deck')");
				while($l= mysqli_fetch_assoc($next_draw)){
					$ls = $l['card_id'];
				}			
				
				$h2 = "UPDATE Deck Set card_status='user2' Where card_id='$ls'";
	    		$mysql->query($h2);
	    	}
		}
	}
}
?>