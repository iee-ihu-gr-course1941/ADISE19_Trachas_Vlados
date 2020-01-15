var user='';//username of client
var my_user='';//user1 or user2
var last_update=new Date().getTime();
var turn="";//var to see whom turn is now
var can_play=false; //bool to see if client can play
var timer=null;
var opponent_cards=7;
var user_cards=7;

$(function()
{
	$('#game_login').click( login_to_game);
	$('#draw_button').click( draw_card);
	$('#pass_button').click( pass_turn);
	$('#red_button').click( set_color);
	$('#green_button').click( set_color);
	$('#yellow_button').click( set_color);
	$('#blue_button').click( set_color);
	game_status_update();
});

function login_to_game()
{
	if($('#username').val()=='') {
		alert('You have to set a username');
		refresh_page();
		return;
	}else if($('#username').val()=='RESTART')//an sto username balei "RESTART" teleiwnei to paixnidi kai kanei restart tin basi
	{
		alert('RESTARTING');
		$.ajax({url: "Internal/API.php/end_game",method: "POST", success: refresh_page});
		return;
	}
	user=document.getElementById("username").value;
	document.getElementById("username").remove();
	document.getElementById("game_login").remove();
	$.ajax({url: "Internal/API.php/register/"+user, method: "POST",  success: start_game });
}

function start_game(data)
{
	dat=JSON.parse(data);
	my_user=dat;
	turn=2;
	if(my_user=='user2')
	{
		$.ajax({url: "Internal/API.php/start_game", method: 'POST', success: refresh_hand_and_board });
	}
	give_turn();
}

function refresh_hand_and_board()//methodos pou kanei refresh to UI tou xeriou kai tis kartas pou einai katw
{
	var hand = document.getElementById('hand');
	while(hand.hasChildNodes())
	{
		hand.removeChild(hand.firstChild);
	}
	$.ajax({url: "Internal/API.php/hand/"+user, success: draw_cards});
	$.ajax({url: "Internal/API.php/card_down", success : print_down_card});
}
function draw_cards(data)//trabaei tis kartes pou krataei
{
	h_cards=JSON.parse(data);
	user_cards=h_cards.length;
	for(var i=0;i<user_cards;i++)
	{
		print_card(h_cards[i]);
	}
}

function game_status_update()
{
	clearTimeout(timer);
	$.ajax({url: "Internal/API.php/get_turn", success: update_status });
}
function update_status(data)//update listener
{
	last_update=new Date().getTime();
	var old_turn = turn;
	turn=0;
	t=JSON.parse(data);
	turn=t;
	
	clearTimeout(timer);

	if(turn==0)
	{
		timer=setTimeout(function() { game_status_update();}, 6000);
	}else if((my_user=="user1" && turn == 1) || (my_user=="user2" && turn == 2))
	{//an einai o guros tou kanei ta parakatw
		if(!can_play)
		{
			get_turn();
		}
		if(old_turn != turn)
		{
			refresh_hand_and_board();
		}
		check_opponent_hand();
		timer=setTimeout(function() { game_status_update();}, 2000)
	}
	else if((my_user=="user1" && turn == 2) || (my_user=="user2" && turn == 1))
	{//perimenei na paiksei
		refresh_hand_and_board();
		check_opponent_hand();
		timer=setTimeout(function() { game_status_update();}, 2000);
	}
}


function draw_card()//trabaei mia karta
{
	user_cards++;
	document.getElementById('draw_button').disabled = true;
	$.ajax({url: "Internal/API.php/draw/" + user, success: print_drawn_card});
}

function print_drawn_card(data)//emfanizei karta sto UI tou xeriou
{
	new_card=JSON.parse(data);
	print_card(new_card);
}

function print_down_card(data)//emfanizei tin karta pou paixtike sto UI
{
	new_card=JSON.parse(data);
	new_card[0]["color"]=new_card[1];
	print_card(new_card[0],"board_card");
}

