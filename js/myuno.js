var opponent_username='traxas';
var user='';
var my_user='';
var last_update=new Date().getTime();
var game_status="";
var timer=null;

$(function()
{
	$('#game_login').click( login_to_game);
	$('#draw_button').click( draw_card);
	$('#pass_button').click( give_turn);
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
	my_user.turn=dat;
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

function update_status(data)
{
	last_update=new Date().getTime();
	var game_stat_old = game_status;
	cealTimeout(timer);
	if(game_status.p_turn==my_user)
	{
		if(game_stat_old.p_turn!=game_status.p_turn)
		{

		}
	}else
	{
		give_turn();
		timer=setTimeout(function() { game_status_update();}, 4000);
	}
}


function draw_card()
{
	document.getElementById('draw_button').disabled = true;
	$.ajax({url: "Internal/API.php/draw/" + user, success: print_card});
}

function print_card(data,place)//place = hand_card OR place = board_card
{
	console.log(data);
	place = typeof a !== 'undefined' ? a : "hand_card";
	new_card=JSON.parse(data);
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
		card.setAttribute("onclick", "play_card(this.id)");
		document.getElementById('hand').appendChild(card);
	}else if(place == "board_card")
	{
		$('#board_center').html(card);
	}
}



function try_play_card(card_id)
{

	//elegxos poianou guros einai, an mporeis na paiksei tin karta
	$.ajax({url: "Internal/API.php/play_card/"+user+"/"+card_id,
				 method: "PUT",
				 /*
				 dataType: "json",
				 data: JSON.stringify({username: user, card: card_id}),
				 */
				 success: play_card});
}
function play_card(card_id)
{
	document.getElementById(card_id).remove();
}

function get_turn()
{
	document.getElementById('draw_button').disabled=false;
	document.getElementById('pass_button').disabled=false;
}

function give_turn()
{
	document.getElementById('draw_button').disabled=true;
	document.getElementById('pass_button').disabled=true;
}

function check_opponent_hand()
{
	$.ajax({url: "Internal/API.php/hand/"+opponent_username , success: update_enemy_hand});
}
function update_opponent_hand(data)
{
	//cards=JSON.parse(data);
	//document.getElementById('opponent_cards').innerHTML= cards;
}


function win_game()
{

}
