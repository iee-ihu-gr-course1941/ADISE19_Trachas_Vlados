var opponent_username='traxas';

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
	start_game();
}


function start_game()
{
	give_turn();
	$.ajax({url: "Internal/API.php/start_game", method: 'PUT', success: draw_starting_hand });
}

function draw_starting_hand()
{
	$.ajax({url: "Internal/API.php/hand/vlado", success: draw_cards});
	check_enemy_hand();
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

function draw_card()
{
	document.getElementById('draw_button').disabled = true;
	$.ajax({url: "Internal/API.php/draw/vlado", success: print_card});
}

function print_card(data,place)//place = hand_card OR place = board_card
{
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



function play_card(card_id)
{
	//elegxos poianou guros einai, an mporeis na paiksei tin karta
	remove_card_from_hand(card_id);
}

function remove_card_from_hand(card_id)
{
	//allagi kartas sto board
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

function check_enemy_hand()
{
	$.ajax({url: "Internal/API.php/hand/".concat(opponent_username), success: update_enemy_hand});
}
function update_enemy_hand(data)
{
	cards=JSON.parse(data);
	document.getElementById('opponent_cards').innerHTML= cards.length;
}


function win_game()
{

}