function print_card(new_card,place)//methodos gia tin emfanisi kartwn
{
	place = typeof place !== 'undefined' ? place : "hand_card";
	//place = hand_card OR place = board_card
	card_id=new_card["card_id"];
	card_number=new_card["number"];
	card_color=new_card["color"];	
	
	var card = document.createElement("div");
	card.classList.add(place);
	card.setAttribute("id", card_id);
	card.style.background=card_color;
	card.innerHTML=card_number;
	if (place == "hand_card") 
	{
		card.setAttribute("onclick", "try_play_card(this.id)");
		document.getElementById('hand').appendChild(card);
	}else if(place == "board_card")
	{
		$('#board_center').html(card);
	}
}

function pass_turn()//methodos gia na dwsei ton guro tou ston antipalo
{
	$.ajax({url: "Internal/API.php/set_turn/pass/pass" ,method: 'POST'});
	give_turn();
}


function try_play_card(card_id)
{
	if(can_play)
	{
		$.ajax({url: "Internal/API.php/card_down", success: function(result){
			dat=JSON.parse(result);
			d_card=dat[0]["card_id"];
			card_color=document.getElementById(card_id).style.background;
			if(card_color=="black")
			{
				change_color(card_id,d_card);
				return;
			}
			$.ajax({url: "Internal/API.php/set_turn/"+card_id+"/"+d_card+"/"+"no_change" ,method: 'POST', success: function(result){
				dat=JSON.parse(result);
				if(dat)
				{
					$.ajax({url: "Internal/API.php/play_card/"+user+"/"+card_id, method: "POST", success: play_card(card_id)});
				}
			}});
		}});
	}
}
var color_card_id = 0;
var color_card_down = 0;
function change_color(card_id,d_card)
{
	color_card_id=card_id;
	color_card_down=d_card
	document.getElementById("change_color").style.visibility = "visible";
}

function set_color()
{
	col="";
	if(this.id=="red_button")
	{
		col="red";
	}else if(this.id=="green_button")
	{
		col="green";
	}else if(this.id=="yellow_button")
	{
		col="yellow";
	}else
	{
		col="blue";
	}
	$.ajax({url: "Internal/API.php/set_turn/"+color_card_id+"/"+color_card_down+"/"+col ,method: 'POST', success: function(result){
	dat=JSON.parse(result);
	if(dat)
	{
		$.ajax({url: "Internal/API.php/play_card/"+user+"/"+color_card_id, method: "POST", success: play_card(card_id)});
	}
	}});
	document.getElementById("change_color").style.visibility = "hidden";
}

function play_card(card_id)
{
	var this_card = {};
	this_card["card_id"]=card_id;
	this_card["color"]=document.getElementById(card_id).style.background;
	this_card["number"]=document.getElementById(card_id).innerHTML;
	document.getElementById(card_id).remove();
	print_card(this_card, "board_card");
	user_cards--;
	if(user_cards==0){win_game();}
	give_turn();
}

function get_turn()//energopoiei ta koumpia otan arxizei o guros tou client
{
	can_play=true;
	document.getElementById('draw_button').disabled=false;
	document.getElementById('pass_button').disabled=false;
}

function give_turn()//apenergopoiei ta koumpia otan teleiwnei o guros tou client
{
	can_play=false;
	document.getElementById('draw_button').disabled=true;
	document.getElementById('pass_button').disabled=true;
}

function check_opponent_hand()//methodos gia na brei poses kartes exei o antipalos
{
	$.ajax({url: "Internal/API.php/opponent_hand/"+user , success: update_opponent_hand});
}
function update_opponent_hand(data)//methodos gia na allaksei ton arithmo kartwn tou antipalou
{
	cards=JSON.parse(data);
	opponent_cards = cards;
	document.getElementById('opponent_cards').innerHTML= cards;
	if(opponent_cards==0 && user_cards>0) {lose_game();}//an o antipalos exei 0 kartes sto xeri xanei o client
}



//end of game
function lose_game()
{
	alert("YOU LOSE");
	$.ajax({url: "Internal/API.php/end_game",method: "POST", success: refresh_page});
}
function win_game()
{
	alert("YOU ARE THE WINNER");
	refresh_page();
}
function refresh_page()
{
	location.reload();
}