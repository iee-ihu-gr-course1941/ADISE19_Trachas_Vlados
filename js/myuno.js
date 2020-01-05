var opponent_username='';//username of opponent client
var user='';//username of client
var my_user='';//usser1 or user2
var last_update=new Date().getTime();
var turn="";
var can_play=false;
var timer=null;

$(function()
{
	$('#game_login').click( login_to_game);
	$('#draw_button').click( draw_card);
	$('#pass_button').click( win_game);//NA TO ALLAKSW
});

function login_to_game()
{
	if($('#username').val()=='') {
		alert('You have to set a username');
		return;
	}
	user=document.getElementById("username").value;
	document.getElementById("username").remove();
	document.getElementById("game_login").remove();
	$.ajax({url: "Internal/API.php/login/"+user, success: start_game });
}

function start_game(data)
{
	dat=JSON.parse(data);
	my_user=dat;
	turn=2;
	if(my_user=='user2')
	{
		$.ajax({url: "Internal/API.php/start_game", method: 'POST', success: draw_starting_hand });
	}
	give_turn();
}

function draw_starting_hand()
{
	$.ajax({url: "Internal/API.php/hand/"+user, success: draw_cards});
	check_opponent_hand();
	$.ajax({url: "Internal/API.php/card_down", success : print_down_card});
}
function draw_cards(data)//trabaei tis prwtes kartes
{
	h_cards=JSON.parse(data);
	for(var i=0;i<h_cards.length;i++)
	{
		print_card(h_cards[i]);
	}
	get_turn();
}

function game_status_update()
{
	clearTimeout(timer);
	//ajax
}
function update_status(data)
{
	last_update=new Date().getTime();
	var old_turn = turn;
	t=JSON.parse(data);
	turn=t[0];
	cealTimeout(timer);
	if((my_user=="user1" && turn == 1) || (my_user=="user2" && turn == 2))
	{
		if(old_turn != turn)
		{
			$.ajax({url: "Internal/API.php/card_down", success : print_down_card});
			check_opponent_hand();
		}
		get_turn();
		timer=setTimeout(function() { game_status_update();}, 15000)
	}
	else
	{
		give_turn();
		timer=setTimeout(function() { game_status_update();}, 4000);
	}
}


function draw_card()
{
	document.getElementById('draw_button').disabled = true;
	$.ajax({url: "Internal/API.php/draw/" + user, success: print_drawn_card});
}

function print_drawn_card(data)
{
	new_card=JSON.parse(data);
	print_card(new_card);
}

function print_down_card(data)
{
	console.log(data);
	//new_card=JSON.parse(data);
	//print_card(new_card,"board_card");
}

function print_card(new_card,place)//place = hand_card OR place = board_card
{
	place = typeof a !== 'undefined' ? a : "hand_card";
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



function try_play_card(card_id)
{
	if(can_play)
	{
		$.ajax({url: "Internal/API.php/play_card/"+user+"/"+card_id,
				 method: "PUT",
				 success: play_card(card_id)});
	}
	
}
function play_card(card_id)
{
	document.getElementById(card_id).remove();
}

function get_turn()
{
	can_play=true;
	document.getElementById('draw_button').disabled=false;
	document.getElementById('pass_button').disabled=false;
}

function give_turn()
{
	can_play=false;
	document.getElementById('draw_button').disabled=true;
	document.getElementById('pass_button').disabled=true;
}

function check_opponent_hand()
{
	$.ajax({url: "Internal/API.php/opponent_hand/"+user , success: update_opponent_hand});
}
function update_opponent_hand(data)
{
	cards=JSON.parse(data);
	document.getElementById('opponent_cards').innerHTML= cards;
}



//end of game
function win_game()
{
	$.ajax({url: "Internal/API.php/end_game", success: refresh_page});
}
function refresh_page()
{
	location.reload();
}