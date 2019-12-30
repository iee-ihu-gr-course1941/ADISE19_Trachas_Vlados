$(function()
{
	$('#draw_button').click( draw_card);
	$('#pass_button').click( give_turn);
});

function start_game()
{
	give_turn();
	$.ajax({url: "Internal/API.php/start_game", method: 'POST', success: draw_starting_hand });
}

function draw_starting_hand()
{
	$.ajax({url: "Internal/API.php/hand/vlado", success: draw_cards});
}
function draw_cards(data)
{
	h_cards=JSON.parse(data);
	for(var i=0;i<h_cards.length;i++)
	{
		print_card(i,"blue","hand_card");
		//$.ajax({url: "API.php/draw/vlado", success: print_card});
	}
	get_turn();
}

function draw_card()
{
	document.getElementById('draw_button').disabled = true;
	//$.ajax({url: "API.php/draw/vlado", success: print_card});
}

function print_card(card_number,card_color,place)
{
	card_id=card_number;//na to allaksw
	var card = document.createElement("div");
	card.classList.add(place);
	card.setAttribute("id", card_id);
	card.setAttribute("onclick", "play_card(this.id)");
	card.style.background=card_color;
	card.innerHTML=card_number;
	document.getElementById('hand').appendChild(card);
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